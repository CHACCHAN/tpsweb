<script setup>
    import { ref, onMounted, onUnmounted } from 'vue'
    import gsap from 'gsap'
    import TopHeader from '../../components/HeaderComponent.vue'
    import BottomFooter from '../../components/FooterComponent.vue'

    const isIconImage = location.protocol + '//' + location.host + '/tps-site/images/components/IconImage.png'
    const backVideo = [
        {
            'videoImage': 'images/icon.png',
            'videoMp4': 'videos/BackVideo.mp4',
        }
    ]
    const isScrolledOpacity = ref(1)
    const isTitleSize = ref()
    const isAPP_NAME = ref()
    const isLoginFlag = ref()
    const isAdminstrator = ref()

    // GSAP
    const tween = (el) => {
        console.log(el)
        gsap.set(el, {
            duration: 0.5, // アニメーションの時間
            paused: true, // 勝手にアニメーションが始まらないように
            ease: "power2.out", // イージング
            // アニメーションの終点
            top: 0,
        })
    }

    onMounted(() => {
        getAPP_NAME()
        getLoginData()
        document.title = 'ホーム'
        window.addEventListener('scroll', handleScroll)
        changeResponsive()
        window.addEventListener('resize', changeResponsive)
    })

    onUnmounted(() => {
        handleScroll()
        changeResponsive()
    })

    const handleScroll = () => {
        isScrolledOpacity.value = 1 - (window.scrollY * 0.0012)
    }
    const changeResponsive = () => {
        if(window.screen.width <= 768) {
            isTitleSize.value = '40px'
        } else {
            isTitleSize.value = '70px'
        }
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
    <div class="position-relative z-n1" :style="{ opacity: isScrolledOpacity }">
        <video :poster="backVideo[0].videoImage" width="100%" muted loop autoplay playsinline oncontextmenu="return false;">
            <source :src="backVideo[0].videoMp4" type="video/mp4" />
        </video>
        <div class="position-absolute w-100" style="top: 35%;">
            <div class="text-center text-light">
                <h1 id="Text" class="fw-bold" style="font-weight: 40px;" :style="{ 'font-size': isTitleSize }">Photo club on TPS</h1>
                <h5>World of Tanks Blitz photo club</h5>
            </div>
        </div>
        <div class="position-absolute position-relative w-100" style="top: 60%;">
            <div class="text-center text-light d-none d-md-block">
                <p>スクロール</p>
                <div id="arrowMove" class="position-absolute w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <!-- Component -->
    <div class="card card-body" @click="tween">Click!</div>
    
    <!-- Footer -->
    <BottomFooter :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" />
</template>

<style scoped>
    #arrowMove {
        animation-name: UpDown;
        animation-duration: 2s;
        animation-iteration-count:infinite;
    }
    @keyframes UpDown{
        0% {
            top: 20px;
        }
        50% {
            top: 70px;
        }
        100% {
            top: 20px;
        }
    }
</style>