<script setup>
    import { ref, onMounted, onUnmounted } from 'vue'
    import { useRouter } from 'vue-router'
    import Cookies from 'js-cookie'
    import MailAddressComponent from './MailAddressComponent.vue'
    import PasswordComponent from './PasswordComponent.vue'
    import NamesComponent from './NamesComponent.vue'
    import AvatarComponentVue from './AvatarComponent.vue'
    import DeleteComponent from './DeleteComponent.vue'
    import Cropper from 'cropperjs'

    const router = useRouter()
    const userLoginData = ref(null)
    const infoIcon = ref(`
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-caret-right" viewBox="0 0 16 16">
            <path d="M6 12.796V3.204L11.481 8 6 12.796zm.659.753 5.48-4.796a1 1 0 0 0 0-1.506L6.66 2.451C6.011 1.885 5 2.345 5 3.204v9.592a1 1 0 0 0 1.659.753z"/>
        </svg>
    `)
    const infoCards = ref([
        {
            id: 1,
            flag: false,
            title: 'アバター',
            comment: 'アバターを設定する',
            icon: infoIcon.value,
        },
        {
            id: 2,
            flag: false,
            title: '姓',
            comment: null,
            icon: infoIcon.value,
        },
        {
            id: 3,
            flag: false,
            title: '名',
            comment: null,
            icon: infoIcon.value,
        },
        {
            id: 4,
            flag: false,
            title: 'ニックネーム',
            comment: null,
            icon: infoIcon.value,
        },
    ])
    const controlCards = ref([
        {
            id: 1,
            flag: false,
            title: 'メールアドレス',
            comment: 'TPSアカウントのメールアドレスを変更します。',
            name: 'メールアドレスを変更する',
            image: '/tps-site/images/components/MailAddress.jpg',
        },
        {
            id: 2,
            flag: false,
            title: 'パスワード',
            comment: 'TPSアカウントのパスワードを変更します。',
            name: 'パスワードを変更する',
            image: '/tps-site/images/components/Password.jpg',
        },
        {
            id: 3,
            flag: false,
            title: 'アカウント削除',
            comment: 'TPSアカウントを削除します。',
            name: '手続きをする',
            image: '/tps-site/images/components/AccountDelete.jpg',
        },
    ])

    onMounted(() => {
        document.title = 'プロフィール'
        getLoginData()
    })

    onUnmounted(() => {
        Cookies.remove('one_time_flag')
    })

    const getLoginData = () => {
        userLoginData.value = new Array()

        fetch('/tps-site/auth/login/check')
        .then((response) => response.json())
        .then(res => {
            if(res.ResponseData) {
                userLoginData.value = res.UserData
                infoCards.value[1].comment = userLoginData.value.first_name
                infoCards.value[2].comment = userLoginData.value.last_name
                infoCards.value[3].comment = userLoginData.value.name
            } else {
                router.push({ name: 'home' })
            }
        })
        .catch(error => {
            console.log(error)
        })
    }

    const setViewPage = (id) => {
        for(let i = 0; i < controlCards.value.length; i++) {
            if(controlCards.value[i].id === id) {
                controlCards.value[i].flag = true
            } else {
                controlCards.value[i].flag = false
            }
        }
    }

    const setViewInfo = (id) => {
        for(let i = 0; i < infoCards.value.length; i++) {
            if(infoCards.value[i].id === id) {
                infoCards.value[i].flag = true
            } else {
                infoCards.value[i].flag = false
            }
        }
    }
</script>

<template>
    <div class="container" v-if="userLoginData">
        <div class="card card-body text-bg-dark border-0 mt-3">
            <!-- メニュー -->
            <div class="d-flex align-items-center justify-content-start mb-3">
                <router-link :to="{ name: 'home' }" id="clickItem" class="text-decoration-none text-light p-2 py-1 rounded-pill">ホーム</router-link>
                <div class="vr mx-1"></div>
                <a href="/tps-site/auth/logout" id="clickItem" class="text-decoration-none text-light p-2 py-1 rounded-pill">ログアウト</a>
            </div>
            <!-- トップ -->
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <img :src="'/tps-site/storage/user/avatar/' + userLoginData.avatar" class="rounded-circle shadow" width="150px" v-if="userLoginData.avatar" />
                        <img :src="'/tps-site/images/components/IconImage.png'" class="rounded-circle shadow" width="150px" v-else />
                    </div>
                    <div class="d-flex align-items-center justify-content-center fs-2">
                        ようこそ、{{ userLoginData.name }}さん
                    </div>
                </div>
                <!-- 基本情報 -->
                <div class="col-12">
                    <div class="h4">基本情報</div>
                    <div class="list-group w-100">
                        <button 
                            type="button" 
                            class="list-group-item list-group-item-action" 
                            data-bs-toggle="modal" 
                            data-bs-target="#viewInfomationModal"
                            v-for="infoCard in infoCards"
                            :key="infoCard"
                            @click="setViewInfo(infoCard.id)"
                        >
                            <div class="row text-dark">
                                <div class="col-4 col-md-2">
                                    <div class="fw-bold text-start">{{ infoCard.title }}</div>
                                </div>
                                <div class="col-7 col-md-9">
                                    <div class="text-secondary text-truncate">{{ infoCard.comment }}</div>
                                </div>
                                <div class="col-1 text-end ps-2" v-html="infoCard.icon"></div>
                            </div>
                        </button>
                    </div>
                    <hr>
                </div>



                <!-- セキュリティ -->
                <div class="col-12">
                    <div class="h4">セキュリティ</div>
                </div>
                <div class="col-12 col-md-6 mb-5" v-for="controlCard in controlCards" :key="controlCard.id">
                    <div class="card card-body pb-0">
                        <div class="row">
                            <div class="col-9 text-dark">
                                <div class="h5">{{ controlCard.title }}</div>
                                <div class="text-secondary">{{ controlCard.comment }}</div>
                            </div>
                            <div class="col-3">
                                <img :src="controlCard.image" class="rounded-5 shadow border w-100" />
                            </div>
                            <div class="col-12">
                                <div class="border-top mt-3 py-2">
                                    <div 
                                        class="text-primary" 
                                        style="cursor: pointer;" 
                                        @click="setViewPage(controlCard.id)" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#viewSettingModal"
                                        >{{ controlCard.name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- 基本情報モーダル -->
    <div class="modal" id="viewInfomationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="text-center fs-3 fw-bold">INFOMATION</div>
                    <AvatarComponentVue @getLoginData="getLoginData()" v-if="infoCards[0].flag" />
                    <NamesComponent :userData="userLoginData" @getLoginData="getLoginData()" v-if="infoCards[1].flag || infoCards[2].flag || infoCards[3].flag" />
                </div>
            </div>
        </div>
    </div>
    <!-- セキュリティモーダル -->
    <div class="modal" id="viewSettingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body pt-0">
                    <div class="text-center fs-3 fw-bold">
                        TPS ACCOUNT
                    </div>
                    <MailAddressComponent :email="userLoginData.email" @getLoginData="getLoginData()" v-if="controlCards[0].flag" />
                    <PasswordComponent :email="userLoginData.email" @getLoginData="getLoginData()" v-if="controlCards[1].flag" />
                    <DeleteComponent :userLoginData="userLoginData" v-if="controlCards[2].flag" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    #clickItem:hover {
        background: #3d3d3d;
    }
</style>