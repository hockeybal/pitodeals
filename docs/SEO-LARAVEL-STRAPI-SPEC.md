# PITO Deals — SEO-architectuur voor alle Nederlandse gemeenten

Status: productievoorstel voor de Laravel-website met Strapi als bron van waarheid.

## Aanbevolen beslissing

Maak voor iedere Nederlandse gemeente een bruikbare route, maar indexeer niet automatisch honderden lege of vrijwel identieke pagina’s. Een gemeentepagina wordt pas indexeerbaar zodra er aantoonbare lokale waarde is. Tot die tijd blijft de pagina gewoon bruikbaar voor bezoekers en toont zij landelijke deals, collectieven en vacatures met `noindex,follow`.

Dit levert twee dingen tegelijk op: één consistente gebruikerservaring in heel Nederland en een schaalbare SEO-opbouw zonder dunne stadspagina’s.

## Feiten, beslissingen en werkhypothesen

- **Feit:** Google waarschuwt voor grote aantallen pagina’s die vooral voor zoekmachines zijn gemaakt en weinig eigen waarde bieden.
- **Beslissing:** alle gemeente-URL’s bestaan voor navigatie; alleen pagina’s met lokale inhoud komen in de index en sitemap.
- **Beslissing:** metadata, canonical en structured data worden door Laravel server-side in de eerste HTML gezet. De React-prototypecode toont alleen het gewenste gedrag.
- **Werkhypothese:** Strapi bevat of krijgt één gemeentecollectie met status, lokale introductie en relaties naar deals en vacatures.

## 1. Publieke route en zoekintentie

Route:

`/gemeente/{gemeente-slug}`

Voorbeelden:

- `/gemeente/woerden`
- `/gemeente/ede`
- `/gemeente/amersfoort`

Primaire zoekintentie: lokale deals, lokaal aanbod en vacatures in de gekozen gemeente. Collectieven en vaste lasten vullen de pagina aan, maar worden niet als lokaal voorgesteld wanneer zij landelijk zijn.

## 2. Indexatieregel

Een gemeentepagina krijgt `index,follow` wanneer minimaal één van deze voorwaarden geldt:

1. er staat minimaal één actuele lokale deal of lokaal aanbod live;
2. er staat minimaal één actuele lokale vacature live;
3. de pagina bevat aantoonbaar unieke lokale redactionele inhoud en een concrete lokale PITO-status.

Een gemeente zonder lokale inhoud krijgt:

- `noindex,follow`;
- geen vermelding in de XML-sitemap;
- wel interne links naar landelijke deals, collectieven en vacatures;
- duidelijke tekst: lokaal aanbod is nog niet live, landelijk aanbod wel.

Zodra Strapi lokale inhoud publiceert, zet een webhook de gemeentecache opnieuw op, verandert Laravel de robotsregel naar `index,follow` en verschijnt de canonical URL in de eerstvolgende sitemap.

## 3. Metadata-sjablonen

### Gemeente met lokaal aanbod

**Title**  
`PITO Deals {Gemeente} | Lokale deals, vacatures en voordeel`

**Meta description**  
`Ontdek lokale deals in {Gemeente}, vacatures, collectieven en geselecteerd landelijk voordeel. Alles wat dichtbij relevant is, helder bij elkaar.`

**H1**  
`Deals en voordeel in {Gemeente}.`

### Gemeente zonder lokaal aanbod

**Title**  
`PITO Deals {Gemeente} | Landelijk voordeel`

**Meta description**  
`Bekijk landelijk beschikbare deals, collectieven en vacatures voor {Gemeente}. Lokaal aanbod verschijnt zodra PITO hier actief wordt.`

**Robots**  
`noindex,follow`

### Detailpagina deal

**Title**  
`{Dealnaam} in {Gemeente of Nederland} | PITO Deals`

**Description**  
Gebruik de actuele `short_description` uit Strapi; maak geen automatisch gegenereerde claims over korting, prijs of beschikbaarheid.

### Detailpagina vacature

**Title**  
`{Functietitel} bij {Werkgever} in {Plaats} | PITO Vacatures`

