<script setup>
import { computed } from 'vue';
const props = defineProps({ document: String, title: String });
const meta = computed(() => ({
  privacy: ['Jouw gegevens', 'Privacy, zonder omwegen.', 'Welke gegevens PITO gebruikt, waarom dat nodig is en wanneer een aanvraag wordt gedeeld.'],
  voorwaarden: ['Duidelijke afspraken', 'Gebruiksvoorwaarden.', 'Wie waarvoor verantwoordelijk is bij aanbod, vacatures en externe aanbieders.'],
  'voorwaarden-collectieven': ['PITO Collectieven', 'Vrijblijvend aanvragen. Zelf beslissen.', 'De rol van PITO, de geselecteerde partner en jou als aanvrager.'],
  disclaimer: ['Wie doet wat?', 'PITO verbindt. De partner voert uit.', 'De korte rolverdeling voor aanbod, collectieven en vacatures.'],
  cookies: ['Cookiebeleid', 'Alleen wat nodig is.', 'Welke lokale opslag en cookies deze website gebruikt.'],
})[props.document] || ['Juridisch', props.title, 'Duidelijke informatie over het gebruik van PITO.']);
const links = [['privacy','Privacy'],['voorwaarden','Gebruik'],['voorwaarden-collectieven','Collectieven'],['disclaimer','Rolverdeling'],['cookies','Cookies']];
</script>

<template>
  <section class="legal-hero"><div class="shell"><p class="eyebrow eyebrow--light">{{ meta[0] }}</p><h1>{{ meta[1] }}</h1><p>{{ meta[2] }}</p><span>Laatst bijgewerkt: 17 augustus 2026</span></div></section>
  <div class="shell legal-layout"><aside><nav class="legal-nav" aria-label="Juridische informatie"><RouterLink v-for="item in links" :key="item[0]" :class="{ 'is-active': document === item[0] }" :to="`/${item[0]}`">{{ item[1] }}</RouterLink></nav><div class="legal-aside-note"><strong>Vraag over je gegevens?</strong><p>Neem contact op via info@pito.app.</p></div></aside><article class="legal-page">
    <template v-if="document === 'privacy'">
      <section><h2>1. Wie is verantwoordelijk?</h2><p>Pito App B.V. verwerkt persoonsgegevens via PITO. Deze verklaring geldt voor de website “Ontdek je voordeel”. Voor accounts en buurtfuncties in de app geldt daarnaast het privacybeleid op pito.app.</p></section>
      <section><h2>2. Welke gegevens gebruiken we?</h2><div class="legal-data-grid"><article><strong>Adviesaanvragen</strong><p>Naam, e-mail, telefoonnummer, volledig adres, gekozen onderwerp, toestemming en verzendmoment.</p></article><article><strong>Lokale updates</strong><p>E-mail, gemeente, voorkeur voor aanbod of vacatures en je adres om kansen lokaal en woninggericht te kunnen selecteren.</p></article><article><strong>Contact</strong><p>De gegevens die je invult en informatie die nodig is om je vraag te behandelen.</p></article><article><strong>Techniek</strong><p>Beperkte logs voor werking, beveiliging en misbruikpreventie. Je gemeentekeuze wordt op je apparaat onthouden.</p></article></div></section>
      <section><h2>3. Waarom verwerken we dit?</h2><p>We gebruiken gegevens om een door jou gevraagde dienst uit te voeren, je toestemming uit te voeren, wettelijke verplichtingen na te komen en de website veilig te houden. Updates kun je altijd stopzetten.</p></section>
      <section><h2>4. Delen met een geselecteerde partner</h2><p>Bij een concrete adviesaanvraag geef je PITO toestemming om de noodzakelijke gegevens te delen met maximaal één geselecteerde partner. Die partner mag contact opnemen voor intake, advies en eventueel een offerte. Een adres uit alleen de update-inschrijving blijft bij PITO totdat je apart om advies vraagt.</p></section>
      <section><h2>5. Bewaren en beveiligen</h2><p>Adviesaanvragen en bewijs van overdracht bewaren we maximaal 24 maanden na de laatste inhoudelijke opvolging. Updates bewaren we tot je toestemming intrekt. Contactvragen bewaren we maximaal 24 maanden. Wettelijke administratieve gegevens bewaren we zolang de wet dat verlangt.</p></section>
      <section><h2>6. Jouw rechten</h2><p>Je kunt vragen om inzage, correctie, verwijdering, beperking of overdracht. Je kunt bezwaar maken en toestemming intrekken via info@pito.app. Je kunt ook een klacht indienen bij de Autoriteit Persoonsgegevens.</p></section>
    </template>
    <template v-else-if="document === 'voorwaarden-collectieven'">
      <section class="legal-role-banner"><strong>De korte versie</strong><p>PITO brengt je aanvraag naar maximaal één geselecteerde partner. De partner adviseert, offreert en installeert. Jij beslist.</p></section>
      <section><h2>1. Een aanvraag is vrijblijvend</h2><p>Met “Plan gratis advies” vraag je om persoonlijk contact. Je koopt nog niets en geeft geen installatieopdracht. Er ontstaat pas een overeenkomst wanneer jij zelf een offerte van de partner accepteert.</p></section>
      <section><h2>2. Wat PITO doet</h2><p>PITO registreert je aanvraag, selecteert partners en brengt je in contact. PITO verkoopt of installeert zelf geen warmtepomp, thuisbatterij, isolatie of andere maatregel en geeft geen technisch, financieel of bouwkundig advies.</p></section>
      <section><h2>3. Wat de partner doet</h2><p>De partner is verantwoordelijk voor intake, woningbeoordeling, advies, berekeningen, offerte, prijs, planning, certificering, installatie, oplevering, service en garantie.</p></section>
      <section><h2>4. Collectief voordeel</h2><p>Bundeling kan efficiëntere planning mogelijk maken. PITO garandeert geen minimale korting, besparing, rendement, subsidie, startdatum of geschiktheid voor jouw woning.</p></section>
      <section><h2>5. Vergoeding en eigen keuze</h2><p>PITO kan een vergoeding ontvangen voor een geldige aanvraag of gerealiseerde opdracht. Jij blijft vrij om te vergelijken en niet verder te gaan. Controleer altijd de offerte en voorwaarden van de partner.</p></section>
    </template>
    <template v-else-if="document === 'cookies'">
      <section><h2>1. Functionele opslag</h2><p>De website onthoudt je gemeentekeuze en noodzakelijke sessie-informatie. Dit is nodig om de website goed en veilig te laten werken.</p></section>
      <section><h2>2. Analyse en marketing</h2><p>Niet-noodzakelijke analyse- of marketingcookies worden alleen geplaatst wanneer daarvoor vooraf een geldige keuze is gevraagd. In deze oplevering staan zulke cookies standaard uit.</p></section>
      <section><h2>3. Zelf beheren</h2><p>Je kunt opslag via je browser verwijderen. Daardoor kan je gekozen gemeente opnieuw worden gevraagd.</p></section>
    </template>
    <template v-else-if="document === 'disclaimer'">
      <section class="legal-role-grid"><article><span>01</span><strong>PITO</strong><p>Maakt aanbod vindbaar en brengt partijen in contact.</p></article><article><span>02</span><strong>Aanbieder of partner</strong><p>Levert, adviseert, offreert en voert uit.</p></article><article><span>03</span><strong>Jij</strong><p>Controleert de actuele informatie en beslist zelf.</p></article></section>
      <section><h2>Actuele informatie</h2><p>Prijzen, beschikbaarheid, voorwaarden, vacatures en externe pagina’s kunnen veranderen. Controleer daarom altijd de actuele informatie bij de aanbieder, werkgever of partner.</p></section>
      <section><h2>Geen eigen uitvoering</h2><p>PITO is geen verkoper, werkgever, installateur, aannemer, energiebedrijf of financieel adviseur. Een overeenkomst ontstaat rechtstreeks tussen jou en de betreffende derde.</p></section>
    </template>
    <template v-else>
      <section><h2>1. De rol van PITO</h2><p>PITO beheert het platform, maakt informatie vindbaar en kan vraag en aanbod bij elkaar brengen. PITO is geen verkoper, werkgever, installateur of vertegenwoordiger van de aanbieder.</p></section>
      <section><h2>2. Aanbod en externe links</h2><p>De aanbieder is verantwoordelijk voor prijs, geldigheid, levering, kwaliteit, garantie en klantenservice. De werkgever is verantwoordelijk voor vacature-inhoud, selectie en arbeidsvoorwaarden.</p></section>
      <section><h2>3. Gebruik van de website</h2><p>Gebruik de website rechtmatig en verstrek correcte gegevens. Misbruik, beveiliging omzeilen en geautomatiseerd kopiëren zijn niet toegestaan.</p></section>
      <section><h2>4. Beschikbaarheid en aansprakelijkheid</h2><p>PITO spant zich in voor een veilige en actuele website, maar kan geen ononderbroken werking of foutloze informatie van derden garanderen. Dwingend consumentenrecht blijft altijd gelden.</p></section>
      <section><h2>5. Klachten en contact</h2><p>Een klacht over uitvoering bespreek je eerst met de betrokken aanbieder of partner. Een klacht over PITO kun je melden via <RouterLink to="/contact">contact</RouterLink>.</p></section>
    </template>
    <div class="legal-contact"><h2>Contact</h2><div><p><strong>Pito App B.V.</strong><br />Kweektuinlaan 46<br />3452 SR Vleuten</p><p><strong>KvK</strong><br />93596758</p><p><strong>E-mail</strong><br /><a href="mailto:info@pito.app">info@pito.app</a></p></div></div>
  </article></div>
</template>
