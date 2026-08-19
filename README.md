# PITO — Ontdek je voordeel

Zelfstandig platform voor lokaal aanbod, vacatures, vaste lasten en collectieven.

- Backend: Laravel 13 / PHP 8.4
- Frontend: Vue 3 + Vue Router, geladen vanuit Blade
- Database: MySQL 8.4
- Content: Strapi-koppeling met lokale fallback
- Build: Vite 8
- Lokale omgeving: Docker Compose

## Snel starten

Vereist: Docker Desktop of Docker Engine met Compose.

```bash
docker compose up --build
```

Open daarna <http://localhost:8080>. De healthcheck staat op <http://localhost:8080/up>.

De eerste start bouwt de frontend, installeert PHP-packages, start MySQL en voert de migraties uit. Daarna volstaat `docker compose up`.

```bash
docker compose down       # stoppen
docker compose down -v    # stoppen en lokale database wissen
```

Gebruik `APP_PORT=8090 docker compose up --build` wanneer poort 8080 bezet is.

## Configuratie

Docker gebruikt `.env.docker` met alleen lokale ontwikkelwaarden. Gebruik in productie eigen secrets, `APP_ENV=production`, `APP_DEBUG=false`, een nieuwe `APP_KEY`, sterke databasewachtwoorden, echte Strapi-credentials en een mailprovider.

### Strapi activeren

Zonder Strapi werkt de website met `resources/data/content.json`. Voor Strapi:

```dotenv
STRAPI_ENABLED=true
STRAPI_URL=https://cms.example.nl
STRAPI_TOKEN=...
STRAPI_CONTENT_ENDPOINT=/api/pito-web
STRAPI_CACHE_SECONDS=300
```

Het endpoint retourneert onder `data` minimaal `settings`, `categories`, `offers`, `collectives` en `jobs`. De mapping staat in [docs/STRAPI-MAPPING.md](docs/STRAPI-MAPPING.md). Bij een timeout of ongeldige response gebruikt Laravel gecontroleerd de lokale fallback.

## Zonder Docker ontwikkelen

Vereist: PHP 8.3+, Composer 2, Node.js 22, pnpm 10 en MySQL 8.4 of SQLite.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
pnpm install
pnpm dev
php artisan serve
```

Open <http://127.0.0.1:8000>. Vite draait standaard op poort 5173.

## Routes

| Route | Functie |
| --- | --- |
| `/` en `/gemeente/{slug}` | Homepage met gemeente, aanbod/vacature-switch en filters |
| `/deals` en `/deals/{slug}` | Aanbod en detailpagina |
| `/collectieven` en `/collectieven/{slug}` | Collectieven en adviesaanvraag |
| `/vacatures` en `/vacatures/{slug}` | Vacatures en sollicitatieroute |
| `/voor-bedrijven` | Zakelijke propositie en pakketten |
| `/over-pito` | Missie, app en positionering |
| `/privacy`, `/voorwaarden`, `/voorwaarden-collectieven`, `/disclaimer`, `/cookies` | Juridische lijnen |

Laravel levert alle publieke routes via `resources/views/app.blade.php`. Vue Router verzorgt snelle navigatie. Direct openen en verversen van iedere route blijft werken.

## API en formulieren

| Methode | Endpoint | Doel |
| --- | --- | --- |
| GET | `/api/content` | Strapi- of fallbackcontent |
| GET | `/api/municipalities` | Alle Nederlandse gemeenten, live eerst |
| POST | `/api/leads` | Adviesaanvragen |
| POST | `/api/subscriptions` | Updates voor aanbod en vacatures, met volledig adres voor lokale relevantie |
| POST | `/api/contact` | Contactformulier |

Alle POST-routes hebben servervalidatie en rate limiting. Adviesaanvragen slaan toestemming en verzendmoment op. Formulieren gaan niet rechtstreeks naar Strapi; Laravel blijft de veilige proceslaag.

## Structuur

```text
app/Http/Controllers/       pagina- en API-controllers
app/Http/Requests/          formuliervalidatie
app/Models/                 database-entiteiten
app/Services/               Strapi/fallback-service
database/migrations/        databaseschema
docker/                     Nginx en entrypoint
docs/                       CMS-contract, architectuur en beeldbank
public/assets/              websitebeelden, logo en app-screens
resources/css/app.css       PITO-designsysteem en responsive styling
resources/data/             fallbackcontent en gemeenten
resources/js/components/    herbruikbare Vue-componenten
resources/js/views/         alle websiteschermen
resources/views/app.blade.php
```

## Beeldbank

Alle beelden staan onder `public/assets` en gaan mee in de Nginx-container. Zie [docs/BEELDBANK.md](docs/BEELDBANK.md) voor plaatsing, formaat en vervangregels.

## Controle

```bash
php artisan test
pnpm build
docker compose config
```

Controleer voor productie echte partnerlinks, definitieve prijzen/looptijden, mailbezorging, analytics/cookiekeuze en juridisch getoetste publicatieteksten.
