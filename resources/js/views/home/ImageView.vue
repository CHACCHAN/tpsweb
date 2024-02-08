<script setup>
    import { ref, onMounted, onUpdated } from 'vue'
    import VanillaTilt from 'vanilla-tilt'
    import TopHeader from '../../components/HeaderComponent.vue'
    import BottomFooter from '../../components/FooterComponent.vue'

    const isIconImage = location.protocol + '//' + location.host + '/tps-site/images/components/IconImage.png'
    const isAPP_NAME = ref()
    const isLoginFlag = ref()
    const isAdminstrator = ref()
    const isMediaGroups = ref([])
    const isMedias = ref([])

    onMounted(() => {
        getAPP_NAME()
        getLoginData()
        getData()
    })

    onUpdated(() => {
        VanillaTilt.init(document.querySelectorAll('.tiltElm'), {
            max: 20,
            speed: 400,
            glare: true,
            "max-glare": 0.5,
        })
    })

    const getData = async() => {
        const getMediaGroupPromise = getMediaGroup()
        const getMediaPromise = getMedia()

        await Promise.all([getMediaGroupPromise, getMediaPromise])

        // linkPost()
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

    // const linkPost = () => {
    //     const path = decodeURI(location.pathname).split('/')[4]
    //     for(let i = 0; i < isPostDatas.value.length; i++) {
    //         // 存在する
    //         if(isPostDatas.value[i].title === path && isPostDatas.value[i].public) {
    //             document.title = isPostDatas.value[i].title
    //             viewContent.value = isPostDatas.value[i].content
    //             viewContentDate.value = isPostDatas.value[i].updated_at
    //             break
    //         // 存在しない
    //         } else if(isPostDatas.value[i].title === path && !isPostDatas.value[i].public) {
    //             document.title = '非公開'
    //             viewContent.value = 'このページは非公開設定になっています'
    //             viewContentDate.value = false
    //             break
    //         }
    //     }
    // }

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

    <!-- 画像一覧 -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 col-md-3" v-for="isMediaGroup in isMediaGroups" :key="isMediaGroup.id">
                <div class="position-relative tiltElm">
                    <img :src="'/tps-site/storage/media/groupImage/' + isMediaGroup.image" class="rounded-3" width="100%" style="box-shadow: 0 0 10px black;" />
                    <div class="position-absolute top-0">aaa</div>
                </div>
            </div>
        </div>
    </div>
    
    
    
    <!-- Footer -->
    <BottomFooter :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" />
</template>

<style scoped>
    .cardBox {
        width: 200px;
        height: 100px;
        background: green;
        border-radius: 5px;
    }
</style>