import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/tps/about",
        component: () => import('./views/AboutView.vue'),
        name: "about"
    },
    {
        path: "/tps/",
        component: () => import('./views/HomeView.vue'),
        name: "home"
    }
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: routes
})

export default router