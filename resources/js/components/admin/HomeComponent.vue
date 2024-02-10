<script setup>
    import { ref, onMounted, onUpdated, computed } from 'vue'
    import Cookies from 'js-cookie'
    import LineChart from './LineChart.vue'

    const props = defineProps({
        Menus: Array,
        Settings: Array,
    })
    const laravelVersion = ref(null)
    const phpVersion = ref(null)
    const isShow = ref(true)
    const isInputEmailSubject = ref()
    const isInputEmailContent = ref()
    const isInputEmailDisabled = ref(false)
    const isPickUpSumCount = ref(null)
    const isPickUpGetCount = ref(3)
    const isPickUpLastID = ref(null)
    const isPickUpLists = ref([])
    const isPickUpInputTitle = ref(null)
    const isPickUpInputContent = ref(null)
    const isPickUpClickedID = ref(null)
    const isPickUpClickedTitle = ref(null)
    const isPickUpClickedContent = ref(null)
    const isDiscordInputPostWebhook = ref(null)
    const isDiscordInputPostContent = ref(null)
    const isDiscordCheckPostAutoMode = ref(null)
    const isDiscordCheckPostNotifical = ref(null)
    const isDiscordInputMediaWebhook = ref(null)
    const isDiscordInputMediaContent = ref(null)
    const isDiscordCheckMediaAutoMode = ref(null)
    const isDiscordCheckMediaNotifical = ref(null)
    const isDiscordCheckMediaImage = ref(null)
    const isDiscordInputContent = ref(null)
    const isDiscordOnCookieWebhook = ref(null)
    const isDiscordLoading = ref(false)
    const isNotificalText = ref()

    onMounted(() => {
        getLaravelVersion()
        getPHPVersion()
        getDiscord()
        changeResponsive()
        getPickUpSubCount()
        getPickUp(false)
        isDiscordOnCookieWebhook.value = Cookies.get('discord_webhook')
        window.addEventListener('resize', changeResponsive)
    })

    onUpdated(() => {
        if(isNotificalText.value) {
            setTimeout(() => {
                isNotificalText.value = ''
            }, 2000)
        }
    })

    const autoResizeHeightTextarea = (e) => {
        const PADDING_Y = 20
        const textarea = e.target
        let lineHeight = getComputedStyle(textarea).lineHeight

        lineHeight = lineHeight.replace(/[^-\d\.]/g, '')

        const lines = (textarea.value + '\n').match(/\n/g).length

        textarea.style.height = lineHeight * lines + PADDING_Y + 'px'
    }

    const changeResponsive = () => {
        if(window.screen.width <= 768) {
            isShow.value = false
        } else {
            isShow.value = true
        }
    }

    const postEmail = () => {
        let EmailPostSubmit = document.getElementById('EmailPostSubmit')
        let beforeText = EmailPostSubmit.innerHTML

        if(isInputEmailSubject.value && isInputEmailContent.value) {
            isInputEmailDisabled.value = true
            EmailPostSubmit.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   配信中...'

            fetch('/tps-site/email/post', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    subject: isInputEmailSubject.value,
                    content: isInputEmailContent.value,
                }),
            })
            .then((response) => response.json())
            .then(res => {
                document.querySelector('meta[name="csrf-token"]').content = res.csrf
                isNotificalText.value = '正常にメールが送信されました'
                reset()
            })
            .catch(error => {
                console.log(error)
                reset()
            })
        }

        function reset() {
            isInputEmailDisabled.value = false
            EmailPostSubmit.innerHTML = beforeText
            isInputEmailSubject.value = ""
            isInputEmailContent.value = ""
        }
    }

    const getLaravelVersion = () => {
        fetch('/tps-site/get/env/laravel')
        .then((response) => response.json())
        .then(res => {
            laravelVersion.value = res.responseData
        })
        .catch(error => {
            console.log(error)
        })
    }

    const getPHPVersion = () => {
        fetch('/tps-site/get/env/php')
        .then((response) => response.json())
        .then(res => {
            phpVersion.value = res.responseData
        })
        .catch(error => {
            console.log(error)
        })
    }

    // PickUp
    const getPickUpSubCount = () => {
        fetch('/tps-site/get/pickup/sumCount')
        .then((response) => response.json())
        .then(res => {
            isPickUpSumCount.value = res.responseData
        })
        .catch(error => {
            console.log(error)
        })
    }

    const getNewPickUp = () => {
        const getCount = isPickUpLists.value.length
        isPickUpLists.value = new Array()

        fetch('/tps-site/post/pickup/focus', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                getCount: getCount,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            for(let i = 0; i < res.responseData.length; i++) {
                isPickUpLists.value.push(res.responseData[i])
                isPickUpLastID.value = res.responseData[i].id
            }
        })
        .catch(error => {
            console.log(error)
        })
    }

    const getPickUp = (viewFlag) => {
        let beforeButtonHTML = null
        if(viewFlag) {
            beforeButtonHTML = viewFlag.target.innerHTML
            viewFlag.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   読み込み中'
            viewFlag.target.disabled = true
        }
        const id = isPickUpLastID.value
        const getCount = isPickUpGetCount.value

        fetch('/tps-site/post/pickup/focus', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: id,
                getCount: getCount,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            for(let i = 0; i < res.responseData.length; i++) {
                isPickUpLists.value.push(res.responseData[i])
                isPickUpLastID.value = res.responseData[i].id
            }
            
            if(viewFlag) {
                viewFlag.target.innerHTML = beforeButtonHTML
                viewFlag.target.disabled = false
            }
        })
        .catch(error => {
            console.log(error)
        })
    }

    const createPickUp = (e) => {
        if(isPickUpInputTitle.value && isPickUpInputContent.value) {
            const PickUpInputTitle = isPickUpInputTitle.value
            const pickUpInputContent = isPickUpInputContent.value
            const beforeButtonHTML = e.target.innerHTML
            e.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   送信中'
            e.target.disabled = true

            fetch('/tps-site/post/pickup/create', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    title: PickUpInputTitle,
                    content: pickUpInputContent,
                }),
            })
            .then((response) => response.json())
            .then(res => {
                e.target.innerHTML = beforeButtonHTML
                e.target.disabled = false
                isPickUpInputTitle.value = ''
                isPickUpInputContent.value = ''
                if(res.responseData) {
                    isNotificalText.value = '正常に送信されました'
                    getPickUpSubCount()
                    getNewPickUp()
                } else {
                    isNotificalText.value = '送信に失敗しました'
                }
            })
            .catch(error => {
                console.log(error)
            })
        }
    }

    const deletePickUp = () => {
        const PickUpClickedID = isPickUpClickedID.value

        fetch('/tps-site/post/pickup/delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: PickUpClickedID,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            if(res.responseData) {
                isNotificalText.value = 'ピックアップが削除されました'
                getPickUpSubCount()
                getNewPickUp()
            } else {
                isNotificalText.value = '削除に失敗しました'
            }
        })
        .catch(error => {
            console.log(error)
        })
    }

    // Discord
    const getDiscord = async() => {
        const getDiscordPostPromise = getDiscordPost()
        const getDiscordMediaPromise = getDiscordMedia()
        
        await Promise.all([getDiscordPostPromise, getDiscordMediaPromise])

        isDiscordLoading.value = true
    }

    const getDiscordPost = () => {
        return new Promise((resolve, reject) => {
            fetch('/tps-site/get/discord/post')
            .then((response) => response.json())
            .then(res => {
                isDiscordCheckPostAutoMode.value = Boolean(res.responseData.auto_mode)
                isDiscordCheckPostNotifical.value = Boolean(res.responseData.notifical_link)
                isDiscordInputPostContent.value = res.responseData.content
                isDiscordInputPostWebhook.value = res.responseData.webhook
                resolve()
            })
            .catch(error => {
                console.log(error)
                reject(error)
            })
        })
    }

    const getDiscordMedia = () => {
        return new Promise((resolve, reject) => {
            fetch('/tps-site/get/discord/media')
            .then((response) => response.json())
            .then(res => {
                isDiscordCheckMediaAutoMode.value = Boolean(res.responseData.auto_mode)
                isDiscordCheckMediaNotifical.value = Boolean(res.responseData.notifical_link)
                isDiscordInputMediaContent.value = res.responseData.content
                isDiscordCheckMediaImage.value = Boolean(res.responseData.paste_image)
                isDiscordInputMediaWebhook.value = res.responseData.webhook
                resolve()
            })
            .catch(error => {
                console.log(error)
                reject(error)
            })
        })
    }

    const updateDiscordPost = () => {
        const DiscordInputPostContent = isDiscordInputPostContent.value
        const DiscordCheckPostAutoMode = isDiscordCheckPostAutoMode.value
        const DiscordCheckPostNotifical = isDiscordCheckPostNotifical.value
        const DiscordInputPostWebhook = isDiscordInputPostWebhook.value
        
        fetch('/tps-site/post/discord/post', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                content: DiscordInputPostContent,
                auto_mode: DiscordCheckPostAutoMode,
                notifical_link: DiscordCheckPostNotifical,
                webhook: DiscordInputPostWebhook
            }),
        })
        .then((response) => response.json())
        .then(res => {
            if(res.responseData) {
                isNotificalText.value = '投稿の設定を更新しました'
            } else {
                isNotificalText.value = '投稿の設定に失敗しました'
            }
        })
        .catch(error => {
            console.log(error)
        })
    }

    const updateDiscordMedia = () => {
        const DiscordInputMediaContent = isDiscordInputMediaContent.value
        const DiscordCheckMediaAutoMode = isDiscordCheckMediaAutoMode.value
        const DiscordCheckMediaNotifical = isDiscordCheckMediaNotifical.value
        const DiscordCheckMediaImage = isDiscordCheckMediaImage.value
        const DiscordInputMediaWebhook = isDiscordInputMediaWebhook.value

        fetch('/tps-site/post/discord/media', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                content: DiscordInputMediaContent,
                auto_mode: DiscordCheckMediaAutoMode,
                notifical_link: DiscordCheckMediaNotifical,
                paste_image: DiscordCheckMediaImage,
                webhook: DiscordInputMediaWebhook
            }),
        })
        .then((response) => response.json())
        .then(res => {
            if(res.responseData) {
                isNotificalText.value = 'メディア投稿の設定を更新しました'
            } else {
                isNotificalText.value = 'メディア投稿の設定を更新できませんでした'
            }
        })
        .catch(error => {
            console.log(error)
        })
    }

    const uploadDiscordContent = (e) => {
        if(isDiscordInputContent.value) {
            const DiscordInputContent = isDiscordInputContent.value
            const DiscordOnCookieWebhook = isDiscordOnCookieWebhook.value
            const beforeButtonHTML = e.target.innerHTML
            e.target.disabled = true
            e.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   送信中'

            fetch('/tps-site/post/discord/send', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    content: DiscordInputContent,
                    webhook: DiscordOnCookieWebhook
                }),
            })
            .then((response) => response.json())
            .then(res => {
                e.target.innerHTML = beforeButtonHTML
                e.target.disabled = false
                isDiscordInputContent.value = ''
                if(res.responseData) {
                    isNotificalText.value = '正常に投稿されました'
                } else {
                    isNotificalText.value = '投稿できませんでした'
                }
            })
            .catch(error => {
                console.log(error)
            })
        }
    }
</script>

<template>
    <div class="container">
        <div class="h4">ダッシュボード</div>
        <!-- 固定トップビュー -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="h4 m-0">TPS Webクリエイターへようこそ!</div>
                <p class="text-secondary mb-5">基本的な機能は以下のものを提供しています</p>
                <!-- 登録メニュー -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 col-md-3" v-for="Menu in Menus" :key="Menu">
                                <div class="fw-bold" v-text="Menu.menuTitle"></div>
                                <p class="text-secondary" v-text="Menu.menuExplanation"></p>
                            </div>
                            <div class="col-6 col-md-3" v-for="Setting in Settings" :key="Setting">
                                <div class="fw-bold" v-text="Setting.settingTitle"></div>
                                <p class="text-secondary" v-text="Setting.settingExplanation"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Webの詳細 -->
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th scope="row">TPS Web</th>
                                            <td>v1.0</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">PHP Version</th>
                                            <td v-text="'v' + phpVersion"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Backend Server</th>
                                            <td v-text="'Laravel Framework v' + laravelVersion"></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Developer</th>
                                            <td>CHACCHAN</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Release</th>
                                            <td>CHACCHAN SYSTEM</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 動的ビュー -->
        <div class="row">
            <div class="col-12 col-md-6">
                <!-- 閲覧状況 -->
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex align-items-center justify-content-start" data-bs-toggle="collapse" data-bs-target="#SeeSiteCollapse" aria-expanded="true" aria-controls="SeeSiteCollapse">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                        <div class="h4 m-0 ms-2">サイト閲覧情報</div>
                    </div>
                    <div class="collapse" :class="{ 'show': isShow }" id="SeeSiteCollapse">
                        <div class="card-body">
                            <LineChart />
                        </div>
                    </div>
                </div>
                <!-- TPS Web to Discord -->
                <!-- 読み込み用 -->
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex align-items-center justify-content-start" data-bs-toggle="collapse" data-bs-target="#DiscordCollapse" aria-expanded="true" aria-controls="DiscordCollapse">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                        <div class="h4 m-0 ms-2">TPS Web to Discord</div>
                    </div>
                    <!-- 読み込み前 -->
                    <div class="collapse show" id="DiscordCollapse" v-if="!isDiscordLoading">
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 読み込み後 -->
                    <div class="collapse" :class="{ 'show': isShow }" id="DiscordCollapse" v-if="isDiscordLoading">
                        <div class="card-body">
                            <!-- 投稿フォーム -->
                            <div class="card card-body mb-3">
                                <!-- コンテンツ -->
                                <label for="toDiscordInputTextarea" class="form-label fw-bold">Discordに投稿する</label>
                                <textarea 
                                    class="form-control mb-3" 
                                    id="toDiscordInputTextarea" 
                                    style="height: 43px; resize: none; overflow: hidden;" 
                                    placeholder="入力してください"
                                    v-model="isDiscordInputContent"
                                    @input="autoResizeHeightTextarea"></textarea>
                                <!-- Webhook -->
                                <label for="toDiscordFormSelect" class="form-label fw-bold">Webhook</label>
                                <input 
                                    type="text" 
                                    id="toDiscordFormSelect" 
                                    class="form-control" 
                                    v-model="isDiscordOnCookieWebhook" 
                                    @change="Cookies.set('discord_webhook', isDiscordOnCookieWebhook)" />
                                <div class="form-text">※入力するとCookieに保存されます</div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-success text-light rounded-pill" @click="uploadDiscordContent">送信</button>
                                </div>
                            </div>
                            <!-- 自動通知設定 -->
                            <div class="fs-6 text-success fw-bold">自動通知機能の設定</div>
                            <!-- 投稿設定 -->
                            <div class="mb-3">
                                <div class="text-secondary">投稿の設定</div>
                                <!-- Webhook -->
                                <div class="form-floating mb-3">
                                    <input
                                        type="text" 
                                        class="form-control border-primary border-2" 
                                        id="inputDiscordPostWebhook" 
                                        placeholder="Leave a comment here" 
                                        rows="1"
                                        cols="1"
                                        v-model="isDiscordInputPostWebhook" 
                                        @change="updateDiscordPost()" />
                                    <label for="inputDiscordPostWebhook">Webhook</label>
                                </div>
                                <!-- コンテンツ -->
                                <textarea 
                                    class="form-control border-primary border-2" 
                                    style="resize: none; overflow: hidden; height: 43px;" 
                                    placeholder="テキストを入力"
                                    v-model="isDiscordInputPostContent"
                                    @input="autoResizeHeightTextarea"
                                    @change="updateDiscordPost()"
                                    @click="autoResizeHeightTextarea"
                                    @blur="(e) => {e.target.style.height = '43px'}"></textarea>
                                <div class="form-text text-primary">公開時に動作します</div>
                                <!-- チェックボックス -->
                                <div class="d-flex">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" v-model="isDiscordCheckPostAutoMode" @change="updateDiscordPost()" id="flexCheckDiscordPostAutoMode">
                                        <label class="form-check-label" for="flexCheckDiscordPostAutoMode">
                                            自動化
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" v-model="isDiscordCheckPostNotifical" @change="updateDiscordPost()" id="flexCheckDiscordPostNotifical">
                                        <label class="form-check-label" for="flexCheckDiscordPostNotifical">
                                            リンクの添加
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- メディア設定 -->
                            <div class="">
                                <div class="text-secondary">メディア投稿の設定</div>
                                <!-- Webhook -->
                                <div class="form-floating mb-3">
                                    <input
                                        type="text" 
                                        class="form-control border-primary border-2" 
                                        id="inputDiscordMediaWebhook" 
                                        placeholder="Leave a comment here" 
                                        v-model="isDiscordInputMediaWebhook" 
                                        @change="updateDiscordPost()" />
                                    <label for="inputDiscordMediaWebhook">Webhook</label>
                                </div>
                                <!-- コンテンツ -->
                                <textarea 
                                    class="form-control border-primary border-2" 
                                    style="resize: none; overflow: hidden; height: 43px;" 
                                    placeholder="テキストを入力"
                                    v-model="isDiscordInputMediaContent"
                                    @input="autoResizeHeightTextarea"
                                    @change="updateDiscordMedia()"
                                    @click="autoResizeHeightTextarea"
                                    @blur="(e) => {e.target.style.height = '43px'}"></textarea>
                                <div class="form-text text-primary">投稿時に動作します</div>
                                <!-- チェックボックス -->
                                <div class="d-flex">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" v-model="isDiscordCheckMediaAutoMode" @change="updateDiscordMedia()" id="flexCheckDiscordMediaAutoMode">
                                        <label class="form-check-label" for="flexCheckDiscordMediaAutoMode">
                                            自動化
                                        </label>
                                    </div>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" v-model="isDiscordCheckMediaNotifical" @change="updateDiscordMedia()" id="flexCheckDiscordMediaNotifical">
                                        <label class="form-check-label" for="flexCheckDiscordMediaNotifical">
                                            リンクの添加
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" v-model="isDiscordCheckMediaImage" @change="updateDiscordMedia()" id="flexCheckDiscordMediaImage">
                                        <label class="form-check-label" for="flexCheckDiscordMediaImage">
                                            画像の添加
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <!-- メール配信 -->
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex align-items-center justify-content-start" data-bs-toggle="collapse" data-bs-target="#EmailCollapse" aria-expanded="true" aria-controls="EmailCollapse">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                        <div class="h4 m-0 ms-2">メール配信</div>
                    </div>
                    <div class="collapse" :class="{ 'show': isShow }" id="EmailCollapse">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="inputEmailSubject" class="form-label fw-bold">Subject</label>
                                <input type="text" class="form-control" id="inputEmailSubject" v-model="isInputEmailSubject">
                            </div>
                            <div class="mb-3">
                                <label for="inputEmailContent" class="form-label fw-bold">Content</label>
                                <textarea 
                                    class="form-control" 
                                    id="inputEmailContent" 
                                    style="height: 44px; resize: none; overflow: hidden;"
                                    v-model="isInputEmailContent" 
                                    placeholder="TPSユーザーにアイデアを配信してみましょう"
                                    @input="autoResizeHeightTextarea">
                                </textarea>
                                <div class="form-text">※TPSアカウント登録者宛に一斉送信します</div>
                            </div>
                            <div class="text-end">
                                <button 
                                    type="button" 
                                    id="EmailPostSubmit" 
                                    class="btn btn-success text-light py-1 rounded-pill" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#mailCheckModal" 
                                    :disabled="isInputEmailDisabled"
                                    v-if="isInputEmailSubject && isInputEmailContent"
                                    >配信する
                                </button>
                                <button type="button" id="EmailPostSubmit" class="btn btn-success text-light py-1 rounded-pill" v-else>配信する</button>
                            </div>
                        </div>
                    </div>
                    <!-- メール配信確認モーダル -->
                    <div class="modal" id="mailCheckModal" tabindex="-1" aria-labelledby="mailCheckModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content text-bg-dark border">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title text-truncate fw-bold" id="mailCheckModalLabel" v-text="'メールを一斉送信します'"></h5>
                                </div>
                                <div class="modal-body pt-0">
                                    <div class="d-flex align-items-center justify-content-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-envelope-check-fill" viewBox="0 0 16 16">
                                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.026A2 2 0 0 0 2 14h6.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586l-1.239-.757ZM16 4.697v4.974A4.491 4.491 0 0 0 12.5 8a4.49 4.49 0 0 0-1.965.45l-.338-.207L16 4.697Z"/>
                                            <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686Z"/>
                                        </svg>
                                        <div class="fs-5 ms-2">続行しますか?</div>
                                    </div>
                                    <div class="text-secondary mt-1">内容に問題なければ続行してください</div>
                                    <div class="text-end mt-5">
                                        <button type="button" class="btn btn-success rounded-pill" @click="postEmail()" data-bs-dismiss="modal">OK</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- TPS Web ピックアップ -->
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex align-items-center justify-content-start" data-bs-toggle="collapse" data-bs-target="#NewsCollapse" aria-expanded="true" aria-controls="NewsCollapse">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                        <div class="h4 m-0 ms-2">TPS Web ピックアップ</div>
                    </div>
                    <div class="collapse" :class="{ 'show': isShow }" id="NewsCollapse">
                        <div class="card-body">
                            <!-- フォーム -->
                            <div class="card card-body mb-3">
                                <div class="mb-3">
                                    <label for="inputPickUpTitle" class="form-label fw-bold">Title</label>
                                    <input type="text" v-model="isPickUpInputTitle" id="inputPickUpTitle" class="form-control" />
                                </div>
                                <div class="mb-3">
                                    <label for="inputPickUpContent" class="form-label fw-bold">Content</label>
                                    <textarea 
                                        v-model="isPickUpInputContent"
                                        class="form-control" 
                                        id="inputPickUpContent" 
                                        style="height: 44px; resize: none; overflow: hidden;"
                                        @input="autoResizeHeightTextarea"></textarea>
                                    <div class="form-text">※30件を超えると古い順に削除されます(現在 {{ isPickUpSumCount }}件)</div>
                                    <div class="form-text">※ピックアップは送信後、編集はできません</div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-success rounded-pill text-light py-1" v-if="isPickUpSumCount == 30 && isPickUpInputTitle && isPickUpInputContent" data-bs-toggle="modal" data-bs-target="#pickupSumMax">送信</button>
                                    <button type="button" class="btn btn-success rounded-pill text-light py-1" v-else-if="isPickUpSumCount != 30" @click="createPickUp">送信</button>
                                    <button type="button" class="btn btn-success rounded-pill text-light py-1" v-else @click="createPickUp">送信</button>
                                </div>
                            </div>
                            <!-- 履歴 -->
                            <div class="fw-bold text-success" v-text="'履歴 (上限30中' + isPickUpSumCount + '件)'"></div>
                            <table class="table table-hover">
                                <thead>
                                    <tr class="row">
                                        <th class="col-1 text-center">@</th>
                                        <th class="col-8">タイトル</th>
                                        <th class="col-3">時間</th>
                                    </tr>
                                </thead>
                                <div class="text-center mt-3" v-if="!isPickUpLists[0]">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <tbody v-if="isPickUpLists[0]">
                                    <tr class="row" 
                                        v-for="isPickUpList in isPickUpLists" 
                                        :key="isPickUpList.id" 
                                        style="cursor: pointer;" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#pickupExampleModal"
                                        @click="
                                            isPickUpClickedID = isPickUpList.id,
                                            isPickUpClickedTitle = isPickUpList.title,
                                            isPickUpClickedContent = isPickUpList.content
                                        "
                                    >
                                        <td class="col-1 text-center" v-text="'#'"></td>
                                        <td class="col-8 text-truncate" v-text="isPickUpList.title"></td>
                                        <td class="col-3 text-truncate" v-text="isPickUpList.created_at"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- ピックアップ上限警告モーダル -->
                            <div class="modal" id="pickupSumMax" tabindex="-1" aria-labelledby="pickupSumMaxLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-bg-dark border">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title text-truncate fw-bold" id="pickupSumMaxLabel" v-text="'上限を超えてしまいます'"></h5>
                                        </div>
                                        <div class="modal-body pt-0">
                                            <div class="d-flex align-items-center justify-content-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-device-hdd" viewBox="0 0 16 16">
                                                    <path d="M12 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Zm0 11a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Zm-7.5.5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1ZM5 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM8 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"/>
                                                    <path d="M12 7a4 4 0 0 1-3.937 4c-.537.813-1.02 1.515-1.181 1.677a1.102 1.102 0 0 1-1.56-1.559c.1-.098.396-.314.795-.588A4 4 0 0 1 8 3a4 4 0 0 1 4 4Zm-1 0a3 3 0 1 0-3.891 2.865c.667-.44 1.396-.91 1.955-1.268.224-.144.483.115.34.34l-.62.96A3.001 3.001 0 0 0 11 7Z"/>
                                                    <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2Zm2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H4Z"/>
                                                </svg>
                                                <div class="fs-5 ms-2">続行しますか?</div>
                                            </div>
                                            <div class="text-secondary mt-1">このまま続行すると、一番古いデータから削除されます。</div>
                                            <div class="text-end mt-5">
                                                <button type="button" class="btn btn-success rounded-pill" @click="createPickUp" data-bs-dismiss="modal">OK</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ピックアップ詳細モーダル -->
                            <div class="modal fade" id="pickupExampleModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content text-bg-dark">
                                        <div class="modal-body">
                                            <div class="text-end">
                                                <button type="button" id="clickItem" class="btn border-0 p-2 rounded-circle" data-bs-dismiss="modal" @click="deletePickUp()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="fw-bold fs-5 border-bottom">タイトル</div>
                                            <p v-text="isPickUpClickedTitle"></p>
                                            <div class="fw-bold fs-5 border-bottom">コンテンツ</div>
                                            <p v-text="isPickUpClickedContent"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="clickItem" class="btn border-0 rounded-pill py-1" @click="getPickUp">続きを読み込む</button>
                        </div>
                    </div>
                </div>  
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

<style scoped>
    #clickItem:hover {
        color: white !important;
        background: #3d3d3d;
    }
</style>