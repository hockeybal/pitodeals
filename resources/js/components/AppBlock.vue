<script setup>
import { ArrowRight } from '../icons';
import { pito } from '../stores/content';
</script>

<template>
  <section class="app-block">
    <div class="shell app-block-inner">
      <div class="app-block-copy">
        <p class="eyebrow eyebrow--light">De PITO-app</p>
        <h2>Kom in contact met je buren. En haal meer uit je buurt.</h2>
        <p>Bekijk de buurtfeed, ontdek activiteiten op de kaart en praat rechtstreeks met mensen om je heen.</p>
        <a class="button button--orange" :href="pito.links.app_download" target="_blank" rel="noopener">Download PITO <ArrowRight :size="18" /></a>
      </div>
      <div class="app-screens" aria-label="Drie schermen uit de PITO-app">
        <figure class="app-screen"><img src="/assets/app-screens-official/feed.png" alt="Buurtfeed in de PITO-app" /><figcaption>Wat er speelt</figcaption></figure>
        <figure class="app-screen"><img src="/assets/app-screens-official/agenda.png" alt="Buurtagenda in de PITO-app" /><figcaption>Wat je kunt doen</figcaption></figure>
        <figure class="app-screen"><img src="/assets/app-screens-official/kaart.png" alt="Buurtkaart in de PITO-app" /><figcaption>Wat dichtbij is</figcaption></figure>
      </div>
    </div>
  </section>
</template>
