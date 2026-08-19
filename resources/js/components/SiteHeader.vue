<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import { CaretDown, List, MapPin, X } from '../icons';
import { currentMunicipality, pito } from '../stores/content';

const open = ref(false);
const route = useRoute();
const isActive = (prefix) => route.path === prefix || route.path.startsWith(`${prefix}/`);
</script>

<template>
  <header class="site-header">
    <RouterLink class="brand" to="/" aria-label="PITO - Ontdek je voordeel, naar de homepage">
      <span class="brand-lockup brand-lockup--compact">
        <img class="brand-logo" src="/assets/pito-logo-officieel.svg" alt="" />
        <span class="brand-copy">
          <span class="brand-title"><strong>PITO</strong><i>·</i><span>Ontdek je voordeel</span></span>
        </span>
      </span>
    </RouterLink>

    <nav class="main-nav" :class="{ 'is-open': open }" aria-label="Hoofdnavigatie">
      <RouterLink to="/deals" :class="{ 'is-active': isActive('/deals') || isActive('/vaste-lasten') }" @click="open = false">Aanbod</RouterLink>
      <RouterLink to="/vacatures" :class="{ 'is-active': isActive('/vacatures') }" @click="open = false">Vacatures</RouterLink>
      <RouterLink to="/collectieven" :class="{ 'is-active': isActive('/collectieven') }" @click="open = false">Collectieven</RouterLink>
      <RouterLink to="/vaste-lasten" @click="open = false">Vaste lasten</RouterLink>
      <RouterLink to="/over-pito" :class="{ 'is-active': isActive('/over-pito') }" @click="open = false">Over PITO</RouterLink>
      <RouterLink class="nav-business-link" to="/voor-bedrijven" :class="{ 'is-active': isActive('/voor-bedrijven') }" @click="open = false">Voor bedrijven</RouterLink>
    </nav>

    <div class="header-actions">
      <button class="location-button" type="button" @click="pito.municipalityModalOpen = true">
        <MapPin :size="18" weight="fill" aria-hidden="true" />
        <strong>{{ currentMunicipality.name }}</strong>
        <CaretDown :size="16" aria-hidden="true" />
      </button>
      <button class="menu-button" type="button" :aria-expanded="open" aria-label="Menu openen of sluiten" @click="open = !open">
        <X v-if="open" :size="22" /><List v-else :size="22" />
      </button>
    </div>
  </header>
</template>
