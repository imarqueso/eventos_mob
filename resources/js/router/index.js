import { createRouter, createWebHistory } from 'vue-router';
import EventoComponent from '../components/EventoComponent.vue';

const routes = [
  {
    path: '/',
    name: 'eventos',
    component: EventoComponent,
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
