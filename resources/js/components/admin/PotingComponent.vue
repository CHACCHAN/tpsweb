<script setup>
    import { ref, onMounted, onUpdated } from 'vue'
    import LoadingComponent from '../../components/loadingcomponent.vue'
    import Cookie from 'js-cookie'
    import { QuillEditor } from '@vueup/vue-quill'
    import '@vueup/vue-quill/dist/vue-quill.snow.css'
    import '@vueup/vue-quill/dist/vue-quill.bubble.css'

    const isLoading = ref(true)
    const isPostData = ref([])
    const isPostCategory = ref([])
    const isListView = ref([true])
    const isTheme = ref('snow')
    const Permalink = location.protocol + '//' + location.hostname + '/tps-site/'
    const editorContent = ref()
    
    onMounted(() => {
        getPosts()
        changeResponsive()
        window.addEventListener('resize', changeResponsive)
    })

    const updateEditorContent = async (newContent) => {
        // テキストデータの適用 
        editorContent.value = newContent
    }

    const uploadTempImg = () => {
        new Promise((resolve, reject) => {
            const imgList = Array.from(document.getElementById('QuillEditor').querySelectorAll('img'))

            for(let index in imgList) {
                fetch('/tps-site/post/postdata/image/temp', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'enctype': 'multipart/form-data',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        'image': imgList[index].src,
                    }),
                })
                .then((response) => response.json())
                .then(res => {
                    imgList[index].src = res.image
                    resolve()
                })
                .catch(error => {
                    console.log(error)
                    reject()
                })
            }
        })

        // uploadTempImg
        // .then(result => console.log('OK'))
        // .catch(erroe => console.log('ERROR'))
    }

    const postAllChecked = () => {
        const inputPostAllItem = document.getElementById("inputPostAllItem")
        const inputPostItems = document.querySelectorAll("#inputPostItem")

        if (inputPostAllItem.checked) {
            inputPostItems.forEach((item) => {
                item.checked = true
            })
        } else {
            inputPostItems.forEach((item) => {
                item.checked = false
            })
        }
    }

    const getPosts = () => {
        getPostData()
        getPostCategory()
    }

    const getPostData = () => {
        fetch('/tps-site/get/postdata')
        .then((response) => response.json())
        .then(res => {
            isPostData.value = res.responseData
            isLoading.value = false
            console.log(isPostData.value)
        })
        .catch(error => {
            console.log(error)
        })
    }

    const getPostCategory = () => {
        fetch('/tps-site/get/postcategory')
        .then((response) => response.json())
        .then(res => {
            isPostCategory.value = res.responseData
            isLoading.value = false
            console.log(isPostCategory.value)
        })
        .catch(error => {
            console.log(error)
        })
    }

    const setToolMenu = (e) => {
        isListView.value = new Array()

        switch(e) {
            case 0: // リスト
                isListView.value[e] = true
                break
            case 1: // 新規登録
                isListView.value[e] = true
                break
            default:
                isListView.value[0] = true
        }
    }

    const changeResponsive = () => {
        if(window.screen.width <= 768) {
            isTheme.value = 'bubble'
        } else {
            isTheme.value = 'snow'
        }
    }
</script>

<template>
    <LoadingComponent v-if="isLoading" />
    <div class="container" v-if="!isLoading">
        <div class="h4">投稿</div>
        <!-- ツールメニュー -->
        <div class="d-flex mb-3">
            <!-- リスト -->
            <div class="card text-bg-secondary border-0 rounded-pill me-1">
                <div id="clickItem" class="card-body btn rounded-pill border-0 p-0 px-2" :class="{ 'clicked':isListView[0] }" @click="setToolMenu(0)">
                    リスト
                </div>
            </div>
            <!-- 新規登録 -->
            <div class="card text-bg-secondary border-0 rounded-pill">
                <div id="clickItem" class="card-body btn rounded-pill border-0 p-0 px-2" :class="{ 'clicked':isListView[1] }" @click="setToolMenu(1)">
                    新規追加
                </div>
            </div>
            <div class="vr mx-2"></div>
        </div>
        
        <!-- リストView -->
        <div class="card" v-if="isListView[0]">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">
                                <input id="inputPostAllItem" type="checkbox" class="form-check-input border-secondary" @input="postAllChecked()">
                            </th>
                            <th scope="col">タイトル</th>
                            <th scope="col">作成者</th>
                            <th scope="col">カテゴリ</th>
                            <th scope="col">日付</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- リスト -->
                        <tr>
                            <th scope="row">
                                <input id="inputPostItem" type="checkbox" class="form-check-input border-secondary">
                            </th>
                            <td><div class="text-truncate" style="width: 140px;">ああああああああああああああああああああああああああああああああああああああ</div></td>
                            <td>CHACCHAN</td>
                            <td>分類なし</td>
                            <td>2024年1月1日</td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <input id="inputPostItem" type="checkbox" class="form-check-input border-secondary">
                            </th>
                            <td><div class="text-truncate" style="width: 140px;">ああああああああああああああああああああああああああああああああああああああ</div></td>
                            <td>CHACCHAN</td>
                            <td>分類なし</td>
                            <td>2024年1月1日</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 新規追加View -->
        <div v-if="isListView[1]">
            <div class="row">
                <!-- メイン -->
                <div class="col-12 col-md-9">
                    <!-- 基本 -->
                    <div class="mb-3">
                        <input type="text" class="form-control rounded-pill border-dark" placeholder="タイトルを入力してください">
                        <div class="d-flex align-items-center justify-content-start text-secondary mt-1">
                            <div class="fw-bold">パーマリンク</div>
                            <div class="mx-2">:</div>
                            <a :href="Permalink" class="me-2" target="_blank" v-text="Permalink"></a>
                            <div id="preView" class="btn border-primary p-1 py-0 text-primary">編集</div>
                        </div>
                    </div>
                    <!-- テキストエディタ -->
                    <div id="QuillEditor" class="border mb-3">
                        <QuillEditor class="editor" :theme="isTheme" toolbar="full" v-model="editorContent" contentType="html" @update:content="updateEditorContent" placeholder="入力してください" />
                    </div>
                    <button type="button" class="btn btn-primary" @click="uploadTempImg()">下書き保存</button>
                    <!-- プレビュー -->
                    <div class="ql-snow">
                        <div class="ql-editor" v-html="editorContent"></div>
                    </div>
                </div>
                <!-- サブ -->
                <div class="col-12 col-md-3">
                    <!-- 公開設定 -->
                    <div class="card text-bg-secondary text-dark mb-3 ">
                        <div class="card-header d-flex align-items-center justify-content-start px-3 py-0" data-bs-toggle="collapse" data-bs-target="#publicSetting" aria-expanded="true" aria-controls="publicSetting" style="background: #b3b1b1;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                            <div class="fs-5 ms-2">公開</div>
                        </div>
                        <div class="collapse show" id="publicSetting">
                            <div class="card-body rounded-bottom px-3 py-1" style="background: rgb(231, 227, 227);">
                                <!-- プレビュー -->
                                <button type="button" id="preView" class="btn border-primary text-primary rounded-pill py-1 w-100">
                                    プレビュー
                                </button>
                                <!-- 公開設定 -->
                                <div class="d-flex align-items-center justify-content-start text-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                    </svg>
                                    <div class="fs-6 ms-1">公開設定:</div>
                                    <div class="fw-bold mx-1">非公開</div>
                                    <div class="btn border-0 p-0 text-primary text-decoration-underline">編集</div>
                                </div>
                                <!-- すぐに公開する -->
                                <div class="d-flex align-items-center justify-content-start text-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-globe-asia-australia" viewBox="0 0 16 16">
                                        <path d="m10.495 6.92 1.278-.619a.483.483 0 0 0 .126-.782c-.252-.244-.682-.139-.932.107-.23.226-.513.373-.816.53l-.102.054c-.338.178-.264.626.1.736a.476.476 0 0 0 .346-.027ZM7.741 9.808V9.78a.413.413 0 1 1 .783.183l-.22.443a.602.602 0 0 1-.12.167l-.193.185a.36.36 0 1 1-.5-.516l.112-.108a.453.453 0 0 0 .138-.326ZM5.672 12.5l.482.233A.386.386 0 1 0 6.32 12h-.416a.702.702 0 0 1-.419-.139l-.277-.206a.302.302 0 1 0-.298.52l.761.325Z"/>
                                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0ZM1.612 10.867l.756-1.288a1 1 0 0 1 1.545-.225l1.074 1.005a.986.986 0 0 0 1.36-.011l.038-.037a.882.882 0 0 0 .26-.755c-.075-.548.37-1.033.92-1.099.728-.086 1.587-.324 1.728-.957.086-.386-.114-.83-.361-1.2-.207-.312 0-.8.374-.8.123 0 .24-.055.318-.15l.393-.474c.196-.237.491-.368.797-.403.554-.064 1.407-.277 1.583-.973.098-.391-.192-.634-.484-.88-.254-.212-.51-.426-.515-.741a6.998 6.998 0 0 1 3.425 7.692 1.015 1.015 0 0 0-.087-.063l-.316-.204a1 1 0 0 0-.977-.06l-.169.082a1 1 0 0 1-.741.051l-1.021-.329A1 1 0 0 0 11.205 9h-.165a1 1 0 0 0-.945.674l-.172.499a1 1 0 0 1-.404.514l-.802.518a1 1 0 0 0-.458.84v.455a1 1 0 0 0 1 1h.257a1 1 0 0 1 .542.16l.762.49a.998.998 0 0 0 .283.126 7.001 7.001 0 0 1-9.49-3.409Z"/>
                                    </svg>
                                    <div class="fs-6 ms-1">すぐに公開する:</div>
                                    <div class="fw-bold mx-1">あとで</div>
                                    <div class="btn border-0 p-0 text-primary text-decoration-underline">編集</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- カテゴリ設定 -->
                    <div class="card text-bg-secondary text-dark">
                        <div class="card-header d-flex align-items-center justify-content-start px-3 py-0" data-bs-toggle="collapse" data-bs-target="#categorySetting" aria-expanded="true" aria-controls="categorySetting" style="background: #b3b1b1;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                            <div class="fs-5 ms-2">カテゴリ</div>
                        </div>
                        <div class="collapse show" id="categorySetting">
                            <div class="card-body rounded-bottom px-3 py-1" style="background: rgb(231, 227, 227);">
                                aa
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .clicked {
        background: rgb(20, 130, 219) !important;
        transition: 0.5s;
    }
    #clickItem:hover {
        background: #3d3d3d;
    }
    #preView:hover {
        color: white !important;
        background: rgb(20, 130, 219);
    }
</style>