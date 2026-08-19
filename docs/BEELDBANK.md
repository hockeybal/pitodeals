# PITO Deals beeldbank

Alle bestanden staan onder `public/assets`. Kernbeelden worden niet extern geladen.

| Map / bestand | Plaatsing |
| --- | --- |
| `pito-logo-officieel.svg` | Header, footer, favicon en loading state |
| `pito-mascotte/PITO_HOMEPAGE_HERO_CREATIVE_V8.png` | Homepagehero |
| `pito-mascotte/01_...` t/m `06_...` | App-, deals-, collectief-, hulp- en vacatureaccenten |
| `pito-website-v18/deals-*.png` | Homepage, aanbod, missie en zakelijk |
| `pito-website-v18/collectief-*.png` | Collectievenoverzicht en details |
| `pito-website-v18/vacature-*.png` | Vacaturehero en kaarten |
| `app-screens-official/feed.png` | Linker app-scherm: feed |
| `app-screens-official/agenda.png` | Middelste app-scherm: agenda |
| `app-screens-official/kaart.png` | Rechter app-scherm: kaart |
| `vacancies/*.jpg` | Vacaturekaarten en details |
| `deals/*.png` | Specifieke lokale deals |
| `neighborhood-hero.png`, `local-market.png`, `business-partners.png` | Algemene fallbacks |

## Formaatregels

- Aanbod- en vacaturekaarten: `3:2`, onderwerp gecentreerd en veilige ruimte rond gezichten.
- Hero: minimaal 1600 px breed; hoofdonderwerp binnen het middelste 70%-gebied.
- Collectieven: geen zichtbaar partnerlogo vóór de aanvraag.
- App-screens: bovenkant niet bijsnijden; officiële screenshots volledig in telefoonframes.
- Logo: altijd het SVG-bestand gebruiken.

## Vervangen

1. Plaats het geoptimaliseerde bestand in de juiste map.
2. Gebruik een bestandsnaam zonder spaties.
3. Pas het pad aan in Strapi of `resources/data/content.json`.
4. Controleer desktop en mobiel.
5. Gebruik alleen beeld waarvoor PITO gebruiksrechten heeft.

In productie kan Strapi Media Library of een CDN absolute publieke URL's leveren zonder wijziging aan de Vue-componenten.
