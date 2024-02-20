<script setup>
    import { ref, onMounted } from 'vue'

    const isWidth = ref(false)
    const props = defineProps({
        APP_NAME: String,
        LoginFlag: Boolean,
        Adminstrator: Number,
        IconImage: String,
    })

    onMounted(() => {
        window.addEventListener('resize', changeResponsive)
        changeResponsive()
    })

    const changeResponsive = () => {
        if(window.screen.width <= 768) {
            isWidth.value = false
        } else {
            isWidth.value = true
        }
    }
</script>

<template>
    <div class="container-fluid bg-dark">
        <div class="container text-light">
            <div class="row">
                <!-- ロゴ -->
                <div class="col-12 col-md-5">
                    <div class="my-5">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <img class="rounded-circle" :src="IconImage" width="50px" alt="">
                            <h1 class="m-0 ms-2 fw-bold" v-text="APP_NAME"></h1>
                            <h1>®</h1>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="text-secondary border-top border-secondary" style="font-size: 10px;">
                                Powered By CHACCHAN SYSTEM
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-7 d-flex align-items-center">
                    <div class="row" :class="{ 'w-100': isWidth }">
                        <div class="col-6 col-md-4 text-center border-top border-bottom py-2 my-1">
                            <router-link :to="{ name: 'licence' }" class="text-light text-decoration-none">利用規約</router-link>
                        </div>
                        <div class="col-6 col-md-4 text-center border-top border-bottom py-2 my-1">
                            <router-link :to="{ name: 'privacy_policy' }" class="text-light text-decoration-none">プライバシーポリシー</router-link>
                        </div>
                        <div class="col-6 col-md-4 text-center border-top border-bottom py-2 my-1">
                            <router-link :to="{ name: 'contact' }" class="text-light text-decoration-none">お問い合わせ</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-light">
        <div class="text-center text-secondary" style="font-size: 12px;">Copyright © {{ APP_NAME }} by CHACCHAN SYSTEM . All Rights Reserved.</div>
    </div>
</template>