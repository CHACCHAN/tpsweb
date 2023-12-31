import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/about",
        component: () => import('./views/AboutView.vue'),
        name: "about"
    },
    {
        path: "/",
        component: () => import('./views/HomeView.vue'),
        name: "home"
    }
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL + 'tps-site'),
    routes: routes
})

export default router