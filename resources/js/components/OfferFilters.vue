<script setup>
import { computed } from 'vue';
import { Briefcase, GlobeHemisphereWest, MapPin, SquaresFour, Storefront, Ticket, UsersThree, Wallet, Wrench } from '../icons';
import { pito } from '../stores/content';

const props = defineProps({ scope: String, category: String, items: { type: Array, default: () => [] }, localAvailable: Boolean });
const emit = defineEmits(['update:scope', 'update:category']);
const scopeItems = computed(() => [
  { value: 'all', label: 'Alles', hint: 'Helder bij elkaar', icon: SquaresFour },
  { value: 'local', label: 'Lokaal aanbod', hint: props.localAvailable ? 'Dichtbij gevonden' : 'Nog niet live', icon: MapPin, unavailable: !props.localAvailable },
  { value: 'fixed_costs', label: 'Vaste lasten', hint: 'Landelijk beschikbaar', icon: Wallet },
  { value: 'collective', label: 'Collectieven', hint: 'Persoonlijk geregeld', icon: UsersThree },
  { value: 'national', label: 'Landelijke deals', hint: 'Waar je ook woont', icon: GlobeHemisphereWest },
]);
const categoryIcons = { 'tickets-uitjes': Ticket, 'lokaal-aanbod': Storefront, 'lokale-diensten': Wrench, 'voor-ondernemers': Briefcase, 'nieuw-in-de-buurt': MapPin };
function countScope(value) {
  if (value === 'all') return props.items.length;
  if (value === 'collective') return pito.collectives.length;
  return props.items.filter((item) => item.scope_type === value).length;
}
function countCategory(slug) { return props.items.filter((item) => item.category === slug).length; }
</script>

<template>
  <div class="discovery-filter-groups" aria-label="Filter aanbod">
    <div class="discovery-filter-row">
      <span class="discovery-filter-label"><SquaresFour :size="17" /> Soort aanbod</span>
      <div class="discovery-filters">
        <button v-for="item in scopeItems" :key="item.value" class="discovery-filter" :class="[`discovery-filter--${item.value === 'fixed_costs' ? 'fixed' : item.value}`, { 'is-active': scope === item.value, 'is-unavailable': item.unavailable }]" type="button" @click="emit('update:scope', item.value)">
          <span class="discovery-filter-icon"><component :is="item.icon" :size="20" /></span>
          <span class="discovery-filter-copy"><span class="discovery-filter-text">{{ item.label }}</span><small>{{ item.hint }}</small></span>
          <span class="discovery-filter-count">{{ countScope(item.value) }}</span>
        </button>
      </div>
    </div>
    <div class="discovery-category-row">
      <span class="discovery-filter-label"><Storefront :size="17" /> Categorie</span>
      <div class="discovery-category-scroll">
        <button type="button" :class="{ 'is-active': category === 'all' }" @click="emit('update:category', 'all')"><SquaresFour :size="17" /> Alle categorieën</button>
        <button v-for="item in pito.categories" :key="item.slug" type="button" :class="{ 'is-active': category === item.slug }" :style="item.color ? { '--category-color': item.color } : {}" @click="emit('update:category', item.slug)">
          <span v-if="item.icon" class="discovery-category-emoji" aria-hidden="true">{{ item.icon }}</span>
          <component v-else :is="categoryIcons[item.slug] || Storefront" :size="17" />
          {{ item.label }} <span class="discovery-filter-count">{{ countCategory(item.slug) }}</span>
        </button>
      </div>
    </div>
  </div>
</template>
