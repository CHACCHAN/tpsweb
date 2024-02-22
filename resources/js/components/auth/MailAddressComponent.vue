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
    const inputNewMail = ref(null)

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

    const changeMailAddress = (e) => {
        const beforeButtonHTML = e.target.innerHTML
        const inputEmail = inputNewMail.value
        e.target.disabled = true
        e.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   送信中'

        fetch('/tps-site/auth/change/mailaddress', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email: inputEmail,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            if(res.responseData && res.checkMail) {
                resetButton()
                viewChange.value[2].flag = false
                viewChange.value[3].flag = true
                missMatch.value = false
                emit('getLoginData')

            } else if(res.responseData && !res.checkMail) {
                resetButton()
                missMatch.value = true
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
</script>

<template>
    <div class="text-center" v-if="viewChange[0].flag">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-envelope-exclamation-fill" viewBox="0 0 16 16">
            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.026A2 2 0 0 0 2 14h6.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586l-1.239-.757ZM16 4.697v4.974A4.491 4.491 0 0 0 12.5 8a4.49 4.49 0 0 0-1.965.45l-.338-.207L16 4.697Z"/>
            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1.5a.5.5 0 0 1-1 0V11a.5.5 0 0 1 1 0Zm0 3a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Z"/>
        </svg>
        <div class="fs-5 fw-bold">メールアドレスを変更する前に認証が必要です</div>
        <button type="button" class="btn btn-primary rounded-pill py-1 text-light mt-3" @click="onePasscord">認証する</button>
    </div>



    <div class="text-center"  v-if="viewChange[1].flag">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-envelope-at-fill" viewBox="0 0 16 16">
            <path d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2H2Zm-2 9.8V4.698l5.803 3.546L0 11.801Zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586l-1.239-.757ZM16 9.671V4.697l-5.803 3.546.338.208A4.482 4.482 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671Z"/>
            <path d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034v.21Zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791Z"/>
        </svg>
        <div class="fs-5 fw-bold">認証コードを入力してください</div>
        <input type="number" v-model="input_token" class="form-control rounded-pill border-success border-2 mt-3" />
        <div class="form-text text-start text-danger" v-if="missMatch">認証コードが一致しませんでした</div>
        <div class="text-end mt-3">
            <button type="button" class="btn btn-success rounded-pill py-1 text-light mt-3" @click="checkOneTime">確認</button>
        </div>
    </div>



    <div class="text-start" v-if="viewChange[2].flag">
        <div class="text-secondary">現在のメールアドレス</div>
        <div class="fw-bold">{{ props.email }}</div>
        <br>
        <div class="text-secondary">新しいメールアドレス</div>
        <input type="email" v-model="inputNewMail" class="form-control rounded-pill" />
        <div class="form-text text-start text-danger" v-if="missMatch">存在するメールアドレスは使用できません</div>
        <div class="text-end mt-3">
            <button type="button" class="btn btn-success rounded-pill py-1 text-light" @click="changeMailAddress" :disabled="!inputNewMail">変更</button>
        </div>
    </div>



    <div class="text-center" v-if="viewChange[3].flag">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-envelope-check-fill" viewBox="0 0 16 16">
            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.026A2 2 0 0 0 2 14h6.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586l-1.239-.757ZM16 4.697v4.974A4.491 4.491 0 0 0 12.5 8a4.49 4.49 0 0 0-1.965.45l-.338-.207L16 4.697Z"/>
            <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686Z"/>
        </svg>
        <div class="fs-5 fw-bold">正常に更新されました</div>
    </div>
</template>