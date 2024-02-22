<script setup>
    import { ref, onMounted } from 'vue'
    import Cookies from 'js-cookie'

    const props = defineProps({
        email: String,
    })
    const emit = defineEmits([
        'getLoginData'
    ])
    const onetime_token = ref(null)
    const input_token = ref(null)
    const viewChange = ref([
        {
            flag: true,
        },
        {
            flag: false,
        },
        {
            flag: false,
        },
        {
            flag: false,
        }
    ])
    const missMatch = ref(false)
    const inputNewPassword = ref(null)

    onMounted(() => {
        if(Cookies.get('one_time_flag')) {
            viewChange.value[0].flag = false
            viewChange.value[1].flag = false
            viewChange.value[2].flag = true
        }
    })

    const onePasscord = (e) => {
        onetime_token.value = Math.floor(Math.random() * (999999 - 100000 + 1)) + 100000
        const beforeButtonHTML = e.target.innerHTML
        e.target.disabled = true
        e.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   処理中'

        fetch('/tps-site/auth/mailcheck', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email: props.email,
                subject: '認証メール',
                onetime_token: onetime_token.value,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            if(res.responseData) {
                e.target.innerHTML = beforeButtonHTML
                e.target.disabled = false
                viewChange.value[0].flag = false
                viewChange.value[1].flag = true
            } else {
                window.location.reload(true)
            }
        })
        .catch(error => {
            console.log(error)
        })
    }

    const checkOneTime = (e) => {
        const beforeButtonHTML = e.target.innerHTML
        e.target.disabled = true
        e.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   確認中'
        
        if(onetime_token.value === input_token.value) {
            viewChange.value[1].flag = false
            viewChange.value[2].flag = true
            missMatch.value = false
            resetButton()
            if(!Cookies.get('one_time_flag')) {
                Cookies.set('one_time_flag', true)
            }
        } else {
            missMatch.value = true
            resetButton()
        }

        function resetButton() {
            e.target.innerHTML = beforeButtonHTML
            e.target.disabled = false
        }
    }

    const changePassword = (e) => {
        const beforeButtonHTML = e.target.innerHTML
        const inputPassword = inputNewPassword.value
        e.target.disabled = true
        e.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   送信中'

        fetch('/tps-site/auth/change/password', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                password: inputPassword,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            if(res.responseData) {
                resetButton()
                viewChange.value[2].flag = false
                viewChange.value[3].flag = true
                missMatch.value = false
                emit('getLoginData')

            } else {
                window.location.reload(true)
            }
        })
        .catch(error => {
            console.log(error)
        })

        function resetButton() {
            e.target.innerHTML = beforeButtonHTML
            e.target.disabled = false
        }
    }

    const seePassword = (e) => {
        const newPasswordElm = document.getElementById('newPassword')

        if(newPasswordElm.type === 'password') {
            newPasswordElm.type = 'text'
            e.target.innerHTML = 'パスワードを非表示'
        } else {
            newPasswordElm.type = 'password'
            e.target.innerHTML = 'パスワードを表示'
        }
    }
</script>

<template>
    <div class="text-center" v-if="viewChange[0].flag">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-shield-fill-x" viewBox="0 0 16 16">
            <path d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.777 11.777 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7.159 7.159 0 0 0 1.048-.625 11.775 11.775 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.541 1.541 0 0 0-1.044-1.263 62.467 62.467 0 0 0-2.887-.87C9.843.266 8.69 0 8 0zM6.854 5.146 8 6.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 7l1.147 1.146a.5.5 0 0 1-.708.708L8 7.707 6.854 8.854a.5.5 0 1 1-.708-.708L7.293 7 6.146 5.854a.5.5 0 1 1 .708-.708z"/>
        </svg>
        <div class="fs-5 fw-bold">パスワードを変更する前に認証が必要です</div>
        <button type="button" class="btn btn-primary rounded-pill py-1 text-light mt-3" @click="onePasscord">認証する</button>
    </div>



    <div class="text-center"  v-if="viewChange[1].flag">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-shield-fill-plus" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.777 11.777 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7.159 7.159 0 0 0 1.048-.625 11.775 11.775 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.541 1.541 0 0 0-1.044-1.263 62.467 62.467 0 0 0-2.887-.87C9.843.266 8.69 0 8 0zm-.5 5a.5.5 0 0 1 1 0v1.5H10a.5.5 0 0 1 0 1H8.5V9a.5.5 0 0 1-1 0V7.5H6a.5.5 0 0 1 0-1h1.5V5z"/>
        </svg>
        <div class="fs-5 fw-bold">認証コードを入力してください</div>
        <input type="number" v-model="input_token" class="form-control rounded-pill border-success border-2 mt-3" />
        <div class="form-text text-start text-danger" v-if="missMatch">認証コードが一致しませんでした</div>
        <div class="text-end mt-3">
            <button type="button" class="btn btn-success rounded-pill py-1 text-light mt-3" @click="checkOneTime">確認</button>
        </div>
    </div>



    <div class="text-start" v-if="viewChange[2].flag">
        <div class="text-secondary">新しいパスワード</div>
        <input type="password" v-model="inputNewPassword" id="newPassword" class="form-control rounded-pill" />
        <button type="button" class="btn btn-light border-0 mt-1 p-1" @click="seePassword">パスワードを表示</button>
        <div class="text-end mt-3">
            <button type="button" class="btn btn-success rounded-pill py-1 text-light" @click="changePassword" :disabled="!inputNewPassword">変更</button>
        </div>
    </div>



    <div class="text-center" v-if="viewChange[3].flag">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-shield-fill-check" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.777 11.777 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7.159 7.159 0 0 0 1.048-.625 11.775 11.775 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.541 1.541 0 0 0-1.044-1.263 62.467 62.467 0 0 0-2.887-.87C9.843.266 8.69 0 8 0zm2.146 5.146a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647z"/>
        </svg>
        <div class="fs-5 fw-bold">正常に更新されました</div>
    </div>
</template>