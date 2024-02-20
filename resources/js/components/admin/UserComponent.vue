<script setup>
    import { ref, onMounted, onUpdated, onUnmounted } from 'vue'
    import LoadingComponent from '../LoadingComponent.vue'

    const isLoading = ref(true)
    const isNotificalText = ref(null)
    const userDatas = ref(null)
    const userFocusData = ref(null)
    const userFocusBanData = ref(null)
    const userLoginData = ref(null)
    const userSearchData = ref(null)

    onMounted(() => {
        setTimeout(() => {
            getData()
            connectData()
        }, 1000)
    })

    onUpdated(() => {
        if(isNotificalText.value) {
            setTimeout(() => {
                isNotificalText.value = ''
            }, 2000)
        }
    })

    onUnmounted(() => {
        connectData()
    })

    const getData = async() => {
        const getUserDataPromise = getUserData()
        const getLoginDataPromise = getLoginData()

        await Promise.all([getUserDataPromise, getLoginDataPromise])
        isLoading.value = false
    }

    const getUserData = () => {
        return new Promise((resolve, reject) => {
            const link = location.pathname + decodeURI(location.hash)
            userDatas.value = new Array()

            fetch('/tps-site/post/user/get', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    link: link,
                }),
            })
            .then((response) => response.json())
            .then(res => {
                userDatas.value = res.responseData
                resolve()
            })
            .catch(error => {
                console.log(error)
                reject(error)
            })
        })
    }

    const getLoginData = () => {
        return new Promise((resolve, reject) => {
            userLoginData.value = new Array()

            fetch('/tps-site/auth/login/check')
            .then((response) => response.json())
            .then(res => {
                userLoginData.value = res.UserData
                resolve()
            })
            .catch(error => {
                console.log(error)
                reject(error)
            })
        })
    }

    const getUserFocus = (setId) => {
        const link = location.pathname + decodeURI(location.hash)
        userFocusData.value = new Array()

        fetch('/tps-site/post/user/get/focus', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: setId,
                link: link,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            userFocusData.value = res.responseData
        })
        .catch(error => {
            console.log(error)
        })
    }

    const getUserFocusBan = (setId) => {
        const link = location.pathname + decodeURI(location.hash)
        userFocusBanData.value = new Array()

        fetch('/tps-site/post/user/ban/get/focus', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: setId,
                link: link,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            if(res.responseData) {
                userFocusBanData.value = res.responseData
                userFocusBanData.value.ban = true

                const start_date = new Date(userFocusBanData.value.start_date)
                const last_date = new Date(userFocusBanData.value.last_date)
                userFocusBanData.value.start_date = start_date.toLocaleDateString('ja-JP', { year: 'numeric', month: 'long', day: 'numeric' })
                userFocusBanData.value.last_date = last_date.toLocaleDateString('ja-JP', { year: 'numeric', month: 'long', day: 'numeric' })
            } else {
                userFocusBanData.value.ban = false
            }
        })
        .catch(error => {
            console.log(error)
        })
    }

    const getUserSearch = () => {
        let userSearch = new Object()
        userSearch.content = userSearchData.value
        userDatas.value = null

        if(userSearch.content.indexOf('@') !== -1) {
            userSearch.name = null
            userSearch.email = userSearch.content
            runRequest()
        } else {
            userSearch.name = userSearch.content
            userSearch.email = null
            runRequest()
        }

        function runRequest() {
            fetch('/tps-site/auth/search', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: userSearch.name,
                    email: userSearch.email,
                }),
            })
            .then((response) => response.json())
            .then(res => {
                if(res.responseData) {
                    userDatas.value = res.responseData
                }
            })
            .catch(error => {
                console.log(error)
            })
        }
    }

    const updateUser = (setId) => {
        const link = location.pathname + decodeURI(location.hash)

        fetch('/tps-site/auth/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: setId,
                link: link,
                first_name: userFocusData.value.first_name,
                last_name: userFocusData.value.last_name,
                name: userFocusData.value.name,
                email: userFocusData.value.email,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            if(res.responseData) {
                isNotificalText.value = '基本情報が更新されました'
            } else {
                isNotificalText.value = '基本情報の更新に失敗しました'
            }
            getUserFocus(setId)
            getData()
        })
        .catch(error => {
            console.log(error)
        })
    }

    const requestAuth = (setId, administrator) => {
        const link = location.pathname + decodeURI(location.hash)

        fetch('/tps-site/auth/change/admin', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: setId,
                administrator: !administrator,
                link: link,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            getUserFocus(setId)
            if(res.responseData) {
                isNotificalText.value = '管理者権限を変更しました'
            } else {
                isNotificalText.value = '管理者権限の変更に失敗しました'
            }
        })
        .catch(error => {
            console.log(error)
        })
    }

    const stopUser = (setId) => {
        const link = location.pathname + decodeURI(location.hash)

        fetch('/tps-site/auth/change/ban', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: setId,
                link: link,
                ban: !userFocusBanData.value.ban,
                start_date: userFocusData.value.start_date,
                last_date: userFocusData.value.last_date,
                level: userFocusData.value.level,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            if(res.responseData) {
                getUserFocus(setId)
                getUserFocusBan(setId)
                isNotificalText.value = 'アカウントを利用状態を変更しました'
            } else {
                isNotificalText.value = 'アカウントを変更できませんでした'
            }
            isLoading.value = false
        })
        .catch(error => {
            console.log(error)
            isLoading.value = false
        })
    }

    const deleteUser = (setId) => {
        isLoading.value = true
        fetch('/tps-site/auth/delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: setId,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            if(res.responseData) {
                userFocusData.value = null
                getData()
                isNotificalText.value = 'アカウントを削除しました'
            } else {
                isNotificalText.value = 'アカウントの削除に失敗しました'
            }
            isLoading.value = false
        })
        .catch(error => {
            console.log(error)
            isLoading.value = false
        })
    }

    const connectData = () => {
        setInterval(() => {
            if(!userDatas.value) {
                getData()
            }
        }, 5000)
    }
</script>

<template>
    <LoadingComponent v-if="isLoading" />
    <div class="container">
        <div class="h4">ユーザ</div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>
            </span>
            <input type="text" class="form-control" v-model="userSearchData" @change="getUserSearch()" placeholder="名前またはメールアドレスで検索" aria-describedby="basic-addon1">
        </div>
        <!-- ユーザ選択画面 -->
        <div class="mb-3">
            <div class="card card-body">
                <div class="fw-bold text-success">選択したユーザの情報</div>
                <div class="row" v-if="userFocusData">
                    <div class="col-12 col-md-2 mb-3">
                        <img :src="'/tps-site/storage/user/avatar/' + userFocusData.avatar" class="rounded shadow w-100" v-if="userFocusData.avatar" />
                        <div class="position-relative" v-else>
                            <img :src="'/tps-site/images/components/IconImage.png'" class="rounded shadow w-100" />
                            <div class="position-absolute d-flex align-items-center justify-content-center w-100 h-100" style="top: 0;">
                                <div class="fw-bold text-light" style="text-shadow: 0 0 10px black;">未設定です</div>
                            </div>
                        </div>
                        <div class="fw-bold text-center">ユーザアバター</div>
                    </div>
                    <div class="col-12 col-md-10 card card-body p-2">
                        <!-- 基本情報 -->
                        <div v-if="userFocusData.id == userLoginData.id" class="text-danger">※このアカウントはあなたです</div>
                        <div class="text-secondary" v-if="userFocusBanData.ban">
                            ※このアカウントは{{ userFocusBanData.start_date }}から{{ userFocusBanData.last_date }}まで停止されています
                        </div>
                        <div class="text-secondary" v-if="userFocusData.administrator">
                            ※このアカウントは管理者です
                        </div>
                        <div class="text-success fw-bold">基本情報</div>
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-floating">
                                    <input type="text" class="form-control fw-bold fs-5" v-model="userFocusData.first_name" @change="updateUser(userFocusData.id)" placeholder="" id="inputFirstName" />
                                    <label for="inputFirstName">姓</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <input type="text" class="form-control fw-bold fs-5" v-model="userFocusData.last_name" @change="updateUser(userFocusData.id)" placeholder="" id="inputLastName" />
                                    <label for="inputLastName">名</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control fw-bold fs-5" v-model="userFocusData.name" @change="updateUser(userFocusData.id)" placeholder="" id="inputName" />
                            <label for="inputName">ニックネーム</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control fw-bold fs-5" v-model="userFocusData.email" @change="updateUser(userFocusData.id)" placeholder="" id="inputEmail" />
                            <label for="inputEmail">メールアドレス</label>
                        </div>
                        <!-- アカウント操作 -->
                        <div class="text-success fw-bold" v-if="userFocusData.id != userLoginData.id">アカウント操作</div>
                        <div class="row" v-if="userFocusData.id != userLoginData.id">
                            <div class="col">
                                <div class="card card-body p-2 h-100">
                                    <!-- 権限 -->
                                    <div class="text-dark fw-bold">権限</div>
                                    <div class="d-flex align-items-center justify-content-start">
                                        <button 
                                            type="button" 
                                            class="btn btn-danger text-light rounded-pill py-1 mb-1" 
                                            @click="requestAuth(userFocusData.id, userFocusData.administrator)" 
                                            v-if="userFocusData.administrator"
                                            >管理者を剥奪
                                        </button>
                                        <button 
                                            type="button" 
                                            class="btn btn-success text-light rounded-pill py-1 mb-1" 
                                            @click="requestAuth(userFocusData.id, userFocusData.administrator)" 
                                            v-else
                                            >管理者を付与
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card card-body p-2 h-100">
                                    <!-- 処理 -->
                                    <div class="text-dark fw-bold">処理</div>
                                    <div>
                                        <button 
                                            type="button" 
                                            class="btn btn-danger text-light rounded-pill py-1 me-1 mb-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteUserModal"
                                            :disabled="userFocusData.administrator"
                                            >アカウント削除
                                        </button>
                                        <button 
                                            type="button" 
                                            class="btn btn-danger text-light rounded-pill py-1 mb-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#banUserModal"
                                            @click="
                                                    () => { 
                                                        let date = new Date();
                                                        userFocusData.start_date = 
                                                        date.getFullYear() + '-' + ('0' + String(date.getMonth() + 1)).slice(-2) + '-' + ('0' + String(date.getDate())).slice(-2);
                                                        userFocusData.level = 1;
                                                    }
                                                "
                                            :disabled="userFocusData.administrator"
                                            v-if="!userFocusBanData.ban"
                                            >アカウント停止
                                        </button>
                                        <button 
                                            type="button" 
                                            class="btn btn-success text-light rounded-pill py-1 mb-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#banUserResetModal"
                                            :disabled="userFocusData.administrator"
                                            v-else
                                            >アカウント解除
                                        </button>
                                    </div>
                                    <!-- 削除モーダル -->
                                    <div class="modal" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-bg-dark border">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title text-truncate fw-bold" id="deleteUserModalLabel" v-text="'ユーザを削除します'"></h5>
                                                </div>
                                                <div class="modal-body pt-0">
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                                        </svg>
                                                        <div class="fs-5 ms-2">本当に削除しますか?</div>
                                                    </div>
                                                    <div class="text-secondary mt-1">このユーザのデータはすべて削除されます</div>
                                                    <div class="text-secondary mt-1">復元はできません、続けますか?</div>
                                                    <div class="text-end mt-5">
                                                        <button type="button" class="btn btn-danger rounded-pill" @click="deleteUser(userFocusData.id)" data-bs-dismiss="modal">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 停止モーダル -->
                                    <div class="modal" id="banUserModal" tabindex="-1" aria-labelledby="banUserModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-bg-dark border">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title text-truncate fw-bold" id="banUserModalLabel" v-text="'ユーザを利用停止にします'"></h5>
                                                </div>
                                                <div class="modal-body pt-0">
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                                        </svg>
                                                        <div class="fs-5 ms-2">期間を指定してください</div>
                                                    </div>
                                                    <div class="my-3">
                                                        <label for="inputUserStartDate" class="form-label">開始日</label>
                                                        <input 
                                                            type="date" 
                                                            id="inputUserStartDate" 
                                                            class="form-control rounded-pill" 
                                                            v-model="userFocusData.start_date" 
                                                        />
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="inputUserLastDate" class="form-label">終了日</label>
                                                        <input 
                                                            type="date" 
                                                            id="inputUserLastDate" 
                                                            class="form-control rounded-pill" 
                                                            v-model="userFocusData.last_date" 
                                                        />
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="inputUserLevelDate" class="form-label">停止レベル(現在は固定)</label>
                                                        <input 
                                                            type="number" 
                                                            id="inputUserLevelDate" 
                                                            class="form-control rounded-pill" 
                                                            v-model="userFocusData.level" disabled 
                                                        />
                                                    </div>
                                                    <div class="text-end mt-5">
                                                        <button type="button" class="btn btn-danger rounded-pill" @click="stopUser(userFocusData.id)" data-bs-dismiss="modal">BAN</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 停止解除モーダル -->
                                    <div class="modal" id="banUserResetModal" tabindex="-1" aria-labelledby="banUserResetModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-bg-dark border">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title text-truncate fw-bold" id="banUserResetModalLabel" v-text="'ユーザの利用を解除します'"></h5>
                                                </div>
                                                <div class="modal-body pt-0">
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-stars" viewBox="0 0 16 16">
                                                            <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828l.645-1.937zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.734 1.734 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.734 1.734 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.734 1.734 0 0 0 3.407 2.31l.387-1.162zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L10.863.1z"/>
                                                        </svg>
                                                        <div class="fs-5 ms-2">本当に解除しますか?</div>
                                                    </div>
                                                    <div class="text-end mt-5">
                                                        <button 
                                                            type="button" 
                                                            class="btn btn-success rounded-pill" 
                                                            @click="stopUser(userFocusData.id)"
                                                            data-bs-dismiss="modal"
                                                            >RESET
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-else>
                    <div class="col-12 d-flex align-items-center justify-content-center fw-bold">
                        ユーザが未選択です
                    </div>
                </div>
            </div>
        </div>
        <div class="fw-bold">ユーザ一覧</div>
        <div class="card card-body mb-3">
            <div class="d-flex justify-content-center my-3" v-if="!userDatas">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="table-responsive" v-if="userDatas">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">姓</th>
                            <th scope="col">名</th>
                            <th scope="col">ニックネーム</th>
                            <th scope="col">メールアドレス</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="cursor: pointer;" v-for="userData in userDatas" :key=userData.id @click="getUserFocus(userData.id), getUserFocusBan(userData.id)">
                            <th scope="row" v-text="userData.id"></th>
                            <td v-text="userData.first_name"></td>
                            <td v-text="userData.last_name"></td>
                            <td v-text="userData.name"></td>
                            <td v-text="userData.email"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <!-- 下部通知バー -->
    <div class="toast-container p-3 bottom-0 start-50 translate-middle-x position-fixed z-3" data-original-class="toast-container p-3" v-if="isNotificalText">
        <div class="toast fade show d-flex align-items-center text-bg-dark">
            <div class="d-flex">
                <button type="button" class="btn border-0 p-0 ms-2 m-auto" @click="isNotificalText = ''">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                    </svg>
                </button>
                <div class="toast-body">
                    <div v-text="isNotificalText"></div>
                </div>
            </div>
        </div>
    </div>
</template>