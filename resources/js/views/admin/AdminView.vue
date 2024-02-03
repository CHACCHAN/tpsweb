<script setup>
    import { ref, onMounted } from 'vue'
    import { useRouter } from 'vue-router'
    import LoadingComponent from '../../components/loadingcomponent.vue'
    import HomeComponent from '../../components/admin/HomeComponent.vue'
    import ReleaseComponent from '../../components/admin/ReleaseComponent.vue'
    import PotingComponent from '../../components/admin/PotingComponent.vue'

    const isIconImage = location.protocol + '//' + location.host + '/tps-site/images/components/IconImage.png'
    const router = useRouter()
    const isView = ref(false)
    const isUsername = ref()
    const isLoading = ref(true)
    const webMenus = [
        {
            // 0, 1 はホームと更新
            id: 2,
            menuTitle: '投稿',
            menuIcon: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pin-angle-fill" viewBox="0 0 16 16"><path d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a5.927 5.927 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707-.195-.195.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a5.922 5.922 0 0 1 1.013.16l3.134-3.133a2.772 2.772 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146z"/></svg>',
            menuExplanation: 'ブログなどは投稿から発信できます',
        },
        {
            id: 3,
            menuTitle: 'メディア',
            menuIcon: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera2" viewBox="0 0 16 16"><path d="M5 8c0-1.657 2.343-3 4-3V4a4 4 0 0 0-4 4z"/><path d="M12.318 3h2.015C15.253 3 16 3.746 16 4.667v6.666c0 .92-.746 1.667-1.667 1.667h-2.015A5.97 5.97 0 0 1 9 14a5.972 5.972 0 0 1-3.318-1H1.667C.747 13 0 12.254 0 11.333V4.667C0 3.747.746 3 1.667 3H2a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1h.682A5.97 5.97 0 0 1 9 2c1.227 0 2.367.368 3.318 1zM2 4.5a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0zM14 8A5 5 0 1 0 4 8a5 5 0 0 0 10 0z"/></svg>',
            menuExplanation: 'メディアコレクションをスケッチしてサイトへ共有できます',
        }
    ]
    const webSettings = [
        {
            id: 4,
            settingTitle: '外観',
            settingIcon: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-heading" viewBox="0 0 16 16"><path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/><path d="M3 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0-5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-1z"/></svg>',
            settingExplanation: 'ウェブサイトの見た目を構築します',
        },
        {
            id: 5,
            settingTitle: 'ユーザ',
            settingIcon: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16"><path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7Zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216ZM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/></svg>',
            settingExplanation: 'ウェブサイトへ登録したユーザを検証します',
        },
    ]
    const isSelectWeb = ref([true])

    onMounted(() => {
        document.title = 'TPS Webクリエイター'
        history.replaceState('','','/tps-site/admin')
        getLoginData()
    })
    
    const getLoginData = () => {
        fetch('/tps-site/auth/login/check')
        .then((response) => response.json())
        .then(res => {
            if(res.ResponseData && res.Adminstrator) {
                isLoading.value = false
                isView.value = true
                isUsername.value = res.UserData.name
            } else {
                router.push({ name: 'home' })
            }
        })
        .catch(error => {
            console.log(error)
        })
    }

    const selectWeb = (num) => {
        isSelectWeb.value = []
        isSelectWeb.value[num] = true
    }

    const changeTitle = (title) => {
        document.title = 'TPS - ' + title
    }
</script>

<template>
    <LoadingComponent v-if="isLoading" />
    <div v-if="isView">
        <div class="row">
            <!-- 上部ヘッダ -->
            <div class="col-12 bg-dark border-bottom border-secondary py-1">
                <div class="row">
                    <div class="col-4 col-md-9 d-flex align-items-center justify-content-start">
                        <div class="d-flex align-items-center text-decoration-none text-light ms-2">
                            <img class="rounded-circle" :src="isIconImage" width="30px" height="30px" alt="">
                        </div>
                        <div class="d-none d-md-block ms-5">
                            <router-link :to="{ name: 'home' }" class="d-flex align-items-center justify-content-start text-light text-decoration-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill me-1" viewBox="0 0 16 16">
                                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
                                    <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"/>
                                </svg>
                                <div style="font-size: 10px;">ホームへ戻る</div>
                            </router-link>
                        </div>
                    </div>
                    <div class="col-8 col-md-3 d-flex align-items-center justify-content-end">
                        <div class="text-light me-3" style="font-size: 10px;" v-text="'こんにちは、' + isUsername + 'さん'"></div>
                    </div>
                </div>
            </div>
            <!-- モバイル用メニュー -->
            <div class="col-12 d-block d-md-none bg-secondary text-light">
                <button type="button" class="btn border-0 p-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                    </svg>   MENU
                </button>
            </div>
            <!-- PC用左メニュー -->
            <div class="d-none d-md-block col-2 pe-0 bg-dark" style="height: 100vh;">
                <div class="list-group">
                    <!-- ダッシュボード -->
                    <div class="list-group-item text-bg-dark border-0 rounded-0 px-3 p-2 d-flex align-items-center justify-content-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-speedometer" viewBox="0 0 16 16">
                            <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2zM3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
                            <path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z"/>
                        </svg>
                        <div class="ms-3">ダッシュボード</div>
                    </div>
                    <button type="button" class="btn rounded-0 text-light text-start px-5" style="background: rgb(51, 48, 48);" data-bs-toggle="collapse" data-bs-target="#collapseDashBoard" aria-expanded="true" aria-controls="collapseDashBoard">
                        ダッシュボードメニュー
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                        </svg>
                    </button>
                    <div class="collapse show" id="collapseDashBoard">
                        <div class="py-3 mb-3 text-bg-secondary px-5">
                            <a href="#view=ホーム" id="clickItem" class="btn border-0 text-start w-100" :class="{ 'clicked': isSelectWeb[0] }" @click="selectWeb(0); changeTitle('ホーム');">ホーム</a>
                            <a href="#view=更新" id="clickItem" class="btn border-0 text-start w-100" :class="{ 'clicked': isSelectWeb[1] }" @click="selectWeb(1); changeTitle('更新');">更新</a>
                        </div>
                    </div>
                    <!-- 登録メニュー -->
                    <div class="my-3">
                        <div v-for="webMenu in webMenus" :key="webMenu">
                            <a :href="'#view=' + webMenu.menuTitle" id="clickMenuItem" class="list-group-item text-bg-dark border-0 rounded-3 p-1 ps-3 mb-2 mx-5" :class="{ 'clicked': isSelectWeb[webMenu.id] }" @click="selectWeb(webMenu.id); changeTitle(webMenu.menuTitle);">
                                <div v-html="webMenu.menuIcon + '&emsp;' + webMenu.menuTitle"></div>
                            </a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div v-for="webSetting in webSettings" :key="webSetting">
                            <a :href="'#view=' + webSetting.settingTitle" id="clickMenuItem" class="list-group-item text-bg-dark border-0 rounded-3 p-1 ps-3 mb-2 mx-5" :class="{ 'clicked': isSelectWeb[webSetting.id] }" @click="selectWeb(webSetting.id); changeTitle(webSetting.settingTitle);">
                                <div v-html="webSetting.settingIcon + '&emsp;' + webSetting.settingTitle"></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- コントロール画面 -->
            <div class="col-12 col-md-10 pt-3 bg-light">
                <HomeComponent v-if="isSelectWeb[0]" :Menus="webMenus" :Settings="webSettings" />
                <ReleaseComponent v-if="isSelectWeb[1]" />
                <PotingComponent v-if="isSelectWeb[2]" />
            </div>
        </div>
    </div>
</template>

<style scoped>
.clicked {
    background: linear-gradient(-45deg, #bebebe, #918686, #b6d3b4) fixed !important;
    background-size: 800% 800%;
    animation: gradietionAnimation 4s ease infinite;
}
#clickItem:hover {
    background: #3d3d3d;
}
#clickMenuItem {
    transition: 0.5s;
}
#clickMenuItem:hover {
    background: #3d3d3d;
    transform: scale(1.1);
    box-shadow: 0 0 10px white;
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