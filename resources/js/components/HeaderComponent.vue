<script setup>
    import { ref, onMounted } from 'vue'

    const props = defineProps({
        APP_NAME: String,
        LoginFlag: Boolean,
        Adminstrator: Number,
        IconImage: String,
    })
    const isBackGradient = ref(false)
    const isBorderBottom = ref(false)
    const isPositionLeft = ref(0)
    const isD_none = ref(true)

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

        document.addEventListener('click', (e) => {
            if(!e.target.closest('#menuItem') && !e.target.closest('#listMenu')) {
                isD_none.value = true
            } else {
                isPositionLeft.value = (e.pageX - 70) + 'px'
                isD_none.value = false
            }
        })
    })
</script>

<template>
    <div>
        <div class="position-relative position-fixed w-100 z-3">
            <header class="position-absolute w-100">
                <div class="container-fluid text-light border-2 py-2" :class="{ 'colorBackGradient': isBackGradient, 'border-bottom': isBorderBottom, 'border-secondary': isBorderBottom }">
                    <div class="row">
                        <div class="col-6 col-md-10 d-flex align-items-center justify-content-start">
                            <button type="button" id="clickItem" class="p-2 border-0 rounded-circle" data-bs-toggle="offcanvas" data-bs-target="#SideCanvas" aria-controls="SideCanvas">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                                </svg>
                            </button>
                            <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="SideCanvas" aria-labelledby="SideCanvasLabel" style="width: 300px;">
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
                                <div class="offcanvas-body">

                                </div>
                            </div>
                            <router-link :to="{ name: 'home' }" class="d-flex align-items-center text-decoration-none text-light ms-2">
                                <img class="rounded-circle" :src="IconImage" width="30px" height="30px" alt="">
                                <div class="fs-3 fw-bold ms-1" v-text="APP_NAME"></div>
                            </router-link>
                            <div id="menuItem" class="d-none d-md-block ms-5">
                                <button type="button" id="clickItem" class="btn border-0 rounded-pill ms-2" :class="{ 'clicked': !isD_none }">ホーム</button>
                            </div>
                        </div>
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
                                        
                                        <a href="/tps-site/auth/logout" id="clickItem" class="btn border-0 text-light rounded-pill p-1 w-100">ログアウト</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </header>


            <!-- PC用　上部メニュー -->
            <div class="position-fixed w-100" :class="{ 'd-none': isD_none }" style="top: 65px; left: 0;">
                <div class="position-relative">
                    <div class="position-absolute w-100 d-flex align-items-center justify-content-center">
                        <div id="listMenu" class="bg-dark p-2 rounded-3 shadow-lg" style="width: 65%;">
                            <div class="row">
                                <!-- トップメニュー -->
                                <div class="col-3 text-light border-end border-secondary">
                                    <div class="rounded-3 p-3">
                                        <div class="h5 fw-bold border-2 border-bottom pb-4 mb-4">TPS Portal</div>
                                        <router-link to="" id="clickItem" class="btn border-0 text-primary text-decoration-none rounded-pill">ホーム</router-link>
                                    </div>
                                </div>
                                <!-- リストメニュー -->
                                <div class="col-3">
                                    <div class="rounded-3 p-3 bg-light">
                                        <div class="h5 fw-bold border-2 border-bottom pb-4 mb-4">基本メニュー</div>
                                        <router-link to="" class="btn btn-primary rounded-pill">ホーム</router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- モバイル用 下部メニュー -->
            <div class="position-fixed bottom-0 d-block d-md-none w-100">
                <div class="position-relative w-100">
                    <div class="bg-dark text-light rounded-pill m-2 p-2 px-4">
                        <div class="mx-4">
                            <button type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
                                    <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5Z"/>
                                </svg>
                                <div style="font-size:8px;">HOME</div>
                            </button>
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