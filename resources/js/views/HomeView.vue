<script setup>
import { computed, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { ArrowRight, Briefcase, CaretDown, MapPin, Storefront } from '../icons';
import AppBlock from '../components/AppBlock.vue';
import BusinessBanner from '../components/BusinessBanner.vue';
import OfferCard from '../components/OfferCard.vue';
import OfferFilters from '../components/OfferFilters.vue';
import VacancyCard from '../components/VacancyCard.vue';
import { currentMunicipality, pito, setMunicipality, visibleJobs } from '../stores/content';

const route = useRoute();
const tab = ref('offers');
const scope = ref('all');
const category = ref('all');
const vacancyType = ref('alles');

watch(() => route.params.municipality, (slug) => { if (slug) setMunicipality(slug); }, { immediate: true });

const collectionItems = computed(() => pito.collectives.map((item) => ({ ...item, scope_type: 'collective', category: 'collectieven', cta_label: item.cta_label })));
const offersForArea = computed(() => {
  const local = pito.offers.filter((item) => item.municipalities?.includes(currentMunicipality.value.slug));
  const everywhere = pito.offers.filter((item) => ['national', 'fixed_costs'].includes(item.scope_type));
  return [...local, ...everywhere, ...collectionItems.value];
});
const filteredOffers = computed(() => offersForArea.value.filter((item) => {
  if (scope.value !== 'all' && item.scope_type !== scope.value) return false;
  if (category.value !== 'all' && item.category !== category.value) return false;
  return true;
}));
const filteredJobs = computed(() => visibleJobs.value.filter((job) => vacancyType.value === 'alles' || job.type === vacancyType.value));
const vacancyTypes = [['alles', 'Alles'], ['fulltime', 'Full-time'], ['parttime', 'Part-time'], ['bijbaan', 'Bijbaan'], ['stage', 'Stage'], ['vrijwilligerswerk', 'Vrijwilligerswerk']];
</script>

<template>
  <section class="home-hero">
    <div class="hero-copy"><div class="hero-copy-inner">
      <p class="eyebrow eyebrow--light">PITO · Ontdek je voordeel</p>
      <h1>Ontdek je voordeel <span class="home-hero-place">in {{ currentMunicipality.name }}</span></h1>
      <p>Lokale kansen, vacatures, vaste lasten en collectieven. Eén overzicht, afgestemd op jouw gemeente.</p>
      <div class="hero-actions">
        <button class="button button--light hero-municipality-button" type="button" @click="pito.municipalityModalOpen = true"><span>{{ currentMunicipality.name }}</span><CaretDown :size="18" /></button>
        <a class="button button--orange" href="#aanbod" @click="tab = 'offers'">Bekijk aanbod</a>
        <a class="button button--hero-secondary" href="#aanbod" @click="tab = 'jobs'">Bekijk vacatures <ArrowRight :size="18" /></a>
      </div>
    </div></div>
    <div class="home-hero-media"><img src="/assets/pito-mascotte/PITO_HOMEPAGE_HERO_CREATIVE_V8.png" alt="PITO helpt een buurtbewoner voordeel in haar gemeente te ontdekken" /></div>
  </section>

  <section id="aanbod" class="now-in-area">
    <div class="shell">
      <div class="now-in-tabs" aria-label="Kies aanbod of vacatures">
        <button type="button" :class="{ 'is-active': tab === 'offers' }" @click="tab = 'offers'"><Storefront :size="20" /><strong>Aanbod</strong><span>{{ offersForArea.length }}</span></button>
        <button type="button" :class="{ 'is-active': tab === 'jobs' }" @click="tab = 'jobs'"><Briefcase :size="20" /><strong>Vacatures</strong><span>{{ visibleJobs.length }}</span></button>
      </div>

      <template v-if="tab === 'offers'">
        <header class="now-in-heading">
          <div><p class="eyebrow">Voordeel voor {{ currentMunicipality.name }}</p><h2>Eerst dichtbij. Daarna slim aangevuld.</h2><p>We tonen lokaal aanbod waar dat er is, gevolgd door vaste lasten, collectieven en landelijke voordelen die overal beschikbaar zijn.</p></div>
        </header>
        <OfferFilters v-model:scope="scope" v-model:category="category" :items="offersForArea" :local-available="currentMunicipality.is_live" />
        <div v-if="scope === 'local' && !currentMunicipality.is_live" class="local-availability-state">
          <span class="local-availability-symbol"><MapPin :size="27" /></span><div><p class="eyebrow">Nog niet live in {{ currentMunicipality.name }}</p><h3>We zijn er nog niet lokaal. Je voordeel wel.</h3><p>{{ pito.settings.inactive_message }}</p><div class="local-availability-actions"><button type="button" @click="scope = 'all'">Bekijk wat nu al kan <ArrowRight :size="16" /></button><button type="button" @click="pito.signupModalOpen = true">Laat weten wanneer PITO live gaat</button></div></div>
        </div>
        <div v-else-if="filteredOffers.length" class="discovery-grid discovery-grid--results">
          <OfferCard v-for="item in filteredOffers.slice(0, 6)" :key="`${item.scope_type}-${item.slug}`" :item="item" compact />
        </div>
        <div v-else class="empty-state"><h3>Hier staat nu nog niets.</h3><p>Kies een andere categorie of bekijk al het aanbod.</p><button class="button" type="button" @click="scope = 'all'; category = 'all'">Toon alles</button></div>
        <div class="now-in-actions"><RouterLink class="now-in-more" to="/deals">Bekijk al het aanbod <ArrowRight :size="17" /></RouterLink></div>
      </template>

      <template v-else>
        <header class="now-in-heading"><div><p class="eyebrow">Werk dichtbij</p><h2>Een volgende stap hoeft niet ver weg te zijn.</h2><p>Bekijk banen, bijbanen, stages en vrijwilligerswerk in jouw omgeving en landelijk.</p></div></header>
        <div class="home-vacancy-filter">
          <button v-for="type in vacancyTypes" :key="type[0]" type="button" :class="{ 'is-active': vacancyType === type[0] }" @click="vacancyType = type[0]"><span class="home-vacancy-icon"><Briefcase :size="18" /></span>{{ type[1] }}<small>{{ type[0] === 'alles' ? visibleJobs.length : visibleJobs.filter(job => job.type === type[0]).length }}</small></button>
        </div>
        <div class="vacancy-grid home-vacancy-grid"><VacancyCard v-for="job in filteredJobs.slice(0, 6)" :key="job.slug" :job="job" /></div>
        <div class="now-in-actions"><RouterLink class="now-in-more now-in-more--vacancies" to="/vacatures">Bekijk alle vacatures <ArrowRight :size="17" /></RouterLink></div>
      </template>
    </div>
  </section>

  <section class="pito-routes">
    <div class="shell pito-route-stage">
      <header class="pito-route-heading"><div><p class="eyebrow">Hier pak je direct voordeel</p><h2>Drie routes. Eén helder vertrekpunt.</h2></div><p>Kies wat bij je past. PITO brengt je zonder omwegen naar het relevante aanbod.</p></header>
      <div class="pito-route-grid">
        <RouterLink class="pito-route pito-route--fixed" to="/vaste-lasten"><div class="pito-route-visual"><img src="/assets/pito-website-v18/pito-deals-drie-rollen.png" alt="PITO helpt bij vaste lasten" /><span class="pito-route-badge">VASTE LASTEN</span></div><div class="pito-route-copy"><h3>Betaal niet meer dan nodig</h3><p>Bekijk geselecteerd aanbod van vaste partners voor energie, internet en meer.</p><strong>Bekijk vaste lasten <ArrowRight :size="18" /></strong></div></RouterLink>
        <RouterLink class="pito-route pito-route--collective" to="/collectieven"><div class="pito-route-visual"><img src="/assets/pito-website-v18/collectief-buurtbijeenkomst.png" alt="Buurtbewoners en PITO bij een collectief" /><span class="pito-route-badge">COLLECTIEVEN</span></div><div class="pito-route-copy"><h3>Samen sta je sterker</h3><p>Persoonlijk advies via een zorgvuldig geselecteerde partner.</p><strong>Ontdek mijn voordeel <ArrowRight :size="18" /></strong></div></RouterLink>
        <RouterLink class="pito-route pito-route--national" to="/landelijk"><div class="pito-route-visual"><img src="/assets/pito-website-v18/deals-markt-korting.png" alt="Landelijk voordeel via PITO" /><span class="pito-route-badge">LANDELIJKE DEALS</span></div><div class="pito-route-copy"><h3>Voordeel, waar je ook woont</h3><p>Geselecteerde deals die in heel Nederland beschikbaar zijn.</p><strong>Bekijk landelijke deals <ArrowRight :size="18" /></strong></div></RouterLink>
      </div>
    </div>
  </section>

  <section class="shell split-banner split-banner--mission"><div><p class="eyebrow eyebrow--light">Waarom PITO bestaat</p><h2>Een sterke buurt begint als dichtbij weer meetelt.</h2><p>We maken lokaal aanbod zichtbaar, brengen bewoners en organisaties dichter bij elkaar en laten een deel van de opbrengst terugstromen naar lokale initiatieven.</p><RouterLink class="button button--light" to="/over-pito">Lees ons verhaal <ArrowRight :size="18" /></RouterLink></div><div class="mission-market-visual"><img src="/assets/pito-website-v18/deals-markt-korting.png" alt="Bewoners ontmoeten ondernemers op de lokale markt" /><span>LOKAAL BESTEDEN. LOKAAL VERSTERKEN.</span></div></section>
  <AppBlock />
  <BusinessBanner />
</template>
