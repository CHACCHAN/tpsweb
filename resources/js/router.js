import { createRouter, createWebHistory } from "vue-router";

const routes = [
    // Home
    {
        path: "/",
        component: () => import('./views/home/HomeView.vue'),
        name: "home"
    },
    // Post
    {
        path: "/post:pathMatch(.*)*",
        component: () => import('./views/home/PostView.vue'),
        name: "post"
    },
    // Image
    {
        path: "/image:pathMatch(.*)*",
        component: () => import('./views/home/ImageView.vue'),
        name: "image"
    },
    // News
    {
        path: "/news",
        component: () => import('./views/home/NewsView.vue'),
        name: "news"
    },
    // Contact
    {
        path: "/contact",
        component: () => import('./views/home/ContactView.vue'),
        name: "contact"
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
            },
        ]
    },
    // プロフィール
    {
        path: "/auth/profile",
        component: () => import('./components/auth/ProfileComponent.vue'),
        name: 'profile'
    },
    // Admin
    {
        path: "/admin",
        component: () => import('./views/admin/AdminView.vue'),
        name: 'admin'
    },
    // フッターコンテンツ
    {
        path: "/footer",
        component: () => import('./views/footer/LayoutView.vue'),
        children: [
            {
                path: "/footer/licence",
                component: () => import('./views/footer/LayoutView.vue'),
                name: 'licence'
            },
            {
                path: "/footer/privacy_policy",
                component: () => import('./views/footer/LayoutView.vue'),
                name: 'privacy_policy'
            }
        ]
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