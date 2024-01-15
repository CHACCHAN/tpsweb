<script setup>
    import { ref, onMounted, computed } from 'vue'
    import LineChart from './LineChart.vue'

    const props = defineProps({
        Menus: Array,
        Settings: Array,
    })
    const isShow = ref(true)

    onMounted(() => {
        changeResponsive()
        window.addEventListener('resize', changeResponsive)
    })

    const changeResponsive = () => {
        if(window.screen.width <= 768) {
            isShow.value = false
        } else {
            isShow.value = true
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
                            未実装
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
                            未実装
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
</template>