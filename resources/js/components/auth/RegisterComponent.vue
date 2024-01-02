<script setup>
    import { ref, onMounted, onUpdated } from 'vue'
    import RegisterEmailComponentVue from './RegisterEmailComponent.vue'
    import LoadingComponent from '../LoadingComponent.vue'

    const isClickedText = ref()
    const isOutputedRand = ref(Math.floor(Math.random()*(999999 + 1 - 111111)) + 111111)
    const isInputedFirstName = ref()
    const isInputedLastName = ref()
    const isInputedNickName = ref()
    const isInputedEmail = ref()
    const isInputedPassword = ref()
    const toggele = ref(false)
    const submitButton = ref(true)
    const isCheckedForm = ref(true)
    const isLoading = ref(false)
    const isInputedEmailCheck = ref(false)

    onMounted(() => {
        ChangePasswordInput()
    })

    onUpdated(() => {
        if(isInputedFirstName.value && isInputedLastName.value && isInputedNickName.value && isInputedEmail.value && isInputedPassword.value) {
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

    function NextRegister() {
        isLoading.value = true

        fetch('', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                subject: 'テストメール',
                email: isInputedEmail.value,
                onetime_token: isOutputedRand.value,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            isLoading.value = false
            if(res.emailCheck) {
                isCheckedForm.value = false
            } else {
                isInputedEmailCheck.value = true
            }
        })
        .catch(error => {
            console.log(error)
            isLoading.value = false
        })
    }
</script>

<template>
    <LoadingComponent v-if="isLoading"/>
    <form v-if="isCheckedForm">
        <div class="mb-3 d-flex">
            <div class="w-100 me-3">
                <label for="FirstName" class="form-label m-0">姓</label>
                <input type="text" id="FirstName" class="form-control text-bg-dark border-secondary" v-model="isInputedFirstName">
            </div>
            <div class="w-100 ms-3">
                <label for="LastName" class="form-label m-0">名</label>
                <input type="text" id="LastName" class="form-control text-bg-dark border-secondary" v-model="isInputedLastName">
            </div>
        </div>
        <div class="mb-3">
            <label for="Name" class="form-label m-0">ニックネーム</label>
            <input type="text" id="Name" class="form-control text-bg-dark border-secondary" v-model="isInputedNickName">
            <div class="form-text text-secondary m-0">半角英字、数字、ピリオドを使用できます。</div>
        </div>
        <div class="mb-3">
            <label for="Email" class="form-label m-0">メールアドレス</label>
            <input type="email" id="Email" class="form-control text-bg-dark border-secondary" v-model="isInputedEmail">
            <div class="form-text text-danger text-start" v-show="isInputedEmailCheck">既に存在しているメールアドレスです</div>
        </div>
        <div class="mb-3">
            <label for="Password" class="form-label m-0">パスワード</label>
            <input type="password" id="Password" class="form-control text-bg-dark border-secondary" v-model="isInputedPassword">
            <button type="button" class="form-text text-secondary btn btn-dark p-1 border-0" @click="ChangePasswordInput()" v-text="isClickedText"></button>
        </div>
        <div class="text-center mt-3 mx-2">
            <div class="row">
                <div class="col text-start">
                    <a href="login" class="text-decoration-none">代わりにログイン</a>
                </div>
                <div class="col text-end">
                    <button type="button" class="btn btn-primary px-5" @click="NextRegister()" :disabled="submitButton">次へ</button>
                </div>
            </div>
        </div>
    </form>
    <!-- Email Check -->
    <RegisterEmailComponentVue v-if="!isCheckedForm" :InputFirstName="isInputedFirstName" :InputLastName="isInputedLastName" :InputNickName="isInputedNickName" :InputEmail="isInputedEmail" :InputPassword="isInputedPassword" :Token="isOutputedRand"/>
</template>