<script setup>
import { computed } from 'vue';
import { ArrowRight, GlobeHemisphereWest, MapPin, UsersThree, Wallet } from '../icons';

const props = defineProps({ item: { type: Object, required: true }, compact: Boolean });
const kind = computed(() => props.item.scope_type === 'fixed_costs' ? 'fixed' : props.item.scope_type === 'national' ? 'national' : props.item.scope_type === 'collective' ? 'collective' : 'local');
const label = computed(() => ({ local: 'Lokaal aanbod', fixed: 'Vaste lasten', collective: 'Collectief', national: 'Landelijk voordeel' })[kind.value]);
const route = computed(() => kind.value === 'collective' ? `/collectieven/${props.item.slug}` : `/deals/${props.item.slug}`);
const Icon = computed(() => ({ local: MapPin, fixed: Wallet, collective: UsersThree, national: GlobeHemisphereWest })[kind.value]);
const scope = computed(() => kind.value === 'local' ? 'In jouw gemeente' : kind.value === 'collective' ? 'Persoonlijk geregeld' : 'Online beschikbaar');
</script>

<template>
  <RouterLink class="discovery-card" :class="[`discovery-card--${kind}`, { 'discovery-card--compact': compact }]" :to="route">
    <div class="discovery-card-visual">
      <img :src="item.image" :alt="item.title" loading="lazy" />
      <span class="discovery-card-scene-label"><component :is="Icon" :size="15" /> {{ label }}</span>
    </div>
    <div class="discovery-card-body">
      <span class="discovery-type" :class="`discovery-type--${kind}`"><component :is="Icon" :size="15" /> {{ label }}</span>
      <h3>{{ item.title }}</h3>
      <p v-if="item.partner" class="discovery-provider">{{ item.partner }}</p>
      <p>{{ item.intro }}</p>
      <div class="discovery-card-footer">
        <span>{{ scope }}</span>
        <strong>{{ item.cta_label || 'Bekijk aanbod' }} <ArrowRight :size="17" /></strong>
      </div>
    </div>
  </RouterLink>
</template>
