<script setup>
    import { ref, onMounted } from 'vue'
    import Cookies from 'js-cookie'
    import TopHeader from '../../components/HeaderComponent.vue'
    import BottomFooter from '../../components/FooterComponent.vue'
    import DefaultComponent from '../../components/home/DefaultComponent.vue'
    import WelcomeComponent from '../../components/home/WelcomeComponent.vue'

    const isIconImage = location.protocol + '//' + location.host + '/tps-site/images/components/IconImage.png'
    const isAPP_NAME = ref()
    const isLoginFlag = ref()
    const isAdminstrator = ref()

    onMounted(() => {
        getAPP_NAME()
        getLoginData()
        document.title = 'ホーム'
        viewCount()
        window.scroll({
            top: 0,
            behavior: 'smooth'
        })
    })

    const viewCount = () => {
        const date = new Date()

        fetch('/tps-site/post/view/count/start', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                month: date.getMonth() + 1
            }),
        })
        .then((response) => response.json())
        .then(res => {})
        .catch(error => {
            console.log(error)
        })
    }

    // データ取得
    const getAPP_NAME = () => {
        fetch('/tps-site/get/env')
        .then((response) => response.json())
        .then(res => {
            isAPP_NAME.value = res.ResponseData
        })
        .catch(error => {
            console.log(error)
        })
    }

    const getLoginData = () => {
        fetch('/tps-site/auth/login/check')
        .then((response) => response.json())
        .then(res => {
            isLoginFlag.value = res.ResponseData
            isAdminstrator.value = res.Adminstrator
        })
        .catch(error => {
            console.log(error)
        })
    }
</script>

<template>
    <!-- Header -->
    <TopHeader :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" />
    
    <!-- Default -->
    <DefaultComponent />
    <!-- Component -->
    <WelcomeComponent />
    
    <!-- Footer -->
    <BottomFooter :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" />
</template>

<style scoped>
</style>