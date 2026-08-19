<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class PitoContentService
{
    public function all(): array
    {
        $content = $this->strapiContent() ?? $this->fixtureContent();
        $content['municipalities'] = $content['municipalities'] ?? $this->municipalities();
        $content['links'] = array_merge(config('pito.external', []), $content['links'] ?? []);

        return [
            'data' => $content,
            'meta' => [
                'source' => config('pito.strapi.enabled') && $this->hasUsableStrapiConfiguration() ? 'strapi-or-fallback' : 'local-fixture',
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

    private function strapiContent(): ?array
    {
        if (! config('pito.strapi.enabled') || ! $this->hasUsableStrapiConfiguration()) {
            return null;
        }

        return Cache::remember('pito.web-content.v1', config('pito.strapi.cache_seconds'), function (): ?array {
            try {
                $request = Http::acceptJson()->timeout(config('pito.strapi.timeout'));
                if ($token = config('pito.strapi.token')) {
                    $request = $request->withToken($token);
                }

                $response = $request->get(config('pito.strapi.url').config('pito.strapi.content_endpoint'));
                $response->throw();
                $payload = $response->json();
                $data = Arr::get($payload, 'data', $payload);

                if (! is_array($data) || ! isset($data['settings'], $data['categories'], $data['offers'], $data['jobs'], $data['collectives'])) {
                    throw new RuntimeException('Strapi response mist één of meer verplichte collecties.');
                }

                return $data;
            } catch (Throwable $exception) {
                Log::warning('Strapi-content kon niet worden geladen; lokale fallback wordt gebruikt.', [
                    'message' => $exception->getMessage(),
                ]);

                return null;
            }
        });
    }

    private function fixtureContent(): array
    {
        $path = resource_path('data/content.json');
        $data = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        return $data;
    }

    private function hasUsableStrapiConfiguration(): bool
    {
        return filled(config('pito.strapi.url')) && filled(config('pito.strapi.content_endpoint'));
    }
}
