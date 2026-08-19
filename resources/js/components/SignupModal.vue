<script setup>
import { reactive, ref } from 'vue';
import axios from 'axios';
import { Check, X } from '../icons';
import { currentMunicipality, pito } from '../stores/content';

const sending = ref(false);
const done = ref(false);
const errors = ref({});
const form = reactive({
  email: '', municipality_slug: currentMunicipality.value.slug, municipality_name: currentMunicipality.value.name,
  deals: true, vacancies: true, street: '', house_number: '', postal_code: '', city: currentMunicipality.value.name,
  consent: false,
});

async function submit() {
  sending.value = true; errors.value = {};
  try {
    await axios.post('/api/subscriptions', { ...form });
    done.value = true;
  } catch (error) {
    errors.value = error.response?.data?.errors || { form: ['Aanmelden lukt nu niet. Probeer het later opnieuw.'] };
  } finally { sending.value = false; }
}
</script>

<template>
  <div class="modal-backdrop" role="presentation" @click.self="pito.signupModalOpen = false">
    <section class="update-modal" role="dialog" aria-modal="true" aria-labelledby="signup-title">
      <header class="modal-head update-modal-head">
        <div><p class="eyebrow">PITO updates</p><h2 id="signup-title">Nieuwe kansen. Rechtstreeks naar jou.</h2><strong>{{ currentMunicipality.name }}</strong></div>
        <button class="close-button" type="button" aria-label="Sluiten" @click="pito.signupModalOpen = false"><X :size="24" /></button>
      </header>
      <div v-if="done" class="update-success">
        <Check :size="30" weight="bold" />
        <strong>Je staat op de lijst.</strong><span>We sturen alleen updates die passen bij je keuzes.</span>
        <button class="button button--light" type="button" @click="pito.signupModalOpen = false">Sluiten</button>
      </div>
      <form v-else class="update-signup" @submit.prevent="submit">
        <div class="update-signup-copy">
          <p class="eyebrow eyebrow--light">Kies zelf wat binnenkomt</p>
          <h2>Laat voordeel en werk niet aan je voorbijgaan.</h2>
          <p>Ontvang een seintje bij nieuw lokaal aanbod of passende vacatures. Met je adres kunnen we kansen selecteren die passen bij jouw straat of woning.</p>
          <ul><li>Afmelden kan altijd</li><li>Geen verkoop van persoonsgegevens</li><li>Alleen relevante PITO-updates</li></ul>
        </div>
        <div class="update-signup-fields">
          <label class="signup-field"><span>E-mailadres</span><input v-model="form.email" type="email" required autocomplete="email" placeholder="jij@voorbeeld.nl" /><small v-if="errors.email">{{ errors.email[0] }}</small></label>
          <label class="signup-field"><span>Gemeente</span><input :value="currentMunicipality.name" readonly /></label>
          <fieldset class="preference-field"><legend>Ik ontvang graag</legend><label><input v-model="form.deals" type="checkbox" /> Deals en collectieven</label><label><input v-model="form.vacancies" type="checkbox" /> Vacatures</label></fieldset>
          <div class="personal-address is-required">
            <div><strong>Jouw adres</strong><span>Daarmee houden we aanbiedingen lokaal en relevant.</span></div>
            <div class="personal-address-grid">
              <label class="signup-field personal-address-street"><span>Straat</span><input v-model="form.street" required autocomplete="street-address" /></label>
              <label class="signup-field"><span>Huisnummer</span><input v-model="form.house_number" required /></label>
              <label class="signup-field"><span>Postcode</span><input v-model="form.postal_code" required autocomplete="postal-code" placeholder="1234 AB" /></label>
              <label class="signup-field"><span>Plaats</span><input v-model="form.city" required autocomplete="address-level2" /></label>
            </div>
          </div>
          <label class="signup-consent"><input v-model="form.consent" type="checkbox" required /><span>Ik geef PITO toestemming om mijn e-mailadres en adres te gebruiken voor relevante lokale selectie en om deze updates te sturen. Lees de <RouterLink to="/privacy" @click="pito.signupModalOpen = false">privacyverklaring</RouterLink>.</span></label>
          <p v-if="errors.form" class="form-error">{{ errors.form[0] }}</p>
          <button class="button button--orange" :disabled="sending" type="submit">{{ sending ? 'Even regelen…' : 'Houd mij op de hoogte' }}</button>
        </div>
      </form>
    </section>
  </div>
</template>
