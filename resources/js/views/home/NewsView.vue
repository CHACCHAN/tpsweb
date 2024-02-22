<script setup>
    import { ref, onMounted } from 'vue'
    import LoadingComponent from '../../components/LoadingComponent.vue'
    import TopHeader from '../../components/HeaderComponent.vue'
    import BottomFooter from '../../components/FooterComponent.vue'

    const isIconImage = location.protocol + '//' + location.host + '/tps-site/images/components/IconImage.png'
    const isLoading = ref(true)
    const isAPP_NAME = ref()
    const isLoginFlag = ref()
    const isAdminstrator = ref()
    const pickups = ref([])
    const pickupChangeView = ref(true)
    const pickupClickedTitle = ref(null)
    const pickupClickedContent = ref(null)
    const pickupClickedCreated = ref(null)

    onMounted(() => {
        document.title = 'ニュース'
        getAPP_NAME()
        getLoginData()
        getData()
    })

    const getData = async() => {
        const getPickupPromise = getPickup()

        await Promise.all([getPickupPromise])
    }

    const getDataButton = async(e) => {
        const beforeButtonHTML = e.target.innerHTML
        e.target.disabled = true
        e.target.innerHTML = 
        `
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `
        
        const getPickupPromise = getPickup()

        await Promise.all([getPickupPromise])
        e.target.innerHTML = beforeButtonHTML
        e.target.disabled = false
    }

    const getPickup = () => {
        return new Promise((resolve, reject) => {
            fetch('/tps-site/get/pickup')
            .then((response) => response.json())
            .then(res => {
                pickups.value = res.responseData
                isLoading.value = false
                resolve()
            })
            .catch(error => {
                console.log(error)
                isLoading.value = false
                reject(error)
            })
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
<div id="backImage">
    <LoadingComponent v-if="isLoading" />
    <!-- Header -->
    <TopHeader :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" style="padding-bottom: 60px;" />
    <div class="container h-100">
        <div class="fw-bold text-light d-none d-md-block" style="text-shadow: 0 0 5px rgb(0, 0, 0); font-size: 40px;">Pickup/ニュース</div>
        <div class="card card-body mb-5 rounded-0" style="background: rgba(255, 255, 255, 0.8); margin: 60px 0;">
            <div class="row mt-5">
                <div class="col-3 d-none d-md-block">
                    <button type="button" @click="getDataButton" class="card card-body fw-bold border-0 rounded-0 text-bg-dark d-flex align-items-center justify-content-center w-100" style="padding: 50px 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                        </svg>
                        <div class="fs-5 mt-3">
                            更新する
                        </div>
                    </button>
                </div>
                <!-- 一覧 -->
                <div class="col-12 col-md-9" v-if="pickupChangeView">
                    <div class="card card-body h-100 border-0 rounded-0 pt-0">
                        <div class="border-start border-5 border-primary" style="padding: 52px 0;">
                            <div class="fs-1 fw-bold ms-5">Pickup/ニュース</div>
                        </div>
                        <div class="row border-bottom py-2" v-for="pickup in pickups" :key="pickup.id">
                            <div class="col-2">
                                <div class="fw-bold text-center" v-text="pickup.created_at"></div>
                            </div>
                            <div class="col-2 bg-success">
                                <div class="fw-bold text-center text-light">Pickup</div>
                            </div>
                            <div class="col-8">
                                <div 
                                    class="fw-bold text-truncate" 
                                    @click="
                                        pickupChangeView = !pickupChangeView,
                                        pickupClickedTitle = pickup.title,
                                        pickupClickedContent = pickup.content,
                                        pickupClickedCreated = pickup.created_at
                                    " 
                                    style="cursor: pointer;" 
                                    v-text="pickup.title">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 詳細 -->
                <div class="col-12 col-md-9" v-if="!pickupChangeView">
                    <div class="d-flex align-items-center justify-content-start">
                        <button type="button" class="btn btn-primary text-light py-1 rounded-pill me-2" @click="pickupChangeView = !pickupChangeView">戻る</button>
                        <div class="fs-2 fw-bold" v-text="pickupClickedTitle"></div>
                    </div>
                    <div class="card card-body rounded-0 border-0 p-2">
                        <div class="text-secondary text-end fw-bold" v-text="pickupClickedCreated"></div>
                        <div v-text="pickupClickedContent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <BottomFooter :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" />
</div>
</template>

<style scoped>
#backImage {
    background-image: 
    linear-gradient(
    rgba(0,0,0,0.5), 
    rgba(0,0,0,0.5)
    ),
    url(https://picsum.photos/1920/1080);
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
    width: 100%;
}
</style>