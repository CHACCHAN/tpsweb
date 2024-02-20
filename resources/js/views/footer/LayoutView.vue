<script setup>
    import { ref, onMounted, watch } from 'vue'
    import { useRouter } from 'vue-router'
    import TopHeader from '../../components/HeaderComponent.vue'
    import BottomFooter from '../../components/FooterComponent.vue'
    import Licence from '../../components/footerContents/Licence.vue'
    import PrivacyPolicy from '../../components/footerContents/PrivacyPolicy.vue'
    
    const router = useRouter()
    watch(() => router.currentRoute.value.path, () => {
        linkPost()
    })
    const isIconImage = location.protocol + '//' + location.host + '/tps-site/images/components/IconImage.png'
    const isAPP_NAME = ref()
    const isLoginFlag = ref()
    const isAdminstrator = ref()
    const viewPage = ref([{
        'licence': false,
        'privacy_policy': false,
    }])

    onMounted(() => {
        getAPP_NAME()
        getLoginData()
        linkPost()
        moveWindowOnTop()
    })

    const linkPost = () => {
        const path = decodeURI(location.pathname).split('/')[3]

        switch(path) {
            case 'licence':
                reset()
                viewPage.value.licence = true
                document.title = '利用規約'
                moveWindowOnTop()
                break
            case 'privacy_policy':
                reset()
                viewPage.value.privacy_policy = true
                document.title = 'プライバシーポリシー'
                moveWindowOnTop()
                break
            default:
                break
        }
        

        function reset() {
            Object.keys(viewPage.value).forEach(index => {
                viewPage.value[index] = false
            })
        }
    }

    const moveWindowOnTop = () => {
        window.scroll({
            top: 0,
            behavior: 'smooth'
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
    <TopHeader :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" style="padding-bottom: 60px;" />

    <div class="container text-bg-dark">
        <!-- Content -->
        <Licence v-if="viewPage.licence" />
        <PrivacyPolicy v-if="viewPage.privacy_policy" />
    </div>

    <!-- Footer -->
    <BottomFooter :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" />
</template>