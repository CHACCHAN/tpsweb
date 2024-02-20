<script setup>
    import { ref, onMounted } from 'vue'
    import { useRouter } from 'vue-router'
    import Cropper from 'cropperjs'

    const router = useRouter()
    const userLoginData = ref(null)
    const zoomView = ref(false)
    const controlCards = ref([
        {
            id: 1,
            flag: false,
            title: 'メールアドレス',
            comment: 'TPSアカウントのメールアドレスを確認し、必要に応じて変更します。',
            image: '/tps-site/images/components/MailAddress.jpg',
        }
    ])
    onMounted(() => {
        document.title = 'プロフィール'
        getLoginData()
    })

    const getLoginData = () => {
        userLoginData.value = new Array()

        fetch('/tps-site/auth/login/check')
        .then((response) => response.json())
        .then(res => {
            if(res.ResponseData) {
                userLoginData.value = res.UserData
            } else {
                router.push({ name: 'home' })
            }
        })
        .catch(error => {
            console.log(error)
        })
    }

    const setViewPage = (id) => {
        zoomView.value = true
        for(let i = 0; i < controlCards.value.length; i++) {
            if(controlCards.value[i].id === id) {
                controlCards.value[i].flag = true
            } else {
                controlCards.value[i].flag = false
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
            <div class="row" v-if="!zoomView">
                <div class="col-12 mb-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <img :src="'/tps-site/storage/user/avatar/' + userLoginData.avatar" class="rounded-circle shadow" width="100px" v-if="userLoginData.avatar" />
                        <img :src="'/tps-site/images/components/IconImage.png'" class="rounded-circle shadow" width="100px" v-else />
                    </div>
                    <div class="d-flex align-items-center justify-content-center fs-2">
                        ようこそ、{{ userLoginData.name }}さん
                    </div>
                </div>
                <div class="col-12">
                    <div class="h4">基本情報</div>
                    <div class="card card-body">
                    </div>
                    <hr>
                </div>
                <div class="col-12 col-md-6" v-for="controlCard in controlCards" :key="controlCard.id">
                    <div class="h4">セキュリティ</div>
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
                                    <div class="text-primary" style="cursor: pointer;" @click="setViewPage(userLoginData.id)">メールアドレスを確認する</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 画面切替 -->
            <div v-else>
                <!-- メニュー -->
                <button class="btn btn-primary rounded-pill mt-3 py-1" @click="zoomView = false">戻る</button>
                <!-- メールアドレス -->
                <!-- パスワード -->
                <!-- アバター -->
            </div>
        </div>
    </div>
</template>

<style scoped>
    #clickItem:hover {
        background: #3d3d3d;
    }
</style>