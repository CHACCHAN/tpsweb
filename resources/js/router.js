import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        component: () => import('./views/home/HomeView.vue'),
        name: "home"
    },
    {
        path: "/about",
        component: () => import('./views/AboutView.vue'),
        name: "about"
    },
]

const router = createRouter({
    history: createWebHistory('tps-site'),
    routes: routes
});

export default router;