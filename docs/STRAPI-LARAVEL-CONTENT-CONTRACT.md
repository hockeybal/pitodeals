# PITO Deals en Vacatures — Strapi/Laravel contentcontract

Status: implementatievoorstel op basis van de huidige PITO-app en de webprototypeflow. De inhoud is leidend; veldnamen mogen door de developer op de bestaande Strapi-conventies worden aangesloten.

## Aanbevolen beslissing

Gebruik voor app en website exact dezelfde Strapi-records. Laravel haalt deze records op, bouwt indexeerbare detailpagina’s en stuurt uitgaande klikken via een meetbare redirect naar de ondernemer of werkgever. Collectieven blijven een apart contenttype en gebruiken hun eigen adviesfunnel.

Zo voorkomen we dubbel beheer, verschillen tussen app en web en anonieme pagina’s zonder voldoende informatie.

## 1. Deal

Verplichte velden voor publicatie:

- `title`, `slug`, `short_description` en `description` (rich text/blocks);
- `partner` of `business` relation;
- `cover_image` en optioneel `gallery`;
- `category` relation;
- `municipalities` relation en `is_national` boolean;
- `benefit_type`: `percentage`, `fixed_amount`, `fixed_price`, `free`, `text`;
- `benefit_label`, bijvoorbeeld `20% korting`, `€7,50 voordeel` of `Gratis fietscheck`;
- `availability_mode`: `online`, `online_and_store` of `store`, zodat app en website dezelfde gebruiksvorm tonen;
- `original_price`, `deal_price` en `currency` wanneer een prijs van toepassing is;
- `starts_at`, `ends_at` en optioneel gestructureerde `opening_hours`;
- `destination_url` en `cta_label`;
- `status`: `draft`, `scheduled`, `live`, `expired`, `paused`;
- `published_at`, `updated_at` en `last_verified_at`;
- `show_in_app` en `show_on_web` voor een gecontroleerde overgang.

Publicatieregel: een externe deal mag niet live zonder titel, samenvatting, volledige beschrijving, beeld, voordeel, bereik, actieperiode en bestemming. Is de exacte prijs niet relevant, dan blijft `benefit_label` verplicht.

De webkaart toont minimaal beeld, voordeel, titel, aanbieder, bereik, publicatiestatus en gebruiksvorm. De detailpagina toont daarnaast de volledige beschrijving, prijsinformatie, actieperiode, openingstijden, voorwaarden/werkwijze en één externe CTA. Kleur wordt semantisch bepaald door `benefit_type` en `availability_mode`; redacteuren kiezen dus geen willekeurige labelkleur in Strapi.

De website maakt geen eigen categorie- of filtermodel naast Strapi. De horizontale dealnavigatie wordt opgebouwd uit dezelfde categorie-records en volgorde die de app gebruikt. De vaste ingang **Vaste lasten** wordt in productie gevoed door een bestaande Strapi-tag, groep of expliciet veld op het dealrecord; de huidige prototype-selectie op slugs is alleen demodata en mag niet in Laravel worden overgenomen.

Standaardvolgorde voor `/deals`:

1. live deals met de gekozen gemeente-relatie;
2. live collectieven die voor deze bezoeker beschikbaar zijn;
3. live deals met `is_national = true`.

De resultaatteller wordt uit deze drie zichtbare sets berekend. Een categorieroute past eerst de Strapi-categorie toe en behoudt daarna dezelfde gemeente-plus-landelijk logica.

De snelle weergavefilters **Alles**, **Lokaal aanbod**, **Vaste lasten**, **Collectieven** en **Landelijke deals** zijn geen nieuwe contenttaxonomie. Laravel leidt dit presentatietype af uit het bronrecord (`deal` plus gemeente/nationaal, `collective` of de bestaande vaste-lasten-groep) en geeft het als queryparameter door. De categorieën binnen deals blijven exact de categorie-records en volgorde uit Strapi en de app. Voeg pas een vrij sorteer- of bereikfilter toe wanneer aantoonbaar veel relevante resultaten niet meer scanbaar zijn; bouw dat dan op API-queryparameters, niet op een tweede frontendtaxonomie.

## 2. Vacature

Verplichte velden voor publicatie:

- `title`, `slug`, `short_description` en `description`;
- `employer` relation en `cover_image`;
- `employment_type`: `fulltime`, `parttime`, `side_job`, `internship`, `volunteer`;
- `hours_label`, `workplace_type`, `experience_level` en `compensation_label`;
- `requirements` en `benefits` als herhaalbare blokken;
- `location_label`, optioneel adres/coördinaten en gemeenten-relatie;
- `external_apply_url` en optioneel `apply_email`;
- `date_posted`, `valid_through` en `status`;
- `show_in_app` en `show_on_web`.

Huidige route: PITO presenteert en verwijst door. De werkgever ontvangt en verwerkt de sollicitatie. PITO kan daarom nu wel `apply_click` meten, maar niet eerlijk melden dat er een nieuwe sollicitatie is ontvangen.

