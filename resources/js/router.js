import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        component: () => import('./views/home/HomeView.vue'),
        name: "home"
    },
    // Auth
    {
        path: "/auth:pathMatch(.*)*",
        component: () => import('./views/auth/Auth.vue'),
        children: [
            {
                path: "/auth/register:pathMatch(.*)*",
                component: () => import('./views/auth/Auth.vue'),
                name: 'register'
            },
            {
                path: "/auth/login:pathMatch(.*)*",
                component: () => import('./views/auth/Auth.vue'),
                name: 'login'
            }
        ]
    },
];

const router = createRouter({
    history: createWebHistory('tps-site'),
    routes: routes
});

export default router;