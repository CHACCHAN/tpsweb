<script setup>
    import { ref, onMounted } from 'vue'
    import LoadingComponent from '../../components/LoadingComponent.vue'
    import TopHeader from '../../components/HeaderComponent.vue'
    import BottomFooter from '../../components/FooterComponent.vue'
    import { QuillEditor } from '@vueup/vue-quill'
    import '@vueup/vue-quill/dist/vue-quill.snow.css'
    import '@vueup/vue-quill/dist/vue-quill.bubble.css'

    const isLoading = ref(true)
    const isIconImage = location.protocol + '//' + location.host + '/tps-site/images/components/IconImage.png'
    const isAPP_NAME = ref()
    const isLoginFlag = ref()
    const isAdminstrator = ref()
    const isPostDatas = ref([])
    const isPostCategorys = ref([])
    const viewContent = ref()
    const viewContentDate = ref()

    onMounted(() => {
        getAPP_NAME()
        getLoginData()
        getPosts()
    })

    const linkPost = () => {
        const path = decodeURI(location.pathname).split('/')[4]
        for(let i = 0; i < isPostDatas.value.length; i++) {
            // 存在かつ公開中
            if(isPostDatas.value[i].title === path && isPostDatas.value[i].public) {
                document.title = isPostDatas.value[i].title
                viewContent.value = isPostDatas.value[i].content
                viewContentDate.value = isPostDatas.value[i].updated_at
                break
            // 存在かつ非公開
            } else if(isPostDatas.value[i].title === path && !isPostDatas.value[i].public) {
                document.title = '非公開'
                viewContent.value = 'このページは非公開設定になっています'
                viewContentDate.value = false
                break
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

    const getPosts = async() => {
        const postDataPromise = getPostData()
        const postCategoryPromise = getPostCategory()
        
        await Promise.all([postDataPromise, postCategoryPromise])

        linkPost()
        isLoading.value = false
    }

    const getPostData = () => {
        return new Promise((resolve, reject) => {
            fetch('/tps-site/get/postdata')
            .then(response => response.json()) 
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
            .then(response => response.json())
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
</script>

<template>
    <LoadingComponent v-if="isLoading" />
    <!-- Header -->
    <TopHeader :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" style="padding-bottom: 60px;" @click="linkPost()"/>

    <!-- View -->
    <div class="container bg-light rounded-4">
        <p v-if="viewContentDate" v-text="'投稿日時 : ' + viewContentDate" class="text-secondary text-end"></p>
        <div class="ql-snow">
            <div class="ql-editor" v-html="viewContent"></div>
        </div>
    </div>

    <!-- Footer -->
    <BottomFooter :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" />
</template>