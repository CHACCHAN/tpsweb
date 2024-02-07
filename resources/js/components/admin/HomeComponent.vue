<script setup>
    import { ref, onMounted, onUpdated, computed } from 'vue'
    import Cookies from 'js-cookie'
    import LineChart from './LineChart.vue'

    const props = defineProps({
        Menus: Array,
        Settings: Array,
    })
    const isShow = ref(true)
    const isInputEmailSubject = ref()
    const isInputEmailContent = ref()
    const isInputEmailDisabled = ref(false)
    const isDiscordInputPostContent = ref(null)
    const isDiscordCheckPostAutoMode = ref(null)
    const isDiscordCheckPostNotifical = ref(null)
    const isDiscordInputMediaContent = ref(null)
    const isDiscordCheckMediaAutoMode = ref(null)
    const isDiscordCheckMediaNotifical = ref(null)
    const isDiscordCheckMediaImage = ref(null)
    const isDiscordInputContent = ref(null)
    const isDiscordOnCookieChannelID = ref(null)
    const isNotificalText = ref()

    onMounted(() => {
        getDiscord()
        changeResponsive()
        isDiscordOnCookieChannelID.value = Cookies.get('discord_channel_id')
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

    // Discord
    const getDiscord = async() => {
        const getDiscordPostPromise = getDiscordPost()
        const getDiscordMediaPromise = getDiscordMedia()
        
        await Promise.all([getDiscordPostPromise, getDiscordMediaPromise])
    }

    const getDiscordPost = () => {
        return new Promise((resolve, reject) => {
            fetch('/tps-site/get/discord/post')
            .then((response) => response.json())
            .then(res => {
                isDiscordCheckPostAutoMode.value = Boolean(res.responseData.auto_mode)
                isDiscordCheckPostNotifical.value = Boolean(res.responseData.notifical_link)
                isDiscordInputPostContent.value = res.responseData.content
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

        
        fetch('/tps-site/post/discord/post', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                content: DiscordInputPostContent,
                auto_mode: DiscordCheckPostAutoMode,
                notifical_link: DiscordCheckPostNotifical
            }),
        })
        .then((response) => response.json())
        .then(res => {
            isNotificalText.value = '投稿の設定を更新しました'
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
                paste_image: DiscordCheckMediaImage
            }),
        })
        .then((response) => response.json())
        .then(res => {
            isNotificalText.value = 'メディア投稿の設定を更新しました'
        })
        .catch(error => {
            console.log(error)
        })
    }

    const uploadDiscordContent = (e) => {
        if(isDiscordInputContent.value) {
            const DiscordInputContent = isDiscordInputContent.value
            const DiscordOnCookieChannelID = isDiscordOnCookieChannelID.value
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
                    channel_id: DiscordOnCookieChannelID
                }),
            })
            .then((response) => response.json())
            .then(res => {
                e.target.innerHTML = beforeButtonHTML
                e.target.disabled = false
                isDiscordInputContent.value = ''
                isNotificalText.value = '正常に投稿されました'
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
                                            <td>v8.2.9</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Backend Server</th>
                                            <td>Laravel Framework v10</td>
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
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex align-items-center justify-content-start" data-bs-toggle="collapse" data-bs-target="#DiscordCollapse" aria-expanded="true" aria-controls="DiscordCollapse">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                        <div class="h4 m-0 ms-2">TPS Web to Discord</div>
                    </div>
                    <div class="collapse" :class="{ 'show': isShow }" id="DiscordCollapse">
                        <div class="card-body">
                            <!-- 投稿フォーム -->
                            <div class="card card-body mb-3">
                                <label for="toDiscordInputTextarea" class="form-label fw-bold">Discordに投稿する</label>
                                <textarea 
                                    class="form-control mb-3" 
                                    id="toDiscordInputTextarea" 
                                    style="height: 43px; resize: none; overflow: hidden;" 
                                    placeholder="入力してください"
                                    v-model="isDiscordInputContent"
                                    @input="autoResizeHeightTextarea"></textarea>
                                <label for="toDiscordFormSelect" class="form-label fw-bold">チャンネルID</label>
                                <input 
                                    type="number" 
                                    id="toDiscordFormSelect" 
                                    class="form-control" 
                                    v-model="isDiscordOnCookieChannelID" 
                                    @change="Cookies.set('discord_channel_id', isDiscordOnCookieChannelID)" />
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
                                <textarea 
                                    class="form-control border-primary border-2" 
                                    style="resize: none; overflow: hidden; height: 43px;" 
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
                                <textarea 
                                    class="form-control border-primary border-2" 
                                    style="resize: none; overflow: hidden; height: 43px;" 
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
                                <p class="text-secondary">※TPSアカウント登録者宛に一斉送信します</p>
                            </div>
                            <div class="text-end">
                                <button type="button" id="EmailPostSubmit" class="btn btn-success text-light rounded-pill" @click="postEmail()" :disabled="isInputEmailDisabled">配信する</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- TPS Webニュース -->
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex align-items-center justify-content-start" data-bs-toggle="collapse" data-bs-target="#NewsCollapse" aria-expanded="true" aria-controls="NewsCollapse">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                        <div class="h4 m-0 ms-2">TPS Web イベントとニュース</div>
                    </div>
                    <div class="collapse" :class="{ 'show': isShow }" id="NewsCollapse">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">@</th>
                                        <th scope="col">お知らせ</th>
                                        <th scope="col">日付</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">#</th>
                                        <td>TPS Web をリリース！</td>
                                        <td>2024年1月1日</td>
                                    </tr>
                                </tbody>
                            </table>
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