<script setup>
import { computed, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { Briefcase, CaretDown, Clock, Heart, MapPin, SquaresFour, Storefront, Student, Tag } from '../icons';
import VacancyCard from '../components/VacancyCard.vue';
import { currentMunicipality, pito, visibleJobs } from '../stores/content';

const route = useRoute();
const type = ref(route.params.type || 'alles');
const tag = ref('alle');
watch(() => route.params.type, (value) => { type.value = value || 'alles'; });
const types = [
  { value: 'alles', label: 'Alles', icon: SquaresFour }, { value: 'fulltime', label: 'Full-time', icon: Briefcase },
  { value: 'parttime', label: 'Part-time', icon: Clock }, { value: 'bijbaan', label: 'Bijbaan', icon: Storefront },
  { value: 'stage', label: 'Stage', icon: Student }, { value: 'vrijwilligerswerk', label: 'Vrijwilligerswerk', icon: Heart },
];
const availableTags = computed(() => (pito.job_tags?.length ? pito.job_tags : Array.from(new Set(visibleJobs.value.flatMap((job) => job.tags || [])))).sort());
const filtered = computed(() => visibleJobs.value.filter((job) => (type.value === 'alles' || job.type === type.value) && (tag.value === 'alle' || (job.tags || []).includes(tag.value))));
const count = (value) => value === 'alles' ? visibleJobs.value.length : visibleJobs.value.filter((job) => job.type === value).length;
const countTag = (value) => value === 'alle' ? visibleJobs.value.length : visibleJobs.value.filter((job) => (job.tags || []).includes(value)).length;
</script>

<template>
  <section class="page-hero page-hero--vacancies-editorial page-hero--compact"><div class="shell page-hero-inner"><div><p class="eyebrow eyebrow--light">Vacatures</p><h1>Werk dat dichterbij komt.</h1><p>Van vaste baan tot vrijwilligerswerk. Bekijk kansen in {{ currentMunicipality.name }} en functies die landelijk beschikbaar zijn.</p><button class="button button--light hero-municipality-button" type="button" @click="pito.municipalityModalOpen = true"><MapPin :size="18" /> {{ currentMunicipality.name }} <CaretDown :size="16" /></button></div><div class="page-hero-visual"><img class="page-hero-background" src="/assets/pito-website-v18/vacatures-beroepen-familie.png" alt="Verschillende beroepen en vacatures via PITO" /></div></div></section>
  <section class="vacancy-filter-section"><div class="shell vacancy-filter-panel"><div class="vacancy-filter-copy"><p class="eyebrow">Wat past bij jou?</p><strong>{{ filtered.length }} kansen gevonden</strong><span>Je solliciteert rechtstreeks bij de werkgever.</span></div><div class="filter-scroll vacancy-filter-scroll"><button v-for="item in types" :key="item.value" type="button" :class="{ active: type === item.value }" @click="type = item.value"><component :is="item.icon" :size="18" />{{ item.label }}<span>{{ count(item.value) }}</span></button></div></div></section>
  <section v-if="availableTags.length" class="vacancy-filter-section vacancy-filter-section--tags"><div class="shell vacancy-filter-panel"><div class="vacancy-filter-copy"><p class="eyebrow"><Tag :size="16" /> Vakgebied</p></div><div class="filter-scroll vacancy-filter-scroll"><button type="button" :class="{ active: tag === 'alle' }" @click="tag = 'alle'"><SquaresFour :size="18" />Alle vakgebieden<span>{{ countTag('alle') }}</span></button><button v-for="value in availableTags" :key="value" type="button" :class="{ active: tag === value }" @click="tag = value">{{ value }}<span>{{ countTag(value) }}</span></button></div></div></section>
  <section class="section"><div class="shell"><div v-if="filtered.length" class="vacancy-grid"><VacancyCard v-for="job in filtered" :key="job.slug" :job="job" /></div><div v-else class="empty-state"><h3>Nu geen vacatures binnen deze keuze.</h3><p>Kies een ander type of ontvang een seintje wanneer er iets nieuws verschijnt.</p><button class="button button--orange" type="button" @click="pito.signupModalOpen = true">Houd mij op de hoogte</button></div></div></section>
  <section class="vacancy-business section--sand section"><div class="shell vacancy-business"><div><p class="eyebrow">Werkgever of organisatie?</p><h2>Je vacature staat in ongeveer één minuut klaar.</h2><p>Kies zelf het gebied en de looptijd. Zodra de vacature verloopt, sturen we je een seintje.</p></div><RouterLink class="button button--orange" to="/voor-bedrijven">Plaats een vacature</RouterLink></div></section>
</template>
