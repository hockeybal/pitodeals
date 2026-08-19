<script setup>
import { ArrowRight } from '../icons';
import { pito } from '../stores/content';
</script>

<template>
  <footer class="site-footer">
    <div class="footer-navigation-zone">
      <div class="shell footer-grid">
        <div>
          <RouterLink class="brand" to="/"><span class="brand-lockup"><img class="brand-logo" src="/assets/pito-logo-officieel.svg" alt="" /><span class="brand-copy"><span class="brand-title"><strong>PITO</strong><i>·</i><span>Ontdek je voordeel</span></span></span></span></RouterLink>
          <p>Een betrouwbare route naar lokaal aanbod, vacatures en geselecteerde partners.</p>
        </div>
        <div><strong>Ontdek</strong><RouterLink to="/deals">Alle aanbod</RouterLink><RouterLink to="/vacatures">Vacatures</RouterLink><RouterLink to="/collectieven">Collectieven</RouterLink><RouterLink to="/vaste-lasten">Vaste lasten</RouterLink></div>
        <div><strong>PITO</strong><RouterLink to="/over-pito">Over PITO</RouterLink><a :href="pito.links.app_download" target="_blank" rel="noopener">Download PITO <ArrowRight :size="14" /></a><RouterLink to="/voor-bedrijven">Voor bedrijven</RouterLink><RouterLink to="/contact">Contact</RouterLink><a :href="pito.links.business_login" target="_blank" rel="noopener">Zakelijk inloggen</a></div>
        <div><strong>Duidelijke afspraken</strong><RouterLink to="/privacy">Privacy</RouterLink><RouterLink to="/voorwaarden">Voorwaarden</RouterLink><RouterLink to="/voorwaarden-collectieven">Collectieven</RouterLink><RouterLink to="/cookies">Cookies</RouterLink><RouterLink to="/disclaimer">Disclaimer</RouterLink></div>
      </div>
    </div>
    <div class="footer-bottom-zone"><div class="shell footer-bottom"><span>© {{ new Date().getFullYear() }} PITO</span><span>PITO brengt je in contact. Partners leveren het aanbod en de uitvoering.</span></div></div>
  </footer>
</template>
