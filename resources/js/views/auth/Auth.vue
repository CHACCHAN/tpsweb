<script setup>
    import { ref, onMounted } from 'vue'
    import { useRouter } from 'vue-router'
    import RegisterComponent from '../../components/auth/RegisterComponent.vue'
    import LoginComponent from '../../components/auth/LoginComponent.vue'
    import LoadingComponent from '../../components/loadingcomponent.vue'
    
    const router = useRouter()
    const isView = ref(false)
    const APP_NAME = ref('')
    const isLoadedWidth = ref()
    const isLoadedHeight = ref()
    const isLoadedBorder = ref()
    const isLoading = ref(true)
    const isSelectedComponentFlag = ref([])
    const isSelectedComponentTitle = ref()

    onMounted(() => {
        getLoginData()
        getAPP_NAME()
        SelectedComponentFlag()
        changeResponsive()
        window.addEventListener('resize', changeResponsive)
    })

    const getLoginData = () => {
        fetch('/tps-site/auth/login/check')
        .then((response) => response.json())
        .then(res => {
            if(res.ResponseData) {
                router.push({ name: 'home' })
            } else {
                isLoading.value = false
                isView.value = true
            }
        })
        .catch(error => {
            console.log(error)
        })
    }

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
    <LoadingComponent v-if="isLoading" />
    <div class="bg-dark d-flex align-items-center justify-content-center" style="width: 100%; height: 100vh;" v-if="isView">
        <div class="card text-bg-dark colorBackGradient" :class="{ 'border-light': isLoadedBorder}" :style="{ width: isLoadedWidth, height: isLoadedHeight }">
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

<style scoped>
#clickItem:hover {
    background: #3d3d3d;
}
.colorBackGradient { 
    background: linear-gradient(-45deg, #181818, #3a3a3a) fixed;
    background-size: 800% 800%;
    animation: gradietionAnimation 4s ease infinite;
}
@keyframes gradietionAnimation { 
    0%{
        background-position:0% 50%
    }
    50%{
        background-position:100% 50%
    }
    100%{
        background-position:0% 50%
    }
}
</style>