# Strapi naar Laravel/Vue

Laravel is de proces- en beveiligingslaag. Strapi is de bron voor publiceerbare content. Vue ontvangt één contract via `/api/content`.

## Collecties

### `offers`

Verplicht: `slug`, `title`, `partner`, `scope_type`, `category`, `image`, `intro`, `type`, `availability_mode`, `cta_label`.

Optioneel: `municipalities[]`, `description[]`, `external_url`, `published_at`, `ends_at`, `opening_hours`, SEO-velden.

- `scope_type`: `local`, `fixed_costs` of `national`
- `type`: `external` of `form`

### `collectives`

Verplicht: `slug`, `title`, `short_title`, `status`, `image`, `intro`, `hero_title`, `hero_intro`, `cta_label`, `focus[]`, `service[]`, `faqs[][]`.

De publieke payload bevat vóór aanmelding geen partnernaam of partnerlogo. Leadroutering blijft server-side.

### `jobs`

Verplicht: `slug`, `title`, `employer`, `type`, `hours`, `image`, `intro`, `location`, `external_url`, `cta_label`.

Optioneel: `municipalities[]`, `workplace`, `experience_level`, `compensation_label`, `description[]`, `requirements[]`, `benefits[]`, structured-data velden.

`type`: `fulltime`, `parttime`, `bijbaan`, `stage` of `vrijwilligerswerk`.

### `categories`

Gebruik dezelfde slugs als de app: `tickets-uitjes`, `lokaal-aanbod`, `lokale-diensten`, `voor-ondernemers`, `nieuw-in-de-buurt`.

Vaste lasten en collectieven zijn een contenttype/filter, geen extra appcategorie.

## Gemeenten

`resources/data/municipalities.json` bevat alle gemeenten. `config/pito.php` markeert live gemeenten in de fallback. In productie kan Strapi per gemeente `rich`, `live` of `inactive` leveren.

De keuze staat in `localStorage` als `pito_municipality` en blijft behouden tussen aanbod, vacatures en collectieven.

## Endpoint

```json
{
  "data": {
    "settings": {},
    "categories": [],
    "offers": [],
    "collectives": [],
    "jobs": []
  }
}
```

Laravel cachet standaard vijf minuten. Bij fouten wordt de lokale fallback gebruikt.

## Formulieren

Leads, updates en contactberichten gaan naar Laravel, niet naar Strapi. Koppel vanuit Laravel eventueel jobs of webhooks aan CRM, e-mailprovider of partnerportaal. Daardoor blijven toestemming, retries, logging en partnerroutering controleerbaar.
