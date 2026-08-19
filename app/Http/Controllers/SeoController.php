<?php

namespace App\Http\Controllers;

use App\Services\PitoContentService;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $body = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /designs',
            'Sitemap: '.url('/sitemap.xml'),
            '',
        ]);

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(PitoContentService $content): Response
    {
        $data = $content->all()['data'];
        $urls = collect(['/', '/deals', '/vacatures', '/collectieven', '/vaste-lasten', '/landelijk', '/over-pito', '/voor-bedrijven']);

        $contentMunicipalities = collect($data['offers'] ?? [])->concat($data['jobs'] ?? [])
            ->flatMap(fn (array $item): array => $item['municipalities'] ?? [])
            ->unique();

        $municipalityUrls = collect($data['municipalities'] ?? [])
            ->filter(fn (array $item): bool => $contentMunicipalities->contains($item['slug']))
            ->map(fn (array $item): string => '/gemeente/'.$item['slug']);

        $offerUrls = collect($data['offers'] ?? [])->map(fn (array $item): string => '/deals/'.$item['slug']);
        $collectiveUrls = collect($data['collectives'] ?? [])->map(fn (array $item): string => '/collectieven/'.$item['slug']);
        $jobUrls = collect($data['jobs'] ?? [])->map(fn (array $item): string => '/vacatures/'.$item['slug']);

        $entries = $urls->concat($municipalityUrls)->concat($offerUrls)->concat($collectiveUrls)->concat($jobUrls)
            ->unique()
            ->map(fn (string $path): string => '  <url><loc>'.htmlspecialchars(url($path), ENT_XML1).'</loc></url>')
            ->implode("\n");

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n{$entries}\n</urlset>\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
