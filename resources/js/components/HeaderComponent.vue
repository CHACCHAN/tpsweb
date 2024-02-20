<script setup>
    import { ref, onMounted } from 'vue'

    const props = defineProps({
        APP_NAME: String,
        LoginFlag: Boolean,
        Adminstrator: Number,
        IconImage: String,
    })
    const isLoading = ref(true)
    const isBackGradient = ref(false)
    const isBorderBottom = ref(false)
    const movedToTheBottom = ref(false)
    const isPositionLeft = ref(0)
    const isClickedHome = ref(true)
    const isClickedImage = ref(true)
    const isClickedNews = ref(true)
    const isPostDatas = ref([])
    const isPostCategorys = ref([])
    const isMediaGroups = ref([])
    const isMedias = ref([])
    const nowYear = ref(null)

    onMounted(() => {
        window.addEventListener('scroll', () => {
            if(window.scrollY > 0) {
                isBackGradient.value = true
                isBorderBottom.value = true
            } else {
                isBackGradient.value = false
                isBorderBottom.value = false
            }
        })

        // Fixedのサイズ調整、クリックイベント
        document.addEventListener('click', (e) => {
            if(!e.target.closest('#menuItem') && !e.target.closest('#listMenu')) {
                isPositionLeft.value = (e.pageX - 70) + 'px'
                isClickedHome.value = true
                isClickedImage.value = true
                isClickedNews.value = true
            }
        })
        getDatas()
        window.addEventListener('scroll', () => {
            const bodyHeight = document.body.clientHeight
            const windowHeight = window.innerHeight
            const bottomPoint = bodyHeight - windowHeight
            const currentPos = window.pageYOffset
            if (bottomPoint <= currentPos) {
                movedToTheBottom.value = true
            } else {
                movedToTheBottom.value = false
            }
        })
        
        getNowDate()
    })
    
    const getNowDate = () => {
        let date = new Date()
        nowYear.value = date.getFullYear()
    }
    
    const getDatas = async() => {
        const getPostDataPromise = getPostData()
        const getPostCategoryPromise = getPostCategory()
        const getMediaGroupPromise = getMediaGroup()
        const getMediaPromise = getMedia()

        await Promise.all([getPostDataPromise, getPostCategoryPromise, getMediaGroupPromise, getMediaPromise])
        
        isLoading.value = false
    }

    // ポスト
    const getPostData = () => {
        return new Promise((resolve, reject) => {
            fetch('/tps-site/get/postdata')
            .then((response) => response.json())
            .then(res => {
                isPostDatas.value = res.responseData
                resolve()
            })
            .catch(error => {
                console.log(error)
                reject(error)
            })
        })
    }

    const getPostCategory = () => {
        return new Promise((resolve, reject) => {
            fetch('/tps-site/get/postcategory')
            .then((response) => response.json())
            .then(res => {
                isPostCategorys.value = res.responseData
                resolve()
            })
            .catch(error => {
                console.log(error)
                reject(error)
            })
        })
    }

    // メディア
    const getMediaGroup = () => {
        return new Promise((resolve, reject) => {
            fetch('/tps-site/get/mediagroup')
            .then((response) => response.json())
            .then(res => {
                isMediaGroups.value = res.responseData
                resolve()
            })
            .catch(error => {
                console.log(error)
                reject(error)
            })
        })
    }

    const getMedia = () => {
        return new Promise((resolve, reject) => {
            fetch('/tps-site/get/media')
            .then((response) => response.json())
            .then(res => {
                isMedias.value = res.responseData
                resolve()
            })
            .catch(error => {
                console.log(error)
                reject(error)
            })
        })
    }
</script>

