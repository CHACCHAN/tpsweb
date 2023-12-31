import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/tpsweb/public/about",
        component: () => import('./views/AboutView.vue'),
        name: "about"
    },
    {
        path: "/tpsweb/public/",
        component: () => import('./views/HomeView.vue'),
        name: "home"
    }
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: routes
})

export default router