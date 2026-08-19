<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { ArrowLeft, ArrowSquareOut, Briefcase, MapPin } from '../icons';
import { jobBySlug } from '../stores/content';
const route = useRoute();
const job = computed(() => jobBySlug(route.params.slug));
</script>

<template>
  <template v-if="job">
    <section class="detail-hero"><div class="detail-media vacancy-detail-media"><img :src="job.image" :alt="job.title" /><strong>{{ job.compensation_label }}</strong></div><div><RouterLink class="back-link" to="/vacatures"><ArrowLeft :size="17" /> Terug naar vacatures</RouterLink><p class="eyebrow">Vacature</p><h1>{{ job.title }}</h1><p class="lead">{{ job.employer }}</p><div class="vacancy-detail-facts"><span><Briefcase :size="16" /> {{ job.hours }}</span><span><MapPin :size="16" /> {{ job.location }}</span><span>{{ job.workplace }}</span><span>{{ job.experience_level }}</span></div><p>{{ job.intro }}</p><a class="button button--orange" :href="job.external_url" target="_blank" rel="noopener">{{ job.cta_label }} <ArrowSquareOut :size="18" /></a></div></section>
    <section class="shell detail-body vacancy-detail-body"><article><h2>Over deze functie</h2><p v-for="paragraph in job.description" :key="paragraph">{{ paragraph }}</p><h3>Wat de werkgever zoekt</h3><ul><li v-for="item in job.requirements" :key="item">{{ item }}</li></ul><h3>Wat je kunt verwachten</h3><ul><li v-for="item in job.benefits" :key="item">{{ item }}</li></ul></article><aside><h3>In één oogopslag</h3><dl><div><dt>Dienstverband</dt><dd>{{ job.type }}</dd></div><div><dt>Uren</dt><dd>{{ job.hours }}</dd></div><div><dt>Werkplek</dt><dd>{{ job.workplace }}</dd></div><div><dt>Solliciteren</dt><dd>Rechtstreeks bij werkgever</dd></div></dl></aside></section>
    <a class="mobile-external-cta" :href="job.external_url" target="_blank" rel="noopener">{{ job.cta_label }} <ArrowSquareOut :size="18" /></a>
  </template>
  <section v-else class="state-page"><div><h1>Deze vacature is niet meer beschikbaar.</h1><RouterLink class="button" to="/vacatures">Bekijk actuele vacatures</RouterLink></div></section>
</template>
