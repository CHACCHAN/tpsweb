<script setup>
    import { ref, onMounted } from 'vue'
    import RegisterComponent from '../../components/auth/RegisterComponent.vue';
    import LoginComponent from '../../components/auth/LoginComponent.vue';
    
    const APP_NAME = ref()
    const isLoadedWidth = ref()
    const isLoadedHeight = ref()
    const isLoadedBorder = ref()
    const isSelectedComponentFlag = ref([])
    const isSelectedComponentTitle = ref()

    onMounted(() => {
        getAPP_NAME()
        changeResponsive()
        SelectedComponentFlag()
        window.addEventListener('resize', changeResponsive)
    })

    const getAPP_NAME = () => {
        fetch('/tps-site/get/env')
        .then((response) => response.json())
        .then(res => {
            APP_NAME.value = res.ResponseData
        })
        .catch(error => {
            console.log(error)
        })
    }

    const changeResponsive = () => {
        if(window.screen.width <= 768) {
            isLoadedWidth.value = '100%'
            isLoadedHeight.value = '100vh'
            isLoadedBorder.value = false
        } else {
            isLoadedWidth.value = '35%'
            isLoadedHeight.value = '60%'
            isLoadedBorder.value = true
        }
    }

    const SelectedComponentFlag = () => {
        if(location.pathname === '/tps-site/auth/register') {
            isSelectedComponentFlag.value[0] = true
            isSelectedComponentTitle.value = 'アカウントの作成'
        } else if(location.pathname === '/tps-site/auth/login') {
            isSelectedComponentFlag.value[1] = true
            isSelectedComponentTitle.value = 'ログイン'
        }
    }
</script>

<template>
    <div class="bg-dark d-flex align-items-center justify-content-center" style="width: 100%; height: 100vh;">
        <div class="card text-bg-dark" :class="{ 'border-light': isLoadedBorder}" :style="{ width: isLoadedWidth, height: isLoadedHeight }">
            <div class="card-header border-0">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="text-center">
                        <router-link to="/" class="fw-bold fs-1 text-light text-decoration-none">{{ APP_NAME }}</router-link>
                        <h4 v-text="APP_NAME + isSelectedComponentTitle"></h4>
                    </div>
                </div>
            </div>
            <div class="card-body overflow-auto">
                <!-- Components -->
                <RegisterComponent v-if="isSelectedComponentFlag[0]" />
                <LoginComponent v-if="isSelectedComponentFlag[1]" />
            </div>
        </div>
    </div>
</template>