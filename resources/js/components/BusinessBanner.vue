<script setup>
import { ArrowRight } from '../icons';
</script>

<template>
  <section class="entrepreneur-banner">
    <div class="shell entrepreneur-banner-inner">
      <div class="entrepreneur-banner-copy">
        <p class="eyebrow eyebrow--light">Voor bedrijven en organisaties</p>
        <h2>Jouw aanbod of vacature. Precies waar mensen kijken.</h2>
        <p>Plaats in ongeveer één minuut een vacature of aanbieding en kies zelf in welke gemeenten je zichtbaar wilt zijn.</p>
        <div class="entrepreneur-proof"><span>Direct online</span><span>Zelf je looptijd bepalen</span><span>Maandelijks opzegbaar</span></div>
        <div class="entrepreneur-actions"><RouterLink class="button button--orange" to="/voor-bedrijven">Bekijk wat PITO oplevert <ArrowRight :size="18" /></RouterLink></div>
      </div>
      <div class="entrepreneur-banner-visual"><img class="entrepreneur-photo" src="/assets/pito-website-v18/deals-bakker-app-korting.png" alt="Lokale ondernemer in contact met de buurt" /><img class="entrepreneur-pito" src="/assets/pito-mascotte/06_pito_vacatures_knipoog.png" alt="" /></div>
    </div>
  </section>
</template>
