<script setup>
    import { ref, onMounted } from 'vue'
    import TopHeader from '../../components/HeaderComponent.vue'
    import WhatTPS from '../../components/home/WhatTPSComponent.vue'
    import PartyImage from '../../components/home/PartyImageComponent.vue'

    const backVideo = [
        {
            'videoImage': 'images/icon.png',
            'videoMp4': 'videos/BackVideo.mp4',
        }
    ]
    const menuLists = [
        {
            'id': 0,
            'title': 'TPSとは',
            'flag': true,
        },
        {
            'id': 1,
            'title': 'オフ会写真集',
            'flag': false,
        },
        {
            'id': 2,
            'title': '戦車写真集',
            'flag': false,
        },
        {
            'id': 3,
            'title': 'お問い合わせ',
            'flag': false,
        },
    ]
    const showComponentFlag = ref([])
    const showComponentText = ref()

    const isScrolledOpacity = ref(1)
    const isLoadedWidth = ref()
    const isTitleSize = ref()

    onMounted(() => {
        window.addEventListener('scroll', handleScroll)
        setFlag()
        changeResponsive()
        window.addEventListener('resize', changeResponsive)

        setInterval(() => {
            document.getElementById('arrowMove').animate({
                top: ["15px", "50px", "15px"],
                opacity: [0.1, 1, 0.3],
            }, 2000)
        }, 2000);
    })

    const handleScroll = () => {
        isScrolledOpacity.value = 1 - (window.scrollY * 0.0012)
    }
    const setFlag = () => {
        menuLists.forEach((menuList) => {
            showComponentFlag.value[menuList.id] = menuList.flag

            if(menuList.flag) {
                showComponentText.value = menuList.title
            }
        })
    }
    const changeResponsive = () => {
        if(window.screen.width <= 768) {
            isLoadedWidth.value = '100%'
            isTitleSize.value = '40px'
        } else {
            isLoadedWidth.value = '140px'
            isTitleSize.value = '70px'
        }
    }

    function changeComponent(e) {
        menuLists.forEach((menuList) => {
            if(menuList.id === e.id) {
                showComponentFlag.value[menuList.id] = true
                showComponentText.value = menuList.title
            } else {
                showComponentFlag.value[menuList.id] = false
            }
        })
    }
</script>

<template>
    <TopHeader />
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
    <div class="bg-dark text-light pb-1" style="box-shadow: 0 -8px #212529;">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <div class="navbar-brand text-center text-light fw-bold" v-text="showComponentText" style="width: 150px"></div>
                <div class="vr mx-3 d-none d-md-block"></div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#NavbarMainMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-three-dots text-light border-0" viewBox="0 0 16 16">
                        <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                    </svg>
                </button>
                <div class="collapse navbar-collapse" id="NavbarMainMenu">
                    <ul class="navbar-nav">
                        <li class="nav-item" v-for="menuList in menuLists" :key="menuList.id">
                            <button type="button" @click="changeComponent(menuList)" id="HoverAnimate" class="btn text-light rounded-0 mt-1 me-2" :style="{ 'width': isLoadedWidth }">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div :class="{ selected: showComponentFlag[menuList.id] }" v-text="menuList.title"></div>
                                    <div class="ms-1" v-if="showComponentFlag[menuList.id]">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                                            <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                                        </svg>
                                    </div>
                                </div>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container">
        <div class="text-end">
            更新日 : 2023/12/31
        </div>
    </div>
    <!-- Component -->
    <WhatTPS class="mt-2" v-if="showComponentFlag[0]" />
    <PartyImage class="mt-2" v-if="showComponentFlag[1]" />
</template>