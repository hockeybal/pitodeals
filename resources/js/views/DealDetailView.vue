<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { ArrowLeft, ArrowSquareOut, Check, GlobeHemisphereWest, MapPin, Storefront } from '../icons';
import LeadForm from '../components/LeadForm.vue';
import { offerBySlug } from '../stores/content';

const route = useRoute();
const offer = computed(() => offerBySlug(route.params.slug));
const availability = computed(() => ({ online: 'Alleen online', store: 'In de winkel', online_and_store: 'Online en in de winkel' })[offer.value?.availability_mode] || 'Bekijk bij aanbieder');
</script>

<template>
  <template v-if="offer">
    <section class="detail-hero">
      <div class="detail-media"><img :src="offer.image" :alt="offer.title" /></div>
      <div><RouterLink class="back-link" to="/deals"><ArrowLeft :size="17" /> Terug naar aanbod</RouterLink><div class="detail-status-line"><p class="eyebrow">{{ offer.scope_type === 'local' ? 'Lokaal aanbod' : offer.scope_type === 'fixed_costs' ? 'Vaste lasten' : 'Landelijk voordeel' }}</p><span>{{ availability }}</span></div><h1>{{ offer.title }}</h1><p class="lead">{{ offer.intro }}</p><div class="detail-partner"><span>Aangeboden door</span><strong>{{ offer.partner }}</strong></div><a v-if="offer.type === 'external'" class="button button--orange" :href="offer.external_url" target="_blank" rel="noopener">{{ offer.cta_label }} <ArrowSquareOut :size="18" /></a><a v-else class="button button--orange" href="#advies">Vraag gratis advies aan</a></div>
    </section>
    <section class="shell detail-body">
      <article><h2>Dit kun je verwachten</h2><p v-for="paragraph in offer.description" :key="paragraph">{{ paragraph }}</p><h3>Goed om te weten</h3><ul><li><Check :size="17" /> Controleer de actuele voorwaarden bij de aanbieder.</li><li><Check :size="17" /> Je overeenkomst loopt rechtstreeks via de aanbieder.</li><li><Check :size="17" /> PITO helpt je vinden, maar levert de dienst of het product niet zelf.</li></ul></article>
      <aside><h3>In één oogopslag</h3><dl><div><dt>Beschikbaar</dt><dd>{{ availability }}</dd></div><div><dt>Gebied</dt><dd>{{ offer.scope_type === 'local' ? 'Jouw gemeente' : 'Heel Nederland' }}</dd></div><div><dt>Route</dt><dd>{{ offer.type === 'external' ? 'Naar aanbieder' : 'Persoonlijk advies' }}</dd></div></dl></aside>
    </section>
    <section v-if="offer.type === 'form'" id="advies" class="form-band"><div class="shell form-band-inner"><div><p class="eyebrow">Vrijblijvend advies</p><h2>Vertel ons waar we je kunnen bereiken.</h2><p>Daarna neemt één geselecteerde partner contact op. Niet drie. Geen onnodig lange vragenlijst.</p></div><LeadForm context-type="deal" :context-slug="offer.slug" title="Ontvang gratis advies" button-label="Vraag gratis advies aan" /></div></section>
    <a v-if="offer.type === 'external'" class="mobile-external-cta" :href="offer.external_url" target="_blank" rel="noopener">{{ offer.cta_label }} <ArrowSquareOut :size="18" /></a>
  </template>
  <section v-else class="state-page"><div><GlobeHemisphereWest :size="38" /><h1>Dit aanbod is niet meer beschikbaar.</h1><p>Bekijk wat er nu voor je klaarstaat.</p><RouterLink class="button" to="/deals">Bekijk actueel aanbod</RouterLink></div></section>
</template>
