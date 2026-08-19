<script setup>
import { ArrowRight, MapPin } from '../icons';
defineProps({ job: { type: Object, required: true } });
const labels = { fulltime: 'Full-time', parttime: 'Part-time', bijbaan: 'Bijbaan', stage: 'Stage', vrijwilligerswerk: 'Vrijwilligerswerk' };
</script>

<template>
  <RouterLink class="vacancy-card" :to="`/vacatures/${job.slug}`">
    <div class="vacancy-card-media"><img :src="job.image" :alt="job.title" loading="lazy" /><span>{{ labels[job.type] || job.type }}</span></div>
    <div>
      <div class="vacancy-card-meta"><span>{{ job.hours }}</span><span>{{ job.workplace }}</span></div>
      <h3>{{ job.title }}</h3>
      <p class="vacancy-employer">{{ job.employer }}</p>
      <p>{{ job.intro }}</p>
      <div class="vacancy-card-footer"><strong><MapPin :size="15" /> {{ job.location }}</strong><span>Bekijk vacature <ArrowRight :size="16" /></span></div>
    </div>
  </RouterLink>
</template>
