<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { ArrowLeft, ArrowRight, Check } from '../icons';
import LeadForm from '../components/LeadForm.vue';
import { collectiveBySlug } from '../stores/content';

const route = useRoute();
const collective = computed(() => collectiveBySlug(route.params.slug));
const steps = [
  ['Geef je interesse door', 'Laat je contact- en adresgegevens achter. Meer hoeft nog niet.'],
  ['Persoonlijke intake', 'De geselecteerde partner belt je om je situatie en wensen te bespreken.'],
  ['Advies op maat', 'Past de oplossing? Dan volgt persoonlijk advies en eventueel een afspraak op locatie.'],
  ['Jij beslist', 'De aanvraag is vrijblijvend. Jij bepaalt zelf of je verder wilt.'],
];
</script>

<template>
  <template v-if="collective">
    <section class="collective-detail-hero" :class="`collective-detail-hero--${collective.slug}`"><div class="shell collective-detail-hero-inner"><div class="collective-hero-copy"><RouterLink class="collective-hero-link" to="/collectieven"><ArrowLeft :size="17" /> Alle collectieven</RouterLink><p class="eyebrow eyebrow--light">{{ collective.title }}</p><h1>{{ collective.hero_title }}</h1><p>{{ collective.hero_intro }}</p><div class="hero-actions"><a class="button button--orange" href="#advies">{{ collective.cta_label }} <ArrowRight :size="18" /></a></div></div><div class="collective-hero-visual"><img :src="collective.image" :alt="collective.title" /><div class="collective-hero-caption"><span>Persoonlijk advies</span><strong>Een specialist bespreekt wat bij jouw situatie past.</strong></div></div></div></section>
    <div class="collective-hero-proof"><div><span>01</span><strong>Gratis aanvraag</strong></div><div><span>02</span><strong>Eén geselecteerde partner</strong></div><div><span>03</span><strong>Jij beslist</strong></div></div>
    <section class="section"><div class="shell collective-intro"><div><p class="eyebrow">Eerst helderheid</p><h2>Geen snelle rekensom. Wel een eerlijk gesprek.</h2><p>Een woning en huishouden zijn nooit standaard. Daarom begint dit collectief niet met een belofte, maar met persoonlijk advies over haalbaarheid, keuzes en vervolgstappen.</p></div><ul><li v-for="(item, index) in collective.focus" :key="item"><span>0{{ index + 1 }}</span>{{ item }}</li></ul></div></section>
    <section class="section section--sand"><div class="shell"><header class="section-heading"><div><p class="eyebrow">Zo werkt het</p><h2>Van interesse naar een beslissing die goed voelt.</h2></div></header><div class="collective-steps"><div v-for="(step, index) in steps" :key="step[0]"><span>0{{ index + 1 }}</span><h3>{{ step[0] }}</h3><p>{{ step[1] }}</p></div></div></div></section>
    <section class="collective-why"><div class="shell collective-why-inner"><div><p class="eyebrow eyebrow--light">Waarom deze route?</p><h2>Wij kiezen niet voor de goedkoopste belofte. We kiezen voor duidelijke verantwoordelijkheid.</h2></div><div><p>PITO kijkt naar advieskwaliteit, installatie, bereikbaarheid en service. De partner maakt de offerte, voert het werk uit en blijft verantwoordelijk voor installatie en garantie.</p><p><strong>PITO installeert niet.</strong> Wij brengen jou op basis van je aanvraag in contact met één geselecteerde partner.</p></div></div></section>
    <section class="collective-service-band"><div class="shell"><header class="section-heading"><div><p class="eyebrow">Van eerste gesprek tot service</p><h2>Je weet steeds wie waarvoor verantwoordelijk is.</h2></div></header><div class="service-grid"><div v-for="(item, index) in collective.service" :key="item"><span>0{{ index + 1 }}</span><h3>{{ item }}</h3><p>{{ index === 0 ? 'Een korte start, gericht op jouw situatie.' : index === 1 ? 'Geen standaardantwoord, maar een passende uitleg.' : index === 2 ? 'Afspraken en uitvoering lopen via de partner.' : 'Service en garantie zijn duidelijk vastgelegd.' }}</p></div></div></div></section>
    <section class="section"><div class="shell faq-section"><p class="eyebrow">Veelgestelde vragen</p><h2>Goed om vooraf te weten.</h2><div class="faq-list"><details v-for="faq in collective.faqs" :key="faq[0]"><summary>{{ faq[0] }}</summary><p>{{ faq[1] }}</p></details></div></div></section>
    <section id="advies" class="collective-inline-application"><div class="shell"><header><p class="eyebrow eyebrow--light">Vrijblijvend en persoonlijk</p><h2>{{ collective.cta_label }}</h2><p>Vul alleen je contact- en adresgegevens in. De geselecteerde partner bespreekt de inhoud daarna persoonlijk met je.</p></header><div class="collective-inline-form"><LeadForm context-type="collective" :context-slug="collective.slug" :title="collective.cta_label" :button-label="collective.cta_label" /></div><div class="collective-transparency-inline"><strong>Transparant over de samenwerking</strong><p>PITO ontvangt mogelijk een vergoeding wanneer een samenwerking tot stand komt. Dat verandert niets aan jouw prijsafspraak met de partner. De partner is verantwoordelijk voor advies, offerte, installatie, service en garantie.</p></div></div></section>
    <a class="mobile-collective-cta is-visible" href="#advies">{{ collective.cta_label }} <ArrowRight :size="18" /></a>
  </template>
  <section v-else class="state-page"><div><h1>Collectief niet gevonden.</h1><RouterLink class="button" to="/collectieven">Bekijk alle collectieven</RouterLink></div></section>
</template>
