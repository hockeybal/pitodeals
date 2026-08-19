<script setup>
import SiteHeader from './components/SiteHeader.vue';
import SiteFooter from './components/SiteFooter.vue';
import MunicipalityModal from './components/MunicipalityModal.vue';
import SignupModal from './components/SignupModal.vue';
import { pito } from './stores/content';
</script>

<template>
  <div v-if="pito.loading" class="app-loading" role="status">
    <img src="/assets/pito-logo-officieel.svg" alt="" />
    <strong>PITO zet het voor je klaar…</strong>
  </div>
  <div v-else-if="pito.error" class="state-page">
    <div class="shell"><h1>Dat ging niet goed.</h1><p>{{ pito.error }}</p><button class="button" @click="$router.go(0)">Probeer opnieuw</button></div>
  </div>
  <template v-else>
    <SiteHeader />
    <main id="main-content"><RouterView /></main>
    <SiteFooter />
    <MunicipalityModal v-if="pito.municipalityModalOpen" />
    <SignupModal v-if="pito.signupModalOpen" />
  </template>
</template>
