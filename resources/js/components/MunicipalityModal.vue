<script setup>
import { computed, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { MagnifyingGlass, X } from '../icons';
import { pito, setMunicipality } from '../stores/content';

const search = ref('');
const route = useRoute();
const router = useRouter();
const filtered = computed(() => {
  const query = search.value.trim().toLowerCase();
  return pito.municipalities.filter((item) => !query || item.name.toLowerCase().includes(query)).slice(0, 80);
});

function choose(item) {
  setMunicipality(item.slug);
  if (route.path === '/' || route.path.startsWith('/gemeente/')) router.push(`/gemeente/${item.slug}`);
}
</script>

<template>
  <div class="modal-backdrop" role="presentation" @click.self="pito.municipalityModalOpen = false">
    <section class="municipality-modal" role="dialog" aria-modal="true" aria-labelledby="municipality-title">
      <header class="modal-head">
        <div><p class="eyebrow">Jouw omgeving</p><h2 id="municipality-title">Kies je gemeente</h2></div>
        <button class="close-button" type="button" aria-label="Sluiten" @click="pito.municipalityModalOpen = false"><X :size="24" /></button>
      </header>
      <label class="modal-search">
        <MagnifyingGlass :size="19" aria-hidden="true" />
        <span class="sr-only">Zoek gemeente</span>
        <input v-model="search" autofocus type="search" placeholder="Zoek jouw gemeente" />
      </label>
      <p class="fine-print">Gemeenten waar PITO live is staan bovenaan. Landelijk aanbod blijft overal beschikbaar.</p>
      <div class="municipality-list">
        <button v-for="item in filtered" :key="item.slug" type="button" @click="choose(item)">
          <strong>{{ item.name }}</strong>
          <span class="municipality-option-meta"><em v-if="item.is_live">Live</em><span>{{ item.is_live ? 'Lokaal + landelijk' : 'Landelijk aanbod' }}</span></span>
        </button>
      </div>
      <p v-if="!filtered.length" class="fine-print">Geen gemeente gevonden. Controleer de spelling.</p>
    </section>
  </div>
</template>