**Description**  
Gebruik de actuele korte functieomschrijving uit Strapi.

## 4. Canonicals, sitemap en robots

- Elke indexeerbare pagina heeft één absolute self-referencing canonical.
- Queryparameters voor filters, gemeentevoorkeur en sortering canoniseren naar de ongefilterde landingspagina.
- De sitemap bevat alleen indexeerbare canonical URL’s met een eerlijke `lastmod` uit Strapi.
- Verlopen deals en vacatures verdwijnen uit de actieve sitemap. De detail-URL blijft tijdelijk bestaan met alternatieven en kan daarna met een passende `301` of `410` worden afgehandeld.
- `robots.txt` blokkeert geen gemeentepagina’s: Google moet een `noindex`-tag kunnen lezen.
- `/designs` en andere prototype- of testpagina’s blijven buiten de index.

Aanbevolen Laravel-routes:

- `GET /robots.txt`
- `GET /sitemap.xml`
- optioneel opgesplitst: `/sitemaps/gemeenten.xml`, `/sitemaps/deals.xml`, `/sitemaps/vacatures.xml`

## 5. Structured data

Gebruik alleen gegevens die zichtbaar en actueel op dezelfde pagina staan.

- Gemeente-overzicht: `CollectionPage` met `about: City`.
- Eén vacaturedetail: `JobPosting`, inclusief `datePosted`, `validThrough`, `employmentType`, werkgever en locatie.
- Eén concrete dealdetail: alleen `Product` met geneste `Offer` wanneer prijs, valuta en beschikbaarheid werkelijk bekend zijn. Geen verzonnen prijs of rating.
- Lijstpagina’s krijgen geen `JobPosting` per kaart en geen productmarkup voor een hele categorie.
- Test iedere template in Google Rich Results Test en Schema Markup Validator.

## 6. Strapi-velden voor gemeenten

Aanbevolen velden op `municipality`:

- `name`, `slug`, `province`;
- `pito_status`: `inactive`, `growing`, `live`;
- `local_intro` en optioneel `local_highlight`;
- relaties naar deals, vacatures en lokale organisaties;
- `seo_title_override`, `seo_description_override` alleen voor redactionele uitzonderingen;
- `indexable_override`: standaard automatisch, handmatig alleen met redactionele reden;
- `updated_at` voor sitemap `lastmod`.

De frontend maakt geen tweede lijst met Nederlandse gemeenten. Laravel en de app lezen dezelfde gemeente-identificatie en contentrelaties uit de bestaande bron.

## 7. Interne linking

- De gemeentezoeker zet actieve gemeenten bovenaan met label `Live`.
- Deal- en vacaturedetailpagina’s linken terug naar hun gemeente en categorie.
- Gemeentepagina’s linken naar relevante lokale categorieën, collectieven en vacatures.
- Inactieve gemeentepagina’s linken naar landelijk aanbod, maar doen niet alsof dat lokaal is.
- Footerlinks blijven beperkt tot hoofdroutes; honderden gemeentelinks horen in een HTML-gemeenteoverzicht of sitemap, niet in elke footer.

## 8. Personalisatie en SEO blijven gescheiden

Een gekozen gemeente of volledig adres mag de canonical inhoud niet onvoorspelbaar wijzigen. Laravel rendert de openbare gemeentecontext op basis van de URL. Persoonlijke updates en woningaanbod worden pas na toestemming in het account/e-mailprofiel toegepast en komen nooit in URL’s, analytics-events of structured data terecht.

## 9. Acceptatiecriteria

- Een live gemeente toont in de eerste HTML een unieke title, description, H1, canonical en `index,follow`.
- Een lege gemeente toont in de eerste HTML `noindex,follow` en staat niet in de sitemap.
- Filters veroorzaken geen indexeerbare duplicaten.
- Een gepauzeerde of verlopen Strapi-entry is binnen de afgesproken cachetijd niet meer als actueel aanbod zichtbaar.
- Deal- en vacaturestructured data bevat uitsluitend feitelijke velden uit Strapi.
- Volledig adres en e-mail komen nooit in analytics, URL’s, logs met querystrings of JSON-LD terecht.

