<script setup>
import { computed, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { CaretDown, MapPin } from '../icons';
import OfferCard from '../components/OfferCard.vue';
import OfferFilters from '../components/OfferFilters.vue';
import BusinessBanner from '../components/BusinessBanner.vue';
import { currentMunicipality, pito } from '../stores/content';

const props = defineProps({ initialScope: { type: String, default: 'all' } });
const route = useRoute();
const scope = ref(props.initialScope);
const category = ref(route.params.category || 'all');

watch(() => route.params.category, (value) => { category.value = value || 'all'; });
watch(() => props.initialScope, (value) => { scope.value = value || 'all'; });

const items = computed(() => {
  const local = pito.offers.filter((item) => item.municipalities?.includes(currentMunicipality.value.slug));
  const everywhere = pito.offers.filter((item) => ['national', 'fixed_costs'].includes(item.scope_type));
  const collectives = pito.collectives.map((item) => ({ ...item, scope_type: 'collective', category: 'collectieven' }));
  return [...local, ...everywhere, ...collectives];
});
const filtered = computed(() => items.value.filter((item) => (scope.value === 'all' || item.scope_type === scope.value) && (category.value === 'all' || item.category === category.value)));
</script>

<template>
  <section class="page-hero page-hero--compact">
    <div class="shell page-hero-inner">
      <div><p class="eyebrow eyebrow--light">PITO aanbod</p><h1>Voordeel dat bij jouw omgeving past.</h1><p>Lokaal waar het kan. Landelijk aangevuld met vaste lasten en collectieven.</p><button class="button button--light hero-municipality-button" type="button" @click="pito.municipalityModalOpen = true"><MapPin :size="18" /> {{ currentMunicipality.name }} <CaretDown :size="16" /></button></div>
      <div class="page-hero-visual page-hero-visual--pito"><img class="page-hero-background" src="/assets/pito-website-v18/deals-bakker-app-korting.png" alt="PITO-aanbod bij een lokale ondernemer" /><div class="page-hero-pito-stage"><img src="/assets/pito-mascotte/03_pito_deal_verrast.png" alt="" /></div></div>
    </div>
  </section>
  <section class="section offer-section">
    <div class="shell">
      <header class="results-head"><div><p class="eyebrow">{{ currentMunicipality.name }}</p><h2>{{ filtered.length }} mogelijkheden gevonden</h2><p>Open een kaart voor alle informatie en de juiste vervolgstap.</p></div></header>
      <OfferFilters v-model:scope="scope" v-model:category="category" :items="items" :local-available="currentMunicipality.is_live" />
      <div v-if="scope === 'local' && !currentMunicipality.is_live" class="local-availability-state"><span class="local-availability-symbol"><MapPin :size="27" /></span><div><p class="eyebrow">Nog niet live</p><h3>Lokaal aanbod voor {{ currentMunicipality.name }} is in voorbereiding.</h3><p>{{ pito.settings.inactive_message }}</p><button class="button button--orange" type="button" @click="pito.signupModalOpen = true">Geef mij een seintje</button></div></div>
      <div v-else-if="filtered.length" class="discovery-grid discovery-grid--results"><OfferCard v-for="item in filtered" :key="`${item.scope_type}-${item.slug}`" :item="item" /></div>
      <div v-else class="empty-state"><h3>Geen resultaat binnen deze keuze.</h3><p>Zet de filters terug en ontdek wat nu wel beschikbaar is.</p><button class="button" type="button" @click="scope = 'all'; category = 'all'">Toon alles</button></div>
    </div>
  </section>
  <BusinessBanner />
</template>
