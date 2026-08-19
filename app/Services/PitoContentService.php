<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class PitoContentService
{
    /**
     * Bekende Strapi employment_type-labels naar de vaste frontend-tabs
     * (fulltime/parttime/bijbaan/stage/vrijwilligerswerk).
     */
    private const EMPLOYMENT_TYPE_MAP = [
        'full-time' => 'fulltime',
        'fulltime' => 'fulltime',
        'part-time' => 'parttime',
        'parttime' => 'parttime',
        'bijbaan' => 'bijbaan',
        'side job' => 'bijbaan',
        'stage' => 'stage',
        'internship' => 'stage',
        'vrijwilligerswerk' => 'vrijwilligerswerk',
        'volunteer' => 'vrijwilligerswerk',
    ];

    public function all(): array
    {
        $fixture = $this->fixtureContent();
        $strapi = $this->strapiContent();

        $content = $fixture;
        $usedStrapi = false;

        if ($strapi !== null) {
            $usedStrapi = true;
            $content['offers'] = $strapi['offers'];
            $content['jobs'] = $strapi['jobs'];
            $content['categories'] = $strapi['categories'] ?: $fixture['categories'];
            $content['job_tags'] = $strapi['job_tags'];
        } else {
            $content['job_tags'] = $this->jobTagsFromFixture($fixture['jobs'] ?? []);
        }

        $content['municipalities'] = $content['municipalities'] ?? $this->municipalities();
        $content['links'] = array_merge(config('pito.external', []), $content['links'] ?? []);

        return [
            'data' => $content,
            'meta' => [
                'source' => $usedStrapi ? 'strapi' : 'local-fixture',
                'generated_at' => now()->toIso8601String(),
            ],
        ];
    }

    public function municipalities(): array
    {
        $path = resource_path('data/municipalities.json');
        $names = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        $live = config('pito.live_municipalities', []);

        $items = collect($names)->map(function (string $name) use ($live): array {
            $slug = Str::slug($name);
            $state = $live[$slug] ?? 'inactive';

            return [
                'name' => $name,
                'slug' => $slug,
                'state' => $state,
                'is_live' => $state !== 'inactive',
            ];
        });

        return $items
            ->sortBy(fn (array $item) => sprintf('%d-%s', $item['is_live'] ? 0 : 1, $item['name']), SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();
    }

    /**
     * Haalt aanbod (/api/deals) en vacatures (/api/vacatures) op uit Strapi
     * en vertaalt ze naar het contract dat de Vue-frontend verwacht.
     */
    private function strapiContent(): ?array
    {
        if (! config('pito.strapi.enabled') || ! $this->hasUsableStrapiConfiguration()) {
            return null;
        }

        return Cache::remember('pito.strapi-content.v2', config('pito.strapi.cache_seconds'), function (): ?array {
            try {
                $municipalitySlugs = collect($this->municipalities())->pluck('slug')->all();

                $companyCities = $this->fetchCompanyCities();
                $rawDeals = $this->fetchStrapiCollection(config('pito.strapi.deals_endpoint'), [
                    'populate' => '*',
                    'pagination[pageSize]' => 100,
                ]);
                $rawVacatures = $this->fetchStrapiCollection(config('pito.strapi.vacatures_endpoint'), [
                    'populate' => 'image',
                    'pagination[pageSize]' => 100,
                ]);

                $categories = $this->extractCategories($rawDeals);

                $offers = collect($rawDeals)
                    ->map(fn (array $deal) => $this->mapDeal($deal, $companyCities, $municipalitySlugs))
                    ->values()
                    ->all();

                $jobs = collect($rawVacatures)
                    ->map(fn (array $vacature) => $this->mapVacature($vacature, $municipalitySlugs))
                    ->values()
                    ->all();

                return [
                    'offers' => $offers,
                    'jobs' => $jobs,
                    'categories' => $categories,
                    'job_tags' => collect($jobs)->flatMap(fn (array $job) => $job['tags'] ?? [])->unique()->sort()->values()->all(),
                ];
            } catch (Throwable $exception) {
                Log::warning('Strapi-content (deals/vacatures) kon niet worden geladen; lokale fallback wordt gebruikt.', [
                    'message' => $exception->getMessage(),
                ]);

                return null;
            }
        });
    }

    private function fetchStrapiCollection(string $endpoint, array $query): array
    {
        $request = Http::acceptJson()->timeout(config('pito.strapi.timeout'));
        if ($token = config('pito.strapi.token')) {
            $request = $request->withToken($token);
        }

        $response = $request->get(config('pito.strapi.url').$endpoint, $query);
        $response->throw();

        return Arr::get($response->json(), 'data', []);
    }

    /**
     * Bouwt een map van company-id => stad, op basis van het eerste adres
     * van elk bedrijf. Wordt gebruikt om deals aan een gemeente te koppelen.
     */
    private function fetchCompanyCities(): array
    {
        try {
            $companies = $this->fetchStrapiCollection('/api/companies', [
                'populate' => 'addresses',
                'pagination[pageSize]' => 200,
            ]);
        } catch (Throwable) {
            return [];
        }

        $map = [];
        foreach ($companies as $company) {
            $addresses = $company['addresses'] ?? [];
            if (! empty($addresses)) {
                $map[$company['id']] = $addresses[0]['city'] ?? null;
            }
        }

        return $map;
    }

    /**
     * Verzamelt alle unieke dealcategorieën die Strapi teruggeeft, in het
     * formaat {slug, label, description, icon, color} zodat de bestaande
     * OfferFilters-component ze kan tonen.
     */
    private function extractCategories(array $rawDeals): array
    {
        $categories = [];
        foreach ($rawDeals as $deal) {
            foreach ($deal['categories'] ?? [] as $category) {
                $slug = $category['slug'] ?? Str::slug($category['name'] ?? '');
                if ($slug === '' || isset($categories[$slug])) {
                    continue;
                }
                $categories[$slug] = [
                    'slug' => $slug,
                    'label' => $category['name'] ?? $slug,
                    'description' => $category['description'] ?? null,
                    'icon' => $category['icon'] ?? null,
                    'color' => $category['color'] ?? null,
                ];
            }
        }

        return array_values($categories);
    }

    /**
     * Vertaalt een Strapi-'deal' record naar het offer-contract van de
     * frontend (title, partner, scope_type, municipalities, category, ...).
     */
    private function mapDeal(array $deal, array $companyCities, array $municipalitySlugs): array
    {
        $company = $deal['company'] ?? null;
        $city = $company ? ($companyCities[$company['id']] ?? null) : null;
        $municipalitySlug = $this->resolveMunicipalitySlug($city, $municipalitySlugs);

        $firstCategory = $deal['categories'][0] ?? null;
        $categorySlug = $firstCategory
            ? ($firstCategory['slug'] ?? Str::slug($firstCategory['name'] ?? ''))
            : 'overig';

        return [
            'slug' => $deal['slug'] ?? Str::slug(($deal['title'] ?? 'aanbod').'-'.$deal['id']),
            'title' => $deal['title'] ?? 'Aanbod',
            'partner' => $company['name'] ?? 'Lokale ondernemer',
            'scope_type' => $municipalitySlug ? 'local' : 'national',
            'municipalities' => $municipalitySlug ? [$municipalitySlug] : [],
            'category' => $categorySlug,
            'image' => $this->resolveImageUrl($deal['image'] ?? null) ?? '/assets/pito-website-v18/deals-bakker-app-korting.png',
            'intro' => $deal['shortDescription'] ?? Str::limit(strip_tags((string) ($deal['description'] ?? '')), 140),
            'description' => $this->htmlToParagraphs($deal['description'] ?? null),
            'type' => 'external',
            'availability_mode' => match ($deal['availability'] ?? null) {
                'online' => 'online',
                'both' => 'online_and_store',
                default => 'store',
            },
            'cta_label' => 'Bekijk het aanbod',
            'external_url' => $deal['website'] ?? ($company['website'] ?? '#'),
        ];
    }

    /**
     * Vertaalt een Strapi-'vacature' record naar het job-contract van de
     * frontend (title, employer, type, municipalities, ...), inclusief de
     * 'tags' (branche) voor het extra vakgebied-filter.
     */
    private function mapVacature(array $vacature, array $municipalitySlugs): array
    {
        $municipalitySlug = $this->resolveMunicipalitySlug($vacature['city'] ?? null, $municipalitySlugs);
        $employmentType = Str::lower(trim((string) ($vacature['employment_type'] ?? '')));

        return [
            'slug' => $vacature['slug'] ?? Str::slug(($vacature['title'] ?? 'vacature').'-'.$vacature['id']),
            'title' => $vacature['title'] ?? 'Vacature',
            'employer' => Str::limit(trim((string) ($vacature['company_name'] ?? '')), 80) ?: 'Lokale werkgever',
            'municipalities' => $municipalitySlug ? [$municipalitySlug] : [],
            'type' => self::EMPLOYMENT_TYPE_MAP[$employmentType] ?? 'fulltime',
            'tags' => array_values(array_filter($vacature['tags'] ?? [])),
            'hours' => $vacature['employment_type'] ?? null,
            'image' => $this->resolveImageUrl($vacature['image'] ?? null) ?? '/assets/pito-website-v18/vacatures-beroepen-familie.png',
            'intro' => Str::limit(strip_tags((string) ($vacature['description'] ?? '')), 160),
            'workplace' => $vacature['location_type'] ?? null,
            'experience_level' => $vacature['experience_level'] ?? null,
            'compensation_label' => $this->compensationLabel($vacature),
            'location' => $vacature['city'] ?? $vacature['location'] ?? null,
            'description' => $this->htmlToParagraphs($vacature['description'] ?? null),
            'requirements' => $this->htmlToListItems($vacature['requirements'] ?? null),
            'benefits' => $this->htmlToListItems($vacature['benefits'] ?? null),
            'external_url' => $vacature['application_url']
                ?: ($vacature['application_email'] ? 'mailto:'.$vacature['application_email'] : '#'),
            'cta_label' => 'Bekijk de vacature',
        ];
    }

    private function compensationLabel(array $vacature): ?string
    {
        $min = $vacature['salary_min'] ?? null;
        $max = $vacature['salary_max'] ?? null;
        $period = $vacature['salary_period'] ?? 'per maand';

        if ($min && $max) {
            return sprintf('€ %s - € %s %s', number_format((float) $min, 0, ',', '.'), number_format((float) $max, 0, ',', '.'), $period);
        }
        if ($min) {
            return sprintf('Vanaf € %s %s', number_format((float) $min, 0, ',', '.'), $period);
        }

        return null;
    }

    /**
     * Zoekt de gemeente-slug bij een vrije Strapi-locatietekst. Onherkende
     * of vage locaties ("Regio Woerden") vallen terug op de dichtstbijzijnde
     * bekende gemeente (Woerden), zodat er nooit content verdwijnt.
     */
    private function resolveMunicipalitySlug(?string $city, array $municipalitySlugs): ?string
    {
        if (! filled($city)) {
            return null;
        }

        $slug = Str::slug($city);
        if (in_array($slug, $municipalitySlugs, true)) {
            return $slug;
        }

        // Probeer een gemeente te herkennen binnen een vrije tekst zoals "Regio Woerden".
        foreach ($municipalitySlugs as $known) {
            if (Str::contains($slug, $known)) {
                return $known;
            }
        }

        return 'woerden';
    }

    private function resolveImageUrl(mixed $image): ?string
    {
        if (! is_array($image)) {
            return null;
        }

        $url = $image['url'] ?? Arr::get($image, 'formats.medium.url') ?? Arr::get($image, 'formats.small.url');
        if (! $url) {
            return null;
        }

        return Str::startsWith($url, ['http://', 'https://']) ? $url : config('pito.strapi.url').$url;
    }

    private function htmlToParagraphs(?string $html): array
    {
        if (! filled($html)) {
            return [];
        }

        $parts = preg_split('/<\/p>|<br\s*\/?>/i', $html) ?: [];

        return collect($parts)
            ->map(fn (string $part) => trim(strip_tags($part)))
            ->filter()
            ->values()
            ->all();
    }

    private function htmlToListItems(?string $html): array
    {
        if (! filled($html)) {
            return [];
        }

        if (preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $html, $matches)) {
            return collect($matches[1])
                ->map(fn (string $item) => trim(strip_tags($item)))
                ->filter()
                ->values()
                ->all();
        }

        return $this->htmlToParagraphs($html);
    }

    private function jobTagsFromFixture(array $jobs): array
    {
        return collect($jobs)->flatMap(fn (array $job) => $job['tags'] ?? [])->unique()->sort()->values()->all();
    }

    private function fixtureContent(): array
    {
        $path = resource_path('data/content.json');
        $data = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);

        return $data;
    }

    private function hasUsableStrapiConfiguration(): bool
    {
        return filled(config('pito.strapi.url')) && filled(config('pito.strapi.deals_endpoint'));
    }
}
