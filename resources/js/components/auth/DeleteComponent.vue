<script setup>
    import { ref } from 'vue'

    const props = defineProps({
        userLoginData: Object,
    })
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
    ])
    const missMatch = ref(false)

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
                email: props.userLoginData.email,
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
        } else {
            missMatch.value = true
            resetButton()
        }

        function resetButton() {
            e.target.innerHTML = beforeButtonHTML
            e.target.disabled = false
        }
    }

    const deleteAccount = (e) => {
        e.target.disabled = true
        e.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   処理中'

        fetch('/tps-site/auth/delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: props.userLoginData.id,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            window.location.reload(true)
        })
        .catch(error => {
            console.log(error)
        })
    }
</script>

<template>
    <div class="text-center" v-if="viewChange[0].flag">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-person-fill-exclamation" viewBox="0 0 16 16">
            <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-9 8c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Z"/>
            <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-3.5-2a.5.5 0 0 0-.5.5v1.5a.5.5 0 0 0 1 0V11a.5.5 0 0 0-.5-.5Zm0 4a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1Z"/>
        </svg>
        <div class="fs-5 fw-bold">アカウント削除の前に認証が必要です</div>
        <button type="button" class="btn btn-primary rounded-pill py-1 text-light mt-3" @click="onePasscord">認証する</button>
    </div>



    <div class="text-center"  v-if="viewChange[1].flag">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-person-fill-add" viewBox="0 0 16 16">
            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0Zm-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
            <path d="M2 13c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Z"/>
        </svg>
        <div class="fs-5 fw-bold">認証コードを入力してください</div>
        <input type="number" v-model="input_token" class="form-control rounded-pill border-success border-2 mt-3" />
        <div class="form-text text-start text-danger" v-if="missMatch">認証コードが一致しませんでした</div>
        <div class="text-end mt-3">
            <button type="button" class="btn btn-success rounded-pill py-1 text-light mt-3" @click="checkOneTime">確認</button>
        </div>
    </div>



    <div class="text-center" v-if="viewChange[2].flag">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-person-fill-x" viewBox="0 0 16 16">
            <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-9 8c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Z"/>
            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708Z"/>
        </svg>
        <div class="fs-5 fw-bold mb-3">アカウントを本当に削除しますか?</div>
        <div class="text-secondary text-start">
            アカウントを削除すると、ゲスト以外のサービス全般が利用不可になります。
            また、アカウントを復元することは一切できません。
        </div>
        <div class="text-end mt-3">
            <button type="button" class="btn btn-danger rounded-pill py-1 text-light" @click="deleteAccount">アカウント削除</button>
        </div>
    </div>
</template>