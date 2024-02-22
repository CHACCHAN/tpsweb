<script setup>
    import { ref, onMounted, onUpdated } from 'vue'
    import { useRouter } from 'vue-router'
    import LoadingComponent from '../LoadingComponent.vue'

    const router = useRouter()
    const isClickedText = ref()
    const isInputedEmail = ref()
    const isInputedPassword = ref()
    const toggele = ref(false)
    const isLoading = ref(false)
    const isCheckedForm = ref(false)
    const submitButton = ref(true)
    const userFocusBanData = ref(null)
    const isWidth = ref(false)

    onMounted(() => {
        document.title = 'ログイン'
        window.addEventListener('resize', changeResponsive)
        changeResponsive()
        ChangePasswordInput()
        document.addEventListener('keypress', (e) => {
            if(e.key === 'Enter' && !submitButton.value) {
                Login()
            }
        })
    })

    onUpdated(() => {
        if(isInputedEmail.value && isInputedPassword.value) {
            if(isInputedEmail.value.includes('@')) {
                submitButton.value = false
            }
        } else {
            submitButton.value = true
        }
    })

    const changeResponsive = () => {
        if(window.screen.width <= 768) {
            isWidth.value = true
        } else {
            isWidth.value = false
        }
    }

    function ChangePasswordInput() {
        let target = document.getElementById('Password')
        if(toggele.value) {
            target.type = 'text'
            isClickedText.value = 'パスワードを非表示'
            toggele.value = false
        } else {
            target.type = 'password'
            isClickedText.value = 'パスワードを表示'
            toggele.value = true
        }
    }

    function Login() {
        isLoading.value = true

        fetch('/tps-site/auth/login', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email: isInputedEmail.value,
                password: isInputedPassword.value,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            document.querySelector('meta[name="csrf-token"]').content = res.csrf
            if(res.check && res.responseData) {
                router.push({ name: 'home' })
            } else if(res.check && !res.responseData) {
                userFocusBanData.value = new Array()
                userFocusBanData.value = res.user_ban_list

                const start_date = new Date(userFocusBanData.value.start_date)
                const last_date = new Date(userFocusBanData.value.last_date)
                userFocusBanData.value.start_date = start_date.toLocaleDateString('ja-JP', { year: 'numeric', month: 'long', day: 'numeric' })
                userFocusBanData.value.last_date = last_date.toLocaleDateString('ja-JP', { year: 'numeric', month: 'long', day: 'numeric' })
            } else {
                isCheckedForm.value = true
            }
            isLoading.value = false
        })
        .catch(error => {
            console.log(error)
            isLoading.value = false
            isCheckedForm.value = true
        })
    }
</script>

<template>
    <LoadingComponent v-if="isLoading" />
    <div class="form-floating text-dark mb-3">
        <input type="email" class="form-control border-0" id="Email" placeholder="name@example.com" v-model="isInputedEmail" />
        <label for="Email">メールアドレス</label>
    </div>
    <div class="form-floating text-dark mb-3">
        <input type="password" class="form-control border-0" id="Password" placeholder="Password" v-model="isInputedPassword">
        <label for="Password">パスワード</label>
        <div class="form-text text-danger text-start" v-show="isCheckedForm">メールアドレスまたはパスワードが不明です</div>
        <button type="button" id="clickItem" class="form-text text-secondary btn p-1 border-0" @click="ChangePasswordInput()" v-text="isClickedText"></button>
    </div>
    <div class="mt-3">
        <div class="text-center">
            <button type="button" class="btn btn-primary rounded-pill w-100" @click="Login()" :disabled="submitButton">ログイン</button>
            <div class="d-flex align-items-center justify-content-center mt-1">
                <a href="/tps-site/auth/register" class="btn text-secondary text-truncate border-0 p-0">新規登録</a>
            </div>
        </div>
    </div>

    <!-- 利用停止の場合 -->
    <div class="ElmCenter px-3" :class="{ 'w-100': isWidth }" v-if="userFocusBanData">
        <div class="card card-body text-bg-dark border">
            <div class="fs-5 fw-bold" v-text="'あなたのアカウントは' + userFocusBanData.start_date + 'に利用停止されました'"></div>
            <div class="text-secondary">この決定への異議申し立てはお問い合わせから可能です。</div>
            <hr>
            <div class="fw-bold mb-3">この影響について</div>
            <div class="d-flex align-items-center justify-content-start mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                    <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                    <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                </svg>
                <div class="ms-3">あなたのアカウントは利用規約に違反しています。</div>
            </div>
            <div class="d-flex align-items-center justify-content-start mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-slash-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M11.354 4.646a.5.5 0 0 0-.708 0l-6 6a.5.5 0 0 0 .708.708l6-6a.5.5 0 0 0 0-.708z"/>
                </svg>
                <div class="ms-3">あなたのアカウントを認証できない場合、アカウントは永久に停止されます。</div>
            </div>
            <div class="d-flex align-items-center justify-content-start mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16">
                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                </svg>
                <div class="ms-3">あなたのアカウントは、{{ userFocusBanData.last_date }}まで停止されます。</div>
            </div>
            <hr>
            <router-link :to="{ name: 'contact' }" class="btn btn-primary rounded-pill py-1">問い合わせ</router-link>
        </div>
    </div>
</template>

<style scoped>
#clickItem:hover {
    background: #3d3d3d;
}

.ElmCenter {
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translateX(-50%) translateY(-50%);
    z-index: 3;
}
</style>