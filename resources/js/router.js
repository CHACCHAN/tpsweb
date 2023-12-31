import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        component: () => import('./views/home/HomeView.vue'),
        name: "home"
    },
];

const router = createRouter({
    history: createWebHistory('tps-site'),
    routes: routes
});

export default router;