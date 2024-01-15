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

    onMounted(() => {
        document.title = 'ログイン'
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

        fetch('', {
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
            if(res.check) {
                router.push({ name: 'home' })
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
    <div class="mb-3">
        <label for="Email" class="form-label m-0">メールアドレス</label>
        <input type="email" id="Email" class="form-control text-bg-dark border-secondary" v-model="isInputedEmail">
    </div>
    <div class="mb-3">
        <label for="Password" class="form-label m-0">パスワード</label>
        <input type="password" id="Password" class="form-control text-bg-dark border-secondary" v-model="isInputedPassword">
        <div class="form-text text-danger text-start" v-show="isCheckedForm">メールアドレスまたはパスワードが不明です</div>
        <button type="button" id="clickItem" class="form-text text-secondary btn p-1 border-0" @click="ChangePasswordInput()" v-text="isClickedText"></button>
    </div>
    <div class="mt-3">
        <div class="text-center">
            <button type="button" class="btn btn-primary rounded-pill w-100" @click="Login()" :disabled="submitButton">ログイン</button>
            <div class="d-flex align-items-center justify-content-center mt-1">
                <button type="button" class="btn text-primary text-truncate border-0 p-0">登録情報を忘れた方</button>
                <div class="vr mx-2 my-1"></div>
                <a href="/tps-site/auth/register" class="btn text-light text-truncate border-0 p-0">新規登録</a>
            </div>
        </div>
    </div>
</template>

<style scoped>
#clickItem:hover {
    background: #3d3d3d;
}
</style>