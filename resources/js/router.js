import { createRouter, createWebHistory } from 'vue-router';
import HomeView from './views/HomeView.vue';
import DealsView from './views/DealsView.vue';
import DealDetailView from './views/DealDetailView.vue';
import CollectivesView from './views/CollectivesView.vue';
import CollectiveDetailView from './views/CollectiveDetailView.vue';
import VacanciesView from './views/VacanciesView.vue';
import VacancyDetailView from './views/VacancyDetailView.vue';
import AboutView from './views/AboutView.vue';
import BusinessView from './views/BusinessView.vue';
import ContactView from './views/ContactView.vue';
import LegalView from './views/LegalView.vue';
import NotFoundView from './views/NotFoundView.vue';
import { pito } from './stores/content';

const legal = (slug, title) => ({ path: `/${slug}`, component: LegalView, props: { document: slug, title } });

const routes = [
  { path: '/', component: HomeView },
  { path: '/start', redirect: '/' },
  { path: '/gemeente/:municipality', component: HomeView },
  { path: '/deals', component: DealsView },
  { path: '/landelijk', component: DealsView, props: { initialScope: 'national' } },
  { path: '/vaste-lasten', component: DealsView, props: { initialScope: 'fixed_costs' } },
  { path: '/deals/categorie/:category', component: DealsView },
  { path: '/deals/:slug', component: DealDetailView },
  { path: '/collectieven', component: CollectivesView },
  { path: '/collectieven/:slug/aanvragen', component: CollectiveDetailView, props: { openForm: true } },
  { path: '/collectieven/:slug', component: CollectiveDetailView },
  { path: '/vacatures', component: VacanciesView },
  { path: '/vacatures/categorie/:type', component: VacanciesView },
  { path: '/vacatures/:slug', component: VacancyDetailView },
  { path: '/over-pito', component: AboutView },
  { path: '/zo-werkt-het', redirect: '/over-pito' },
  { path: '/voor-bedrijven', component: BusinessView },
  { path: '/voor-ondernemers', redirect: '/voor-bedrijven' },
  { path: '/contact', component: ContactView },
  legal('privacy', 'Privacyverklaring'),
  legal('voorwaarden', 'Algemene voorwaarden'),
  legal('voorwaarden-collectieven', 'Voorwaarden collectieven'),
  legal('disclaimer', 'Disclaimer'),
  legal('cookies', 'Cookieverklaring'),
  { path: '/designs', redirect: '/' },
  { path: '/:pathMatch(.*)*', component: NotFoundView },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to) {
    if (to.hash) return { el: to.hash, behavior: 'smooth' };
    return { top: 0 };
  },
});

router.afterEach((to) => {
  const labels = {
    '/vacatures': 'Vacatures bij jou in de buurt',
    '/collectieven': 'PITO Collectieven',
    '/voor-bedrijven': 'PITO voor bedrijven',
    '/over-pito': 'Over PITO',
  };
  let label = labels[to.path];
  if (to.path.startsWith('/gemeente/')) {
    const municipality = pito.municipalities.find((item) => item.slug === to.params.municipality);
    label = `Lokale deals en vacatures in ${municipality?.name || 'jouw gemeente'}`;
  } else if (to.path.startsWith('/deals/') && to.params.slug) {
    label = pito.offers.find((item) => item.slug === to.params.slug)?.title || 'Bekijk het aanbod';
  } else if (to.path.startsWith('/vacatures/') && to.params.slug) {
    label = pito.jobs.find((item) => item.slug === to.params.slug)?.title || 'Bekijk de vacature';
  } else if (to.path.startsWith('/collectieven/') && to.params.slug) {
    label = pito.collectives.find((item) => item.slug === to.params.slug)?.title || 'PITO Collectief';
  }
  document.title = `${label || 'PITO'} — Ontdek je voordeel`;
});

export default router;
