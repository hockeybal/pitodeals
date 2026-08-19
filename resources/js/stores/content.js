import axios from 'axios';
import { computed, reactive } from 'vue';
import { slugifyMunicipality } from '../data/municipalities';

const storedMunicipality = typeof window !== 'undefined' ? window.localStorage.getItem('pito_municipality') : null;

export const pito = reactive({
  loading: true,
  error: null,
  settings: {},
  categories: [],
  offers: [],
  collectives: [],
  jobs: [],
  municipalities: [],
  links: {},
  municipalitySlug: storedMunicipality || 'woerden',
  municipalityModalOpen: false,
  signupModalOpen: false,
});

export async function loadContent() {
  pito.loading = true;
  try {
    const response = await axios.get('/api/content');
    const data = response.data.data;
    Object.assign(pito, data, { loading: false, error: null });
    const exists = pito.municipalities.some((item) => item.slug === pito.municipalitySlug);
    if (!exists) pito.municipalitySlug = data.settings?.default_municipality || 'woerden';
  } catch (error) {
    pito.error = 'De inhoud kon niet worden geladen. Probeer de pagina opnieuw.';
    pito.loading = false;
  }
}

export function setMunicipality(value) {
  const slug = slugifyMunicipality(value || 'woerden');
  if (!pito.municipalities.some((item) => item.slug === slug)) return;
  pito.municipalitySlug = slug;
  window.localStorage.setItem('pito_municipality', slug);
  pito.municipalityModalOpen = false;
}

export const currentMunicipality = computed(() =>
  pito.municipalities.find((item) => item.slug === pito.municipalitySlug) || {
    name: 'Woerden', slug: 'woerden', state: 'live', is_live: true,
  },
);

export const localOffers = computed(() =>
  pito.offers.filter((offer) => offer.municipalities?.includes(pito.municipalitySlug)),
);

export const nationalOffers = computed(() =>
  pito.offers.filter((offer) => ['national', 'fixed_costs'].includes(offer.scope_type)),
);

export const visibleJobs = computed(() =>
  pito.jobs.filter((job) => !job.municipalities?.length || job.municipalities.includes(pito.municipalitySlug)),
);

export function offerBySlug(slug) {
  return pito.offers.find((offer) => offer.slug === slug);
}

export function jobBySlug(slug) {
  return pito.jobs.find((job) => job.slug === slug);
}

export function collectiveBySlug(slug) {
  return pito.collectives.find((collective) => collective.slug === slug);
}
