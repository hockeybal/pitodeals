<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\PitoContentService;

class PageController extends Controller
{
    public function __invoke(Request $request, PitoContentService $content): View
    {
        $path = trim($request->path(), '/');
        $data = $content->all()['data'];
        $municipality = null;
        $municipalitySlug = null;

        if (preg_match('#^gemeente/([^/]+)#', $path, $match)) {
            $municipalitySlug = $match[1];
            $municipality = collect($data['municipalities'] ?? [])->firstWhere('slug', $municipalitySlug)['name'] ?? str($municipalitySlug)->replace('-', ' ')->title()->toString();
        }

        $localContentCount = $municipalitySlug === null ? 0 : collect($data['offers'] ?? [])->filter(
            fn (array $item): bool => in_array($municipalitySlug, $item['municipalities'] ?? [], true),
        )->count() + collect($data['jobs'] ?? [])->filter(
            fn (array $item): bool => in_array($municipalitySlug, $item['municipalities'] ?? [], true),
        )->count();

        $robots = $municipalitySlug !== null && $localContentCount === 0 ? 'noindex,follow' : 'index,follow';

        $meta = match (true) {
            $municipality !== null => [
                'title' => "Lokale deals en vacatures in {$municipality} | PITO",
                'description' => "Ontdek lokaal aanbod, vacatures, collectieven en voordeel op vaste lasten in {$municipality}. Helder bij elkaar via PITO.",
            ],
            str_starts_with($path, 'vacatures') => [
                'title' => 'Vacatures bij jou in de buurt | PITO',
                'description' => 'Vind fulltime- en parttimebanen, bijbanen, stages en vrijwilligerswerk bij jou in de buurt.',
            ],
            str_starts_with($path, 'collectieven') => [
                'title' => 'PITO Collectieven | Persoonlijk advies voor je woning',
                'description' => 'Kom via PITO in contact met zorgvuldig geselecteerde partners voor persoonlijk en vrijblijvend advies.',
            ],
            str_starts_with($path, 'voor-bedrijven') || str_starts_with($path, 'voor-ondernemers') => [
                'title' => 'Bereik lokaal klanten en personeel | PITO voor bedrijven',
                'description' => 'Plaats in ongeveer één minuut een vacature of lokaal aanbod en bereik mensen in de gemeenten die voor jou tellen.',
            ],
            default => [
                'title' => 'PITO — Ontdek je voordeel',
                'description' => 'Ontdek lokaal aanbod, vacatures, vaste lasten en collectieven in jouw gemeente.',
            ],
        };

        $meta['robots'] = $robots;
        $meta['h1'] = $municipality !== null ? "Ontdek je voordeel in {$municipality}" : $meta['title'];

        return view('app', compact('meta'));
    }
}
