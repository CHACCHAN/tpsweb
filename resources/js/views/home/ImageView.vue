<script setup>
    import { ref, onMounted, onUpdated, onUnmounted } from 'vue'
    import { useRouter } from 'vue-router'
    import VanillaTilt from 'vanilla-tilt'
    import LoadingComponent from '../../components/LoadingComponent.vue'
    import ShareComponent from '../../components/ShareComponent.vue'
    import TopHeader from '../../components/HeaderComponent.vue'
    import BottomFooter from '../../components/FooterComponent.vue'

    const router = useRouter()
    const isLoading = ref(true)
    const defaultTitle = 'メディア'
    const documentTitle = ref(defaultTitle)
    const isIconImage = location.protocol + '//' + location.host + '/tps-site/images/components/IconImage.png'
    const isAPP_NAME = ref()
    const isLoginFlag = ref()
    const isAdminstrator = ref()
    const isViewChange = ref([])
    const isTiltView = ref(true)
    const isMediaGroups = ref([])
    const isMedias = ref([])
    const isMediasFilted = ref([])
    const isClickedMediaName = ref(null)
    const isClickedMediaImage = ref(null)
    const clickedMediaGroupID = ref(null)
    const clickedMediaGroupName = ref(null)
    const AccessLink = ref()

    onMounted(() => {
        changeResponsive()
        getAPP_NAME()
        getLoginData()
        getData()
        connectSession()
    })

    onUpdated(() => {
        if(!clickedMediaGroupID.value) {
            VanillaTilt.init(document.querySelectorAll('.tiltElm'), {
                max: 20,
                speed: 400,
                glare: true,
                "max-glare": 0.5,
            })
        }
        document.title = documentTitle.value
    })

    onUnmounted(() => {
        connectSession()
    })

    const linkPost = () => {
        const path = decodeURI(location.pathname).split('/')[3]
        for(let i = 0; i < isMediaGroups.value.length; i++) {
            if(isMediaGroups.value[i].name === path) {
                clickedMediaGroupID.value = isMediaGroups.value[i].id
                AccessLink.value = location.protocol + '//' + location.host + '/tps-site/image/' + isMediaGroups.value[i].name
                clickedMediaGroupName.value = isMediaGroups.value[i].name
                getFilterMedia(clickedMediaGroupID.value)
                break
            } else if(!path) {
                clickedMediaGroupID.value = false
                break
            }
        }
    }

    const changeResponsive = () => {
        if(window.screen.width <= 768) {
            isTiltView.value = false
        } else {
            isTiltView.value = true
        }
    }

    const getData = async() => {
        const getMediaGroupPromise = getMediaGroup()
        const getMediaPromise = getMedia()

        await Promise.all([getMediaGroupPromise, getMediaPromise])
        linkPost()
        isLoading.value = false
    }

    const connectSession = () => {
        setInterval(async() => {
            if(!isMediaGroups.value[0]) {
                getData()
            }
        }, 5000)
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

    const getFilterMedia = (id) => {
        isMediasFilted.value = new Array()
        for(let i = 0; i < isMedias.value.length; i++) {
            if(isMedias.value[i].media_group_id === id) {
                isMediasFilted.value.push(isMedias.value[i])
            }
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
    <LoadingComponent v-if="isLoading" />
    <!-- Header -->
    <TopHeader :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" style="padding-bottom: 60px;" @click="linkPost()" />
    <div v-if="!isLoading">
        <div class="container-fluid">
            <!-- メディアグループ一覧 -->
            <div class="row" v-if="!clickedMediaGroupID">
                <div class="col-6 col-md-3" v-for="isMediaGroup in isMediaGroups" :key="isMediaGroup.id" style="cursor: pointer;">
                    <div 
                        class="position-relative mb-3" 
                        :class="{ tiltElm: isTiltView }" 
                        @mouseenter="isViewChange[isMediaGroup.id] = true" 
                        @mouseout="isViewChange[isMediaGroup.id] = false"
                        @click="
                                clickedMediaGroupID = isMediaGroup.id,
                                clickedMediaGroupName = isMediaGroup.name,
                                router.push('image/' + isMediaGroup.name),
                                documentTitle = isMediaGroup.name,
                                getFilterMedia(isMediaGroup.id)
                            ">
                        <img 
                            :src="'/tps-site/storage/media/groupImage/' + isMediaGroup.image" 
                            class="rounded-3" 
                            width="100%" 
                            style="box-shadow: 0 0 10px black;"
                            v-if="isMediaGroup.image"
                            loading="lazy" />
                        <img 
                            :src="'/tps-site/images/components/defaultMediaImage.jpg'" 
                            class="rounded-3" 
                            width="100%" 
                            style="box-shadow: 0 0 10px black;"
                            v-if="!isMediaGroup.image"
                            loading="lazy" />
                        <div class="position-absolute w-100 h-100 top-0 d-flex align-items-center justify-content-center">
                            <div class="h5 fw-bold text-truncate text-light" style="text-shadow: 0 0 10px black;" v-text="isMediaGroup.name"></div>
                        </div>
                        <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 30px; text-shadow: 0 0 10px black;">
                            <div class="text-light" :class="{ 'd-none': !isViewChange[isMediaGroup.id] }" v-if="isTiltView">クリック</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- メディア一覧 -->
            <div class="row" v-if="clickedMediaGroupID">
                <!-- メニュー -->
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-start mb-3">
                        <button 
                            type="button" 
                            class="btn btn-primary text-light py-1 rounded-pill" 
                            @click="
                                clickedMediaGroupID = !clickedMediaGroupID,
                                documentTitle = defaultTitle,
                                router.push('/image')
                            ">戻る
                        </button>
                        <div class="h4 m-0 fw-bold text-light ms-2" v-text="clickedMediaGroupName"></div>
                    </div>
                </div>
                <!-- 一覧 -->
                <div class="col-6 col-md-3" v-for="isMediaFilted in isMediasFilted" :key="isMediaFilted.id">
                    <div 
                        id="clickMenuItem" 
                        class="card text-bg-dark border-0 mb-3" 
                        style="cursor: pointer;" 
                        data-bs-toggle="modal" 
                        data-bs-target="#zoomMediaModal"
                        @click="
                            isClickedMediaName = isMediaFilted.name,
                            isClickedMediaImage = isMediaFilted.image
                        ">
                        <img :src="'/tps-site/storage/media/Image/' + isMediaFilted.image" class="rounded-2" width="100%" loading="lazy" />
                        <div class="card-body p-0">
                            <div class="h5 m-0 text-center text-truncate fw-bold" v-text="isMediaFilted.name"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <BottomFooter :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" />



    <!-- クリックコンテンツモーダル -->
    <div class="modal" id="zoomMediaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content text-bg-dark border">
                <img :src="'/tps-site/storage/media/Image/' + isClickedMediaImage" class="rounded-4 rounded-bottom-0" width="100%" loading="lazy">
                <div class="modal-body py-2">
                    <div class="row">
                        <div class="col-11 d-flex align-items-center justify-content-start">
                            <div class="h4 fw-bold text-truncate m-0" v-text="isClickedMediaName"></div>
                        </div>
                        <div class="col-1 d-flex align-items-center justify-content-end">
                            <a id="clickItem" class="text-light p-2 me-1 rounded-circle" :href="'/tps-site/storage/media/Image/' + isClickedMediaImage" :download="isClickedMediaName">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                </svg>
                            </a>
                            <ShareComponent 
                                :X="'http://x.com/share?url=' + AccessLink + '&hashtags=TPS,Wotb&text=TPSが' + isClickedMediaName + 'を投稿したよ!'"
                                :FaceBook="'http://www.facebook.com/share.php?u=' + AccessLink"
                                :LINE="'https://social-plugins.line.me/lineit/share?url=' + AccessLink"
                                :Link="AccessLink"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    #clickItem:hover {
        background: #3d3d3d;
    }
    .notHoverOpacity:hover {
        opacity: 1 !important;
    }
    #clickMenuItem {
        transition: 0.5s;
    }
    #clickMenuItem:hover {
        transform: scale(1.03);
        box-shadow: 0 0 10px rgb(0, 0, 0);
        transition: 0.5s;
    }
</style>