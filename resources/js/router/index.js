import { createRouter, createWebHistory } from 'vue-router';
import EventoComponent from '../components/EventoComponent.vue';
import SingleEventoComponent from '../components/SingleEventoComponent.vue';

const routes = [
  {
    path: '/',
    name: 'eventos',
    component: EventoComponent,
  },
  {
    path: '/eventos/:id',
    name: 'eventoSingle',
    component: SingleEventoComponent,
    props: true,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