<template>
    <div>
        <div class="position-relative position-fixed w-100 z-2">
            <!-- ヘッダー -->
            <header class="position-absolute w-100">
                <div class="container-fluid text-light border-2 py-2" :class="{ 'colorBackGradient': isBackGradient, 'border-bottom': isBorderBottom, 'border-secondary': isBorderBottom }">
                    <div class="row">
                        <!-- 左サイド -->
                        <div class="col-6 col-md-10 d-flex align-items-center justify-content-start">
                            <!-- 3本バーキャンバス -->
                            <button type="button" id="clickItem" class="p-2 border-0 rounded-circle" data-bs-toggle="offcanvas" data-bs-target="#SideCanvas" aria-controls="SideCanvas">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                                </svg>
                            </button>
                            <!-- オフキャンバス -->
                            <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="SideCanvas" aria-labelledby="SideCanvasLabel" style="width: 200px;">
                                <div class="offcanvas-header justify-content-start p-2">
                                    <button type="button" id="clickItem" class="p-2 border-0 rounded-circle" data-bs-dismiss="offcanvas" aria-label="Close">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                                        </svg>
                                    </button>
                                    <div class="offcanvas-title" id="SideCanvasLabel">
                                        <router-link :to="{ name: 'home' }" class="d-flex align-items-center text-decoration-none text-light ms-2">
                                            <img class="rounded-circle" :src="IconImage" width="30px" height="30px" alt="">
                                            <div class="fs-3 fw-bold ms-1" v-text="APP_NAME"></div>
                                        </router-link>
                                    </div>
                                </div>
                                <div class="offcanvas-body p-2">
                                    <!-- 基本メニュー -->
                                    <router-link :to="{ name: 'home' }" id="clickItem" class="btn border-0 d-flex align-items-center justify-content-start text-light w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                                        </svg>
                                        <div class="fw-bold ms-3">ホーム</div>
                                    </router-link>
                                    <router-link :to="{ name: 'image' }" id="clickItem" class="btn border-0 d-flex align-items-center justify-content-start text-light w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-collection" viewBox="0 0 16 16">
                                            <path d="M2.5 3.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11zm2-2a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6v7zm1.5.5A.5.5 0 0 1 1 13V6a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13z"/>
                                        </svg>
                                        <div class="fw-bold ms-3">メディア</div>
                                    </router-link>
                                    <router-link :to="{ name: 'news' }" id="clickItem" class="btn border-0 d-flex align-items-center justify-content-start text-light w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-newspaper" viewBox="0 0 16 16">
                                            <path d="M0 2.5A1.5 1.5 0 0 1 1.5 1h11A1.5 1.5 0 0 1 14 2.5v10.528c0 .3-.05.654-.238.972h.738a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 1 1 0v9a1.5 1.5 0 0 1-1.5 1.5H1.497A1.497 1.497 0 0 1 0 13.5v-11zM12 14c.37 0 .654-.211.853-.441.092-.106.147-.279.147-.531V2.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5v11c0 .278.223.5.497.5H12z"/>
                                            <path d="M2 3h10v2H2V3zm0 3h4v3H2V6zm0 4h4v1H2v-1zm0 2h4v1H2v-1zm5-6h2v1H7V6zm3 0h2v1h-2V6zM7 8h2v1H7V8zm3 0h2v1h-2V8zm-3 2h2v1H7v-1zm3 0h2v1h-2v-1zm-3 2h2v1H7v-1zm3 0h2v1h-2v-1z"/>
                                        </svg>
                                        <div class="fw-bold ms-3">ニュース</div>
                                    </router-link>
                                    <hr>
                                    <!-- その他 -->
                                    <a href="https://twitter.com/wotb_fhoto_club?s=11&t=Ygi1_rZPT9AE-XmPDkbmRQ" id="clickItem" class="btn border-0 d-flex align-items-center justify-content-start text-light w-100" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                                            <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
                                        </svg>
                                        <div class="fw-bold ms-3">X(Twitter)</div>
                                    </a>
                                    <a href="https://line.me/ti/g2/qtOl2oNQTbnLcB-3dZq2GX_ajumvHS3HDl-OIw?utm_source=invitation&utm_medium=link_copy&utm_campaign=default" id="clickItem" class="btn border-0 d-flex align-items-center justify-content-start text-light w-100" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-line" viewBox="0 0 16 16">
                                            <path d="M8 0c4.411 0 8 2.912 8 6.492 0 1.433-.555 2.723-1.715 3.994-1.678 1.932-5.431 4.285-6.285 4.645-.83.35-.734-.197-.696-.413l.003-.018.114-.685c.027-.204.055-.521-.026-.723-.09-.223-.444-.339-.704-.395C2.846 12.39 0 9.701 0 6.492 0 2.912 3.59 0 8 0ZM5.022 7.686H3.497V4.918a.156.156 0 0 0-.155-.156H2.78a.156.156 0 0 0-.156.156v3.486c0 .041.017.08.044.107v.001l.002.002.002.002a.154.154 0 0 0 .108.043h2.242c.086 0 .155-.07.155-.156v-.56a.156.156 0 0 0-.155-.157Zm.791-2.924a.156.156 0 0 0-.156.156v3.486c0 .086.07.155.156.155h.562c.086 0 .155-.07.155-.155V4.918a.156.156 0 0 0-.155-.156h-.562Zm3.863 0a.156.156 0 0 0-.156.156v2.07L7.923 4.832a.17.17 0 0 0-.013-.015v-.001a.139.139 0 0 0-.01-.01l-.003-.003a.092.092 0 0 0-.011-.009h-.001L7.88 4.79l-.003-.002a.029.029 0 0 0-.005-.003l-.008-.005h-.002l-.003-.002-.01-.004-.004-.002a.093.093 0 0 0-.01-.003h-.002l-.003-.001-.009-.002h-.006l-.003-.001h-.004l-.002-.001h-.574a.156.156 0 0 0-.156.155v3.486c0 .086.07.155.156.155h.56c.087 0 .157-.07.157-.155v-2.07l1.6 2.16a.154.154 0 0 0 .039.038l.001.001.01.006.004.002a.066.066 0 0 0 .008.004l.007.003.005.002a.168.168 0 0 0 .01.003h.003a.155.155 0 0 0 .04.006h.56c.087 0 .157-.07.157-.155V4.918a.156.156 0 0 0-.156-.156h-.561Zm3.815.717v-.56a.156.156 0 0 0-.155-.157h-2.242a.155.155 0 0 0-.108.044h-.001l-.001.002-.002.003a.155.155 0 0 0-.044.107v3.486c0 .041.017.08.044.107l.002.003.002.002a.155.155 0 0 0 .108.043h2.242c.086 0 .155-.07.155-.156v-.56a.156.156 0 0 0-.155-.157H11.81v-.589h1.525c.086 0 .155-.07.155-.156v-.56a.156.156 0 0 0-.155-.157H11.81v-.589h1.525c.086 0 .155-.07.155-.156Z"/>
                                        </svg>
                                        <div class="fw-bold ms-3">オープンチャット</div>
                                    </a>
                                    <a href="https://discord.gg/8BWqNzDZP2" id="clickItem" class="btn border-0 d-flex align-items-center justify-content-start text-light w-100" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-discord" viewBox="0 0 16 16">
                                            <path d="M13.545 2.907a13.227 13.227 0 0 0-3.257-1.011.05.05 0 0 0-.052.025c-.141.25-.297.577-.406.833a12.19 12.19 0 0 0-3.658 0 8.258 8.258 0 0 0-.412-.833.051.051 0 0 0-.052-.025c-1.125.194-2.22.534-3.257 1.011a.041.041 0 0 0-.021.018C.356 6.024-.213 9.047.066 12.032c.001.014.01.028.021.037a13.276 13.276 0 0 0 3.995 2.02.05.05 0 0 0 .056-.019c.308-.42.582-.863.818-1.329a.05.05 0 0 0-.01-.059.051.051 0 0 0-.018-.011 8.875 8.875 0 0 1-1.248-.595.05.05 0 0 1-.02-.066.051.051 0 0 1 .015-.019c.084-.063.168-.129.248-.195a.05.05 0 0 1 .051-.007c2.619 1.196 5.454 1.196 8.041 0a.052.052 0 0 1 .053.007c.08.066.164.132.248.195a.051.051 0 0 1-.004.085 8.254 8.254 0 0 1-1.249.594.05.05 0 0 0-.03.03.052.052 0 0 0 .003.041c.24.465.515.909.817 1.329a.05.05 0 0 0 .056.019 13.235 13.235 0 0 0 4.001-2.02.049.049 0 0 0 .021-.037c.334-3.451-.559-6.449-2.366-9.106a.034.034 0 0 0-.02-.019Zm-8.198 7.307c-.789 0-1.438-.724-1.438-1.612 0-.889.637-1.613 1.438-1.613.807 0 1.45.73 1.438 1.613 0 .888-.637 1.612-1.438 1.612Zm5.316 0c-.788 0-1.438-.724-1.438-1.612 0-.889.637-1.613 1.438-1.613.807 0 1.451.73 1.438 1.613 0 .888-.631 1.612-1.438 1.612Z"/>
                                        </svg>
                                        <div class="fw-bold ms-3">Discord</div>
                                    </a>
                                    <router-link :to="{ name: 'contact' }" id="clickItem" class="btn border-0 d-flex align-items-center justify-content-start text-light w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
                                            <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                            <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9.06 9.06 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.437 10.437 0 0 1-.524 2.318l-.003.011a10.722 10.722 0 0 1-.244.637c-.079.186.074.394.273.362a21.673 21.673 0 0 0 .693-.125zm.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6c0 3.193-3.004 6-7 6a8.06 8.06 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a10.97 10.97 0 0 0 .398-2z"/>
                                        </svg>
                                        <div class="fw-bold ms-3">問い合わせ</div>
                                    </router-link>
                                    <hr>
                                    <router-link :to="{ name: 'licence' }" class="text-decoration-none text-secondary">利用規約</router-link><br>
                                    <router-link :to="{ name: 'privacy_policy' }" class="text-decoration-none text-secondary">プライバシーポリシー</router-link>
                                    <div class="text-secondary mt-3">© {{ nowYear + ' ' +APP_NAME }}</div>
                                </div>
                            </div>

                            <!-- ロゴ -->
                            <router-link :to="{ name: 'home' }" class="d-flex align-items-center text-decoration-none text-light ms-2">
                                <img class="rounded-circle" :src="IconImage" width="30px" height="30px" style="box-shadow: 0 0 5px black;">
                                <div class="fs-3 fw-bold ms-1" v-text="APP_NAME" style="text-shadow: 0 0 5px black;"></div>
                            </router-link>

                            <!-- ホーム -->
                            <div id="menuItem" class="d-none d-md-block ms-5">
                                <button type="button" id="clickItem" class="btn border-0 rounded-pill ms-2" :class="{ 'clicked': !isClickedHome }" 
                                    @click="isClickedHome = !isClickedHome, isClickedImage = true, isClickedNews = true" style="text-shadow: 0 0 5px black;"
                                    >HOME
                                </button>
                            </div>
                            <div id="menuItem" class="d-none d-md-block">
                                <button type="button" id="clickItem" class="btn border-0 rounded-pill ms-2" :class="{ 'clicked': !isClickedImage }" 
                                    @click="isClickedHome = true, isClickedImage = !isClickedImage, isClickedNews = true" style="text-shadow: 0 0 5px black;"
                                    >IMAGE
                                </button>
                            </div>
                            <div id="menuItem" class="d-none d-md-block">
                                <button type="button" id="clickItem" class="btn border-0 rounded-pill ms-2" :class="{ 'clicked': !isClickedNews }" 
                                    @click="isClickedHome = true, isClickedImage = true, isClickedNews = !isClickedNews" style="text-shadow: 0 0 5px black;"
                                    >NEWS
                                </button>
                            </div>
                            <div class="vr ms-2" style="transform: rotate(20deg);"></div>
                            <div id="menuItem" class="d-none d-md-block">
                                <router-link :to="{ name: 'contact' }" id="clickItemHyper" class="btn border-0 rounded-pill ms-2 text-light" 
                                    style="text-shadow: 0 0 5px black;"
                                    >CONTACT
                                </router-link>
                            </div>
                            <div id="menuItem" class="d-none d-md-block">
                                <a 
                                    href="https://twitter.com/wotb_fhoto_club?s=11&t=Ygi1_rZPT9AE-XmPDkbmRQ" 
                                    id="clickItemHyper" 
                                    class="btn border-0 text-light rounded-pill ms-2" 
                                    style="text-shadow: 0 0 5px black;"
                                    target="_blank"
                                    >X (TWITTER)
                                </a>
                            </div>
                        </div>
                        <!-- 右サイド -->
                        <div class="col-6 col-md-2 d-flex align-items-center justify-content-end">
                            <router-link :to="{ name: 'login' }" class="btn btn-light rounded-pill" :class="{ 'btn-dark': !isBackGradient }" v-if="!LoginFlag">ログイン</router-link>
                            <!-- 管理者のみ表示 -->
                            <router-link :to="{ name: 'admin' }" id="clickItem" class="btn text-light border-0 rounded-circle p-2 me-1" :class="{ 'btn-dark': !isBackGradient }" v-if="Adminstrator">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                                    <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434l.071-.286zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5zm0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78h4.723zM5.048 3.967c-.03.021-.058.043-.087.065l.087-.065zm-.431.355A4.984 4.984 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8 4.617 4.322zm.344 7.646.087.065-.087-.065z"/>
                                </svg>
                            </router-link>
                            <!-- アカウント -->
                            <div class="dropdown" v-if="LoginFlag">
                                <button type="button" id="clickItem" class="btn text-light border-0 rounded-circle p-2 me-1" :class="{ 'btn-dark': !isBackGradient }" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                                    </svg>
                                </button>
                                <ul class="dropdown-menu text-bg-dark p-0">
                                    <li class="dropdown-item bg-dark py-2">
                                        <router-link :to="{ name: 'profile' }" id="clickItem" class="btn border-0 text-light rounded-pill p-1 w-100">プロフィール</router-link><br>
                                        <a href="/tps-site/auth/logout" id="clickItem" class="btn border-0 text-light rounded-pill p-1 w-100">ログアウト</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </header>



            <!-- PC用 Fixed HOME -->
            <div class="position-fixed w-100" v-if="!isClickedHome" style="top: 65px; left: 0;">
                <div class="position-relative">
                    <div class="position-absolute w-100 d-flex align-items-center justify-content-center">
                        <div id="listMenu" class="bg-dark p-2 rounded-3 shadow-lg" style="width: 65%;">
                            <div class="row">
                                <!-- トップメニュー -->
                                <div class="col-3 text-light border-end border-secondary">
                                    <div class="rounded-3 p-3">
                                        <div class="h5 fw-bold border-2 border-bottom pb-4 mb-4 text-primary">HOME</div>
                                        <router-link :to="{ name: 'home' }" id="clickItem" class="btn border-0 text-primary text-decoration-none rounded-pill">ホーム</router-link><br>
                                        <router-link :to="{ name: 'image' }" id="clickItem" class="btn border-0 text-primary text-decoration-none rounded-pill">メディア</router-link><br>
                                        <router-link :to="{ name: 'news' }" id="clickItem" class="btn border-0 text-primary text-decoration-none rounded-pill">ニュース</router-link>
                                    </div>
                                </div>
                                <!-- 自由投稿メニュー -->
                                <div class="col-9">
                                    <div class="d-flex align-items-center justify-content-center h-100" v-if="!isPostCategorys[0]">
                                        <div class="text-bg-dark">
                                            <div class="h1 text-secondary m-0 fw-bold">
                                                TPS
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mt-5" v-if="isLoading">
                                            <div class="text-center text-light">
                                                <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 mb-2" v-for="isPostCategory in isPostCategorys" :key="isPostCategory.id">
                                            <div class="rounded-3 p-3 bg-light" v-if="!isLoading">
                                                <div class="h5 fw-bold border-2 border-bottom pb-4 mb-4 text-truncate" v-text="isPostCategory.category"></div>
                                                <ul v-for="isPostData in isPostDatas" :key="isPostData.id" class="m-0">
                                                    <li v-if="isPostCategory.id == isPostData.category_id && isPostData.public">
                                                        <router-link
                                                            :to="'/post/' + isPostCategory.category + '/' + isPostData.title"
                                                            class="btn border-0 text-primary fs-5 p-0 ps-2 text-decoration-underline text-truncate">
                                                            {{ isPostData.title }}
                                                        </router-link>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PC用 Fixed Image -->
            <div class="position-fixed w-100" v-if="!isClickedImage" style="top: 65px; left: 0;">
                <div class="position-relative">
                    <div class="position-absolute w-100 d-flex align-items-center justify-content-center">
                        <div id="listMenu" class="bg-dark p-2 rounded-3 shadow-lg" style="width: 65%;">
                            <div class="row">
                                <!-- トップメニュー -->
                                <div class="col-3 text-light border-end border-secondary">
                                    <div class="rounded-3 p-3">
                                        <div class="h5 fw-bold border-2 border-bottom pb-4 mb-4 text-primary">IMAGE</div>
                                        <router-link :to="{ name: 'home' }" id="clickItem" class="btn border-0 text-primary text-decoration-none rounded-pill">ホーム</router-link><br>
                                        <router-link :to="{ name: 'image' }" id="clickItem" class="btn border-0 text-primary text-decoration-none rounded-pill">メディア</router-link><br>
                                        <router-link :to="{ name: 'news' }" id="clickItem" class="btn border-0 text-primary text-decoration-none rounded-pill">ニュース</router-link>
                                    </div>
                                </div>
                                <!-- 投稿画像一覧 -->
                                <div class="col-9">
                                    <div class="d-flex align-items-center justify-content-center h-100" v-if="!isMediaGroups[0]">
                                        <div class="text-bg-dark">
                                            <div class="h1 text-secondary m-0 fw-bold">
                                                TPS
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mt-5" v-if="isLoading">
                                            <div class="text-center text-light">
                                                <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 mb-2" v-for="isMediaGroup in isMediaGroups" :key="isMediaGroup.id">
                                            <div class="rounded-3 p-3 bg-light" v-if="!isLoading">
                                                <div class="h5 fw-bold border-2 border-bottom pb-4 mb-4 text-truncate" v-text="isMediaGroup.name"></div>
                                                <ul v-for="isMedia in isMedias" :key="isMedia.id" class="m-0">
                                                    <li v-if="isMediaGroup.id == isMedia.media_group_id">
                                                        <router-link
                                                            :to="'/image/' + isMediaGroup.name"
                                                            class="btn border-0 text-primary fs-5 p-0 ps-2 text-decoration-underline text-truncate">
                                                            {{ isMedia.name }}
                                                        </router-link>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PC用 Fixed News -->
            <div class="position-fixed w-100" v-if="!isClickedNews" style="top: 65px; left: 0;">
                <div class="position-relative">
                    <div class="position-absolute w-100 d-flex align-items-center justify-content-center">
                        <div id="listMenu" class="bg-dark p-2 rounded-3 shadow-lg" style="width: 65%;">
                            <div class="row">
                                <!-- トップメニュー -->
                                <div class="col-3 text-light border-end border-secondary">
                                    <div class="rounded-3 p-3">
                                        <div class="h5 fw-bold border-2 border-bottom pb-4 mb-4 text-primary">NEWS</div>
                                        <router-link :to="{ name: 'home' }" id="clickItem" class="btn border-0 text-primary text-decoration-none rounded-pill">ホーム</router-link><br>
                                        <router-link :to="{ name: 'image' }" id="clickItem" class="btn border-0 text-primary text-decoration-none rounded-pill">メディア</router-link><br>
                                        <router-link :to="{ name: 'news' }" id="clickItem" class="btn border-0 text-primary text-decoration-none rounded-pill">ニュース</router-link>
                                    </div>
                                </div>
                                <!-- 重大ニュース等 -->
                                <div class="col-9">
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <div class="text-bg-dark">
                                            <div class="h1 text-secondary m-0 fw-bold">
                                                TPS
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- モバイル用 下部メニュー -->
            <div class="position-fixed bottom-0 d-block d-md-none z-3 w-100" v-if="isBackGradient && !movedToTheBottom">
                <div class="position-relative w-100">
                    <div class="bg-dark text-light rounded-pill border m-2 p-2 px-4">
                        <div class="mx-4">
                            <!-- メニュー -->
                            <router-link :to="{ name: 'home' }" class="btn border-0 text-light p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
                                    <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5Z"/>
                                </svg>
                                <div style="font-size:8px;">HOME</div>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.clicked {
    background: #3d3d3d;
}
#clickItem:hover {
    background: #3d3d3d;
}
#clickItemHyper:hover {
    background: #d84f4f;
}
#clickMenuItem:hover {
    background: #3d3d3d;
    transform: scale(1.1);
    box-shadow: 0 0 10px white;
}
#listMenu {
    transition: 1s;
}
#listMenu:hover {
    transform: scale(1.05);
    transition: 0.5s;
}
.colorBackGradient { 
    background: linear-gradient(-45deg, #000000, #2e2e2e, #53534f) fixed;
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