<script setup>
    import { ref, onMounted } from 'vue'
    import TopHeader from '../../components/HeaderComponent.vue'
    import BottomFooter from '../../components/FooterComponent.vue'
    import { QuillEditor } from '@vueup/vue-quill'
    import '@vueup/vue-quill/dist/vue-quill.snow.css'
    import '@vueup/vue-quill/dist/vue-quill.bubble.css'

    const isLinkView = ref([true])
    const isIconImage = location.protocol + '//' + location.host + '/tps-site/images/components/IconImage.png'
    const isAPP_NAME = ref()
    const isLoginFlag = ref()
    const isAdminstrator = ref()
    const isPostDatas = ref([])
    const isPostCategorys = ref([])
    const viewContent = ref()

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
                break
            // 存在かつ非公開
            } else if(isPostDatas.value[i].title === path && !isPostDatas.value[i].public) {
                document.title = '非公開'
                viewContent.value = 'このページは非公開設定になっています'
                break
            } else {
                document.title = '不明なページ'
                viewContent.value = 'このページは存在していません'
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
        const postDataPromise = getPostData();
        const postCategoryPromise = getPostCategory();
        
        await Promise.all([postDataPromise, postCategoryPromise]);

        linkPost();
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
    <!-- Header -->
    <TopHeader :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" style="padding-bottom: 60px;"/>

    <!-- View -->
    <div class="container bg-light rounded-4">
        <div class="ql-snow">
            <div class="ql-editor" v-html="viewContent"></div>
        </div>
    </div>

    <!-- Footer -->
    <BottomFooter :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" />
</template>