<script setup>
    import { ref, onMounted } from 'vue'
    import TopHeader from '../../components/HeaderComponent.vue'
    import BottomFooter from '../../components/FooterComponent.vue'
    import PrivacyPolicy from '../../components/footerContents/PrivacyPolicy.vue'

    const isIconImage = location.protocol + '//' + location.host + '/tps-site/images/components/IconImage.png'
    const isAPP_NAME = ref()
    const isLoginFlag = ref()
    const isAdminstrator = ref()
    const inputFirstName = ref(null)
    const inputLastName = ref(null)
    const inputEmail = ref(null)
    const inputSubject = ref(null)
    const inputContent = ref(null)
    const inputprivacyPolicy = ref(false)
    const isViewChange = ref(true)

    onMounted(() => {
        document.title = '問い合わせ'
        getAPP_NAME()
        getLoginData()
    })

    const autoResizeHeightTextarea = (e) => {
        const PADDING_Y = 20
        const textarea = e.target
        let lineHeight = getComputedStyle(textarea).lineHeight

        lineHeight = lineHeight.replace(/[^-\d\.]/g, '')

        const lines = (textarea.value + '\n').match(/\n/g).length

        textarea.style.height = lineHeight * lines + PADDING_Y + 'px'
    }
    
    const uploadContactRequest = async(e) => {
        if(inputFirstName.value && inputLastName.value && inputEmail.value && inputSubject.value && inputContent.value && inputprivacyPolicy.value) {
            const beforeButtonHTML = e.target.innerHTML
            e.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   送信中'
            e.target.disabled = true
            const contactReceptionPromise = contactReception()
            const contactWebhookPromise = contactWebhook()

            await Promise.all([contactReceptionPromise, contactWebhookPromise])
            e.target.innerHTML = beforeButtonHTML
            e.target.disabled = false
            isViewChange.value = false
        }
    }

    const contactReception = () => {
        return new Promise((resolve, reject) => {
            const FirstName = inputFirstName.value
            const LastName = inputLastName.value
            const Email = inputEmail.value
            const Subject = inputSubject.value
            const Content = inputContent.value

            fetch('/tps-site/post/contact/reception', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    first_name: FirstName,
                    last_name: LastName,
                    email: Email,
                    subject: Subject,
                    content: Content
                }),
            })
            .then((response) => response.json())
            .then(res => {
                resolve()
            })
            .catch(error => {
                console.log(error)
                reject(error)
            })
        })
    }

    const contactWebhook = () => {
        const Subject = inputSubject.value
        const Content = inputContent.value

        return new Promise((resolve, reject) => {
            fetch('/tps-site/post/discord/contact/webhook', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    subject: Subject,
                    content: Content
                }),
            })
            .then((response) => response.json())
            .then(res => {
                resolve()
            })
            .catch(error => {
                console.log(error)
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
    <!-- Header -->
    <TopHeader :APP_NAME="isAPP_NAME" :LoginFlag="isLoginFlag" :Adminstrator="isAdminstrator" :IconImage="isIconImage" style="padding-bottom: 60px;" />
    <div class="container h-100">
        <div class="fw-bold text-light d-none d-md-block" style="text-shadow: 0 0 5px rgb(0, 0, 0); font-size: 40px;">Contact/問い合わせ</div>
        <div class="card card-body mb-5 rounded-0" style="background: rgba(255, 255, 255, 0.8); margin: 60px 0;">
            <div class="row mt-5">
                <div class="col-3 d-none d-md-block">
                    <div class="card card-body fw-bold border-0 rounded-0 text-bg-dark d-flex align-items-center justify-content-center w-100" style="padding: 50px 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                        </svg>
                        <div class="fs-5 mt-3">
                            Contact/問い合わせ
                        </div>
                    </div>
                </div>
                <!-- フォーム -->
                <div class="col-12 col-md-9">
                    <div class="card card-body h-100 border-0 rounded-0 pt-0">
                        <div class="border-start border-5 border-primary" style="padding: 52px 0;">
                            <div class="fs-1 fw-bold ms-5">Contact/問い合わせ</div>
                        </div>
                        <div v-if="isViewChange">
                            <div class="my-3">
                                <div class="form-label">苗字とお名前</div>
                                <div class="row">
                                    <div class="col">
                                        <input type="text" v-model="inputFirstName" class="form-control rounded-pill" placeholder="姓" aria-label="姓">
                                    </div>
                                    <div class="col">
                                        <input type="text" v-model="inputLastName" class="form-control rounded-pill" placeholder="名" aria-label="名">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="inputEmailAddress" class="form-label">Emailアドレス</label>
                                <input type="email" v-model="inputEmail" class="form-control rounded-pill" id="inputEmailAddress" placeholder="email@example.com" />
                            </div>
                            <div class="mb-3">
                                <label for="inputSubject" class="form-label">件名</label>
                                <input type="text" v-model="inputSubject" class="form-control rounded-pill" id="inputSubject" placeholder="提供依頼/改善の提案" />
                            </div>
                            <div class="mb-3">
                                <label for="inputContent" class="form-label">内容</label>
                                <textarea 
                                    v-model="inputContent"
                                    class="form-control"
                                    id="inputContent" 
                                    style="height: 44px; resize: none; overflow: hidden;"
                                    placeholder="入力してください"
                                    @input="autoResizeHeightTextarea">
                                </textarea>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" v-model="inputprivacyPolicy" value="" id="flexCheckLicence">
                                <label class="form-check-label" for="flexCheckLicence">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#LicenceModal">プライバシーポリシー</a>に同意する
                                </label>
                            </div>
                            <!-- プライバシーポリシーモーダル -->
                            <div class="modal fade" id="LicenceModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <PrivacyPolicy />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button 
                                    type="button" 
                                    class="btn btn-primary text-light py-1 rounded-pill"
                                    @click="uploadContactRequest"
                                    :disabled="!inputFirstName || !inputLastName || !inputEmail || !inputSubject || !inputContent || !inputprivacyPolicy"
                                    >送信
                                </button>
                            </div>
                        </div>
                        <div v-if="!isViewChange">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-send-check-fill" viewBox="0 0 16 16">
                                        <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 1.59 2.498C8 14 8 13 8 12.5a4.5 4.5 0 0 1 5.026-4.47L15.964.686Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z"/>
                                        <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686Z"/>
                                    </svg>
                                    <div class="fs-1 fw-bold">受付しました</div>
                                    <div class="text-secondary">回答は後日メールからご返信させていただきます。</div>
                                    <div class="text-secondary">このページは閉じても大丈夫です。</div>
                                    <router-link :to="{ name: 'home' }" class="btn btn-primary py-1 mt-5 rounded-pill">ホーム</router-link>
                                </div>
                            </div>
                        </div>
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