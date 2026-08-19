<script setup>
import { reactive, ref } from 'vue';
import axios from 'axios';
import { ArrowRight, Check } from '../icons';
import { currentMunicipality } from '../stores/content';

const props = defineProps({
  contextType: { type: String, required: true },
  contextSlug: { type: String, required: true },
  title: { type: String, default: 'Ontvang gratis advies voor jouw woning' },
  buttonLabel: { type: String, default: 'Plan gratis advies' },
});
const sending = ref(false);
const done = ref(false);
const errors = ref({});
const form = reactive({ first_name: '', last_name: '', email: '', phone: '', street: '', house_number: '', postal_code: '', city: currentMunicipality.value.name, consent: false, marketing_consent: false });

async function submit() {
  sending.value = true; errors.value = {};
  try {
    await axios.post('/api/leads', { ...form, context_type: props.contextType, context_slug: props.contextSlug, municipality_slug: currentMunicipality.value.slug });
    done.value = true;
  } catch (error) {
    errors.value = error.response?.data?.errors || { form: ['Versturen lukt nu niet. Probeer het later opnieuw.'] };
  } finally { sending.value = false; }
}
const errorFor = (field) => errors.value[field]?.[0];
</script>

<template>
  <div v-if="done" class="success-panel collective-success">
    <Check :size="34" weight="bold" />
    <h3>Bedankt, je aanvraag staat klaar.</h3>
    <p>De geselecteerde partner neemt persoonlijk contact met je op. Jij beslist daarna zelf of je verder wilt.</p>
    <div class="advice-assurances"><span><Check :size="15" /> Eén geselecteerde partner</span><span><Check :size="15" /> Vrijblijvend</span><span><Check :size="15" /> Duidelijke vervolgstap</span></div>
  </div>
  <form v-else class="lead-form collective-lead-form" @submit.prevent="submit">
    <h3>{{ title }}</h3>
    <p class="fine-print">Kort formulier. De specialist bespreekt de rest persoonlijk met je.</p>
    <div class="form-grid">
      <label class="field"><span>Voornaam</span><input v-model="form.first_name" required autocomplete="given-name" /><small v-if="errorFor('first_name')">{{ errorFor('first_name') }}</small></label>
      <label class="field"><span>Achternaam</span><input v-model="form.last_name" required autocomplete="family-name" /><small v-if="errorFor('last_name')">{{ errorFor('last_name') }}</small></label>
      <label class="field"><span>E-mailadres</span><input v-model="form.email" required type="email" autocomplete="email" /><small v-if="errorFor('email')">{{ errorFor('email') }}</small></label>
      <label class="field"><span>Telefoonnummer</span><input v-model="form.phone" required type="tel" autocomplete="tel" /><small v-if="errorFor('phone')">{{ errorFor('phone') }}</small></label>
      <label class="field field--wide"><span>Straat</span><input v-model="form.street" required autocomplete="street-address" /><small v-if="errorFor('street')">{{ errorFor('street') }}</small></label>
      <label class="field"><span>Huisnummer</span><input v-model="form.house_number" required /><small v-if="errorFor('house_number')">{{ errorFor('house_number') }}</small></label>
      <label class="field"><span>Postcode</span><input v-model="form.postal_code" required autocomplete="postal-code" placeholder="1234 AB" /><small v-if="errorFor('postal_code')">{{ errorFor('postal_code') }}</small></label>
      <label class="field field--wide"><span>Plaats</span><input v-model="form.city" required autocomplete="address-level2" /></label>
    </div>
    <label class="collective-consent"><input v-model="form.consent" type="checkbox" required /> Ik ga akkoord met de <RouterLink to="/privacy">privacyverklaring</RouterLink> en geef toestemming dat PITO mijn gegevens uitsluitend deelt met één geselecteerde partner voor deze vrijblijvende adviesaanvraag.</label>
    <div class="advice-assurances"><span><Check :size="15" /> Eén partner</span><span><Check :size="15" /> Persoonlijk contact</span><span><Check :size="15" /> Zonder verplichtingen</span></div>
    <p v-if="errors.form" class="form-error">{{ errors.form[0] }}</p>
    <button class="button button--orange" :disabled="sending" type="submit">{{ sending ? 'Even versturen…' : buttonLabel }} <ArrowRight :size="18" /></button>
  </form>
</template>