Toekomstoptie: voeg `application_mode` toe met `external`, `email` en `pito_form`. Alleen bij `pito_form` kan PITO een echte aanvraag opslaan, bevestigen en als lead aan de werkgever melden. Dat vraagt aparte toestemming, bewaartermijnen, beveiliging en verwerkersafspraken.

## 3. Collectief

Collectieven vallen niet onder de generieke dealdetailpagina. Ze behouden:

- eigen landingspagina en inhoudsblokken;
- eigen adviesformulier en toestemmingsregel;
- maximaal één geselecteerde partner per aanvraag;
- bedankscherm waarop pas de betreffende partner wordt genoemd;
- eigen juridische voorwaarden en leadstatussen.

## 4. Laravel-routes

Aanbevolen publieke routes:

- `/deals/{slug}` — detailpagina deal;
- `/vacatures/{slug}` — detailpagina vacature;
- `/collectieven/{slug}` — eigen collectiefpagina;
- `/uit/deal/{slug}` — registreert klik en geeft daarna een `302` naar `destination_url`;
- `/uit/vacature/{slug}` — registreert klik en geeft daarna een `302` naar `external_apply_url`.

Gebruik in de frontend de Laravel-uitroute in plaats van de ruwe externe URL. Valideer bestemmingen server-side en voorkom een open redirect. Voeg waar passend UTM-parameters toe zonder persoonsgegevens mee te sturen.

## 5. Synchronisatie en caching

- Strapi is de bron van waarheid; app en Laravel-website lezen dezelfde entries.
- Gebruik Strapi-webhooks bij publish, unpublish en update om de Laravel-cache per record te verversen.
- Laravel mag korte cache gebruiken, maar een verlopen of gepauzeerde aanbieding moet direct onzichtbaar kunnen worden.
- Maak detailpagina’s server-side indexeerbaar en voeg canonical, Open Graph en `Offer`/`JobPosting` structured data toe op basis van dezelfde velden.
- Als een partnerlink ontbreekt of niet meer werkt, zet het record automatisch op `paused` of stuur het naar redactionele controle.
- De gekozen gemeente is een frontendvoorkeur en geen duplicaat van Strapi-data. Laravel gebruikt de gemeente-slug alleen om de `municipalities`-relatie plus `is_national` te queryen; dezelfde keuze blijft actief tussen deals, collectieven en vacatures.

## 6. Metingen die wel betrouwbaar zijn

Per impressie en klik opslaan: content-id, type, gemeentecontext, categorie, positie, timestamp en anonieme sessie/consentstatus. Geen e-mailadres, volledig adres of andere leadgegevens in analytics.

Kerngebeurtenissen:

- `deal_impression`, `deal_detail_view`, `deal_outbound_click`;
- `vacancy_impression`, `vacancy_detail_view`, `vacancy_apply_click`;
- `collective_form_start`, `collective_form_submit`, `collective_lead_accepted`;
- `update_signup_submit`.

## 6a. Updates en adrespersonalisatie

Behandel de nieuwsbriefinschrijving en persoonlijk woningaanbod als twee afzonderlijke toestemmingen:

- basis: `email`, `municipality`, `wants_deals`, `wants_vacancies`, `consent_at`, `consent_version`;
- vereist voor lokale updates: `postcode`, `house_number`, `street`, `city`; optioneel: `house_number_addition`;
- leg doel, toestemming en moment vast in `personalization_consent_at`;
- intrekken: aparte status en datum voor e-mailupdates en voor adrespersonalisatie;
- bron: `source_page` zonder het adres of e-mailadres in de URL of analytics te zetten.

Het adres blijft bij PITO zolang de bezoeker alleen persoonlijke updates kiest. Deel het pas met maximaal één partner nadat de bezoeker op een concrete adviesaanvraag opnieuw en specifiek toestemming geeft. Laat in het formulier alleen adresvelden zien wanneer de bezoeker bewust kiest voor persoonlijk woningaanbod; zo blijft een gewone deal- of vacature-update laagdrempelig.

Voeg aan Laravel een beveiligd inschrijfpunt toe met server-side validatie, rate limiting, CSRF-bescherming, dubbele opt-in per e-mail en logging van de toestemmingsversie. Sla volledige adressen versleuteld op en stuur ze niet mee naar algemene marketing- of analyseplatformen.

Voor omzetbewijs vraagt PITO bij externe deals en vacatures waar mogelijk een partnercallback, kortingscode of periodieke rapportage. Zonder zo’n terugkoppeling kan PITO alleen doorkliks meten, geen aankoop of sollicitatie.

## 7. Prototype versus productie

De prototypevoorbeelden gebruiken op enkele plekken `example.com` om de externe interactie zichtbaar en testbaar te maken. Vervang die bestemmingen in productie uitsluitend door gevalideerde Strapi-URL’s. De detail- en kaartcomponenten zijn al ingericht op bovenstaande velden.
