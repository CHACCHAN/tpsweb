import { createRouter, createWebHistory } from "vue-router";

const routes = [
    // Home
    {
        path: "/",
        component: () => import('./views/home/HomeView.vue'),
        name: "home"
    },
    // Auth
    {
        path: "/auth",
        component: () => import('./views/auth/Auth.vue'),
        children: [
            {
                path: "/auth/register",
                component: () => import('./views/auth/Auth.vue'),
                name: 'register'
            },
            {
                path: "/auth/login",
                component: () => import('./views/auth/Auth.vue'),
                name: 'login'
            }
        ]
    },
    // Admin
    {
        path: "/admin",
        component: () => import('./views/admin/AdminView.vue'),
        name: 'admin'
    },
    // 例外処理
    {
        path: "/:pathMatch(.*)*",
        component: () => import('./views/home/HomeView.vue'), // 例外画面を表示する予定
        name: 'try'
    }
];

const router = createRouter({
    history: createWebHistory('tps-site'),
    routes: routes
});

export default router;