<script setup>
    import { ref, onMounted, onUpdated, onUnmounted } from 'vue'
    import LoadingComponent from '../../components/LoadingComponent.vue'
    import Cookie from 'js-cookie'
    import { QuillEditor } from '@vueup/vue-quill'
    import '@vueup/vue-quill/dist/vue-quill.snow.css'
    import '@vueup/vue-quill/dist/vue-quill.bubble.css'

    const isLoading = ref(true)
    const isPostDatas = ref([])
    const isPostCategorys = ref([])
    const isListView = ref([true])
    const isTheme = ref('snow')
    const isEditorMsg = ref(true)
    const Permalink = location.protocol + '//' + location.hostname + '/tps-site/'
    const editorContent = ref()
    const editorImage = ref([])
    const saveAnimation = ref(false)
    const isCategory = ref(true)
    const isProject = ref(false)
    const isCategoryText = ref()
    const isClickedCategoryID = ref()
    const isClickedProjectID = ref()
    const isClickedCategoryTitle = ref()
    const isClickedProjectTitle = ref()
    const isClickedProjectPublic = ref(false)
    const isClickedProjectDate = ref()
    const isUpdateCategoryText = ref()
    const isCreateCategoryText = ref()
    const isUpdateProjectText = ref()
    const isCreateProjectText = ref()
    
    onMounted(() => {
        getPosts()
        changeResponsive()
        window.addEventListener('resize', changeResponsive)
    })

    onUpdated(() => {
        if(isListView.value[1] && editorContent.value) {
            window.onbeforeunload = () => {
                return '保存されていないデータは破棄されます。'
            }
        }
    })

    onUnmounted(() => {
        window.onbeforeunload = null
    })

    const uploadDiscord = (url) => {
        fetch('/tps-site/post/discord/post/webhook', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'url': url,
            }),
        })
        .then((response) => response.json())
        .then(res => {})
        .catch(error => {
            console.log(error)
        })
    }

    // プロジェクトAPI
    const moveProject = (toMoveProject) => {
        let target = document.getElementsByName(toMoveProject)
        isLoading.value = true
        target.forEach((data) => {
            if(data.checked) {
                fetch('/tps-site/post/postdata/move', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        'id': isClickedProjectID.value,
                        'category_id': data.value,
                    }),
                })
                .then((response) => response.json())
                .then(res => {
                    getPosts()
                    isLoading.value = false
                })
                .catch(error => {
                    console.log(error)
                })
            }
        })
    }

    const copyProject = (toCopyProject) => {
        let target = document.getElementsByName(toCopyProject)
        isLoading.value = true
        target.forEach((data) => {
            if(data.checked) {
                fetch('/tps-site/post/postdata/copy', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        'id': isClickedProjectID.value,
                        'category_id': data.value,
                    }),
                })
                .then((response) => response.json())
                .then(res => {
                    getPosts()
                    isLoading.value = false
                })
                .catch(error => {
                    console.log(error)
                })
            }
        })
    }

    const deleteProject = (toProjectID) => {
        isLoading.value = true
        fetch('/tps-site/post/postdata/delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'id': toProjectID,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            getPosts()
            isLoading.value = false
        })
        .catch(error => {
            console.log(error)
        })
    }

    const createProject = () => {
        let createProjectText = isCreateProjectText.value
        isCreateProjectText.value = ''
        fetch('/tps-site/post/postdata/create', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'title': createProjectText,
                'category_id': isClickedCategoryID.value
            }),
        })
        .then((response) => response.json())
        .then(res => {
            getPosts()
        })
        .catch(error => {
            console.log(error)
        })
    }

    const updateProjectText = (id) => {
        let updateProjectText = isUpdateProjectText.value
        isUpdateProjectText.value = ''
        fetch('/tps-site/post/postdata/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'id': id,
                'title': updateProjectText,
                'category_id': isClickedCategoryID.value
            }),
        })
        .then((response) => response.json())
        .then(res => {
            getPosts()
            isUpdateProjectText.value = updateProjectText
        })
        .catch(error => {
            console.log(error)
        })
    }

    // カテゴリAPI
    const deleteCategory = (toCategoryID) => {
        isLoading.value = true
        fetch('/tps-site/post/postcategory/delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'id': toCategoryID,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            getPosts()
            isLoading.value = false
        })
        .catch(error => {
            console.log(error)
        })
    }

    const createCategory = () => {
        let createCategoryText = isCreateCategoryText.value
        isCreateCategoryText.value = ''
        fetch('/tps-site/post/postcategory/create', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'category': createCategoryText
            }),
        })
        .then((response) => response.json())
        .then(res => {
            getPosts()
        })
        .catch(error => {
            console.log(error)
        })
    }

    const updateCategoryText = (id) => {
        let updateCategoryText = isUpdateCategoryText.value
        isUpdateCategoryText.value = ''
        fetch('/tps-site/post/postcategory/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'id': id,
                'category': updateCategoryText
            }),
        })
        .then((response) => response.json())
        .then(res => {
            getPosts()
            isUpdateCategoryText.value = updateCategoryText
        })
        .catch(error => {
            console.log(error)
        })
    }

    // エディタ
    const updateEditorContent = async (newContent) => {
        saveAnimation.value = true
        const imgList = Array.from(document.getElementById('QuillEditor').querySelectorAll('img'))
        // 画像データの増減を検証
        if(editorImage.value.length > imgList.length) {
            // 画像データの削除
            await fetch('/tps-site/post/postdata/image/remove', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    'id': editorImage.value[editorImage.value.length - 1].id,
                }),
            })
            .then((response) => response.json())
            .then(res => {
                saveAnimation.value = false
            })
            .catch(error => {
                console.log(error)
            })
        } else if(editorImage.value.length < imgList.length) {
            // 画像データの保存
            fetch('/tps-site/post/postdata/image', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'enctype': 'multipart/form-data',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    'post_id': isClickedProjectID.value,
                    'image': imgList[imgList.length - 1].src,
                }),
            })
            .then((response) => response.json())
            .then(res => {
                imgList[imgList.length - 1].setAttribute('id', res.id)
                imgList[imgList.length - 1].src = res.image
                saveAnimation.value = false
            })
            .catch(error => {
                console.log(error)
            })
        }

        // データの適用 
        editorImage.value = imgList
        editorContent.value = newContent
        await updateEditorContentText()
    }

    // タイトルとコンテンツの保存
    const updateEditorContentText = async() => {
        saveAnimation.value = true
        await fetch('/tps-site/post/postdata/content', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'id': isClickedProjectID.value,
                'title': isClickedProjectTitle.value,
                'content': editorContent.value,
            }),
        })
        .then((response) => response.json())
        .then(res => {
            saveAnimation.value = false
        })
        .catch(error => {
            console.log(error)
        })
    }

    const updatePublic = () => {
        isClickedProjectPublic.value = !isClickedProjectPublic.value
        fetch('/tps-site/post/postdata/public', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'id': isClickedProjectID.value,
                'public': isClickedProjectPublic.value
            }),
        })
        .then((response) => response.json())
        .then(res => {
            if(isClickedProjectPublic.value) {
                uploadDiscord(location.protocol + '//' + location.host + '/tps-site/post/' + isClickedCategoryTitle.value + '/' + isClickedProjectTitle.value)
            }
        })
        .catch(error => {
            console.log(error)
        })
    }

    // 取得
    const getPosts = () => {
        getPostData()
        getPostCategory()
    }

    const getPostData = () => {
        fetch('/tps-site/get/postdata')
        .then((response) => response.json())
        .then(res => {
            isPostDatas.value = res.responseData
            isLoading.value = false
        })
        .catch(error => {
            console.log(error)
        })
    }

    const getPostCategory = () => {
        fetch('/tps-site/get/postcategory')
        .then((response) => response.json())
        .then(res => {
            isPostCategorys.value = res.responseData
            isLoading.value = false
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
                getPosts()
                break
            case 1: // エディタ
                if(editorContent.value) {
                    isListView.value[e] = true
                    getPosts()
                } else {
                    isListView.value[0] = true
                }
                break
            default:
                isListView.value[0] = true
        }
    }

    const changeResponsive = () => {
        if(window.screen.width <= 768) {
            isTheme.value = 'bubble'
            isEditorMsg.value = false
        } else {
            isTheme.value = 'snow'
            isEditorMsg.value = true
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
            <!-- エディタ -->
            <div class="card text-bg-secondary border-0 rounded-pill">
                <div id="clickItem" class="card-body btn rounded-pill border-0 p-0 px-2" :class="{ 'clicked':isListView[1] }" @click="setToolMenu(1)">
                    エディタ
                </div>
            </div>
            <div class="vr mx-2"></div>
        </div>
        
        <!-- リストView -->
        <div v-if="isListView[0]">
            <!-- パステキスト -->
            <div class="mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb fw-bold">
                        <li class="breadcrumb-item"><button type="button" class="btn border-0 p-0 fw-bold" :class="{ 'text-primary': isCategory }" @click="isCategory = true, isProject = false, isCategoryText = ''">Category</button></li>
                        <li class="breadcrumb-item"><button type="button" class="btn border-0 p-0 fw-bold" :class="{ 'text-primary': isProject }" @click="isCategory = false, isProject = true" v-text="isCategoryText"></button></li>
                    </ol>
                </nav>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <!-- !!!!!!!カテゴリ!!!!!!! -->
                    <div v-if="!isCategoryText">
                        <!-- 新規作成 -->
                        <div class="row btn border-0 d-flex align-items-center justify-content-center border-bottom p-0 mb-3" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                            <div class="col-12 d-flex align-items-center justify-content-start">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle-dotted" viewBox="0 0 16 16">
                                    <path d="M8 0c-.176 0-.35.006-.523.017l.064.998a7.117 7.117 0 0 1 .918 0l.064-.998A8.113 8.113 0 0 0 8 0zM6.44.152c-.346.069-.684.16-1.012.27l.321.948c.287-.098.582-.177.884-.237L6.44.153zm4.132.271a7.946 7.946 0 0 0-1.011-.27l-.194.98c.302.06.597.14.884.237l.321-.947zm1.873.925a8 8 0 0 0-.906-.524l-.443.896c.275.136.54.29.793.459l.556-.831zM4.46.824c-.314.155-.616.33-.905.524l.556.83a7.07 7.07 0 0 1 .793-.458L4.46.824zM2.725 1.985c-.262.23-.51.478-.74.74l.752.66c.202-.23.418-.446.648-.648l-.66-.752zm11.29.74a8.058 8.058 0 0 0-.74-.74l-.66.752c.23.202.447.418.648.648l.752-.66zm1.161 1.735a7.98 7.98 0 0 0-.524-.905l-.83.556c.169.253.322.518.458.793l.896-.443zM1.348 3.555c-.194.289-.37.591-.524.906l.896.443c.136-.275.29-.54.459-.793l-.831-.556zM.423 5.428a7.945 7.945 0 0 0-.27 1.011l.98.194c.06-.302.14-.597.237-.884l-.947-.321zM15.848 6.44a7.943 7.943 0 0 0-.27-1.012l-.948.321c.098.287.177.582.237.884l.98-.194zM.017 7.477a8.113 8.113 0 0 0 0 1.046l.998-.064a7.117 7.117 0 0 1 0-.918l-.998-.064zM16 8a8.1 8.1 0 0 0-.017-.523l-.998.064a7.11 7.11 0 0 1 0 .918l.998.064A8.1 8.1 0 0 0 16 8zM.152 9.56c.069.346.16.684.27 1.012l.948-.321a6.944 6.944 0 0 1-.237-.884l-.98.194zm15.425 1.012c.112-.328.202-.666.27-1.011l-.98-.194c-.06.302-.14.597-.237.884l.947.321zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a6.999 6.999 0 0 1-.458-.793l-.896.443zm13.828.905c.194-.289.37-.591.524-.906l-.896-.443c-.136.275-.29.54-.459.793l.831.556zm-12.667.83c.23.262.478.51.74.74l.66-.752a7.047 7.047 0 0 1-.648-.648l-.752.66zm11.29.74c.262-.23.51-.478.74-.74l-.752-.66c-.201.23-.418.447-.648.648l.66.752zm-1.735 1.161c.314-.155.616-.33.905-.524l-.556-.83a7.07 7.07 0 0 1-.793.458l.443.896zm-7.985-.524c.289.194.591.37.906.524l.443-.896a6.998 6.998 0 0 1-.793-.459l-.556.831zm1.873.925c.328.112.666.202 1.011.27l.194-.98a6.953 6.953 0 0 1-.884-.237l-.321.947zm4.132.271a7.944 7.944 0 0 0 1.012-.27l-.321-.948a6.954 6.954 0 0 1-.884.237l.194.98zm-2.083.135a8.1 8.1 0 0 0 1.046 0l-.064-.998a7.11 7.11 0 0 1-.918 0l-.064.998zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                </svg>
                                <div class="fs-6 ms-2" v-text="'カテゴリを作成'"></div>
                            </div>
                        </div>
                        <!-- カテゴリ作成モーダル -->
                        <div class="modal" id="createCategoryModal" tabindex="-1" aria-labelledby="#createCategoryModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content text-bg-dark border">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title text-truncate fw-bold" id="createCategoryModalLabel" v-text="'カテゴリを作る'"></h5>
                                    </div>
                                    <div class="modal-body pt-0">
                                        <input type="text" class="form-control rounded-pill" v-model="isCreateCategoryText" placeholder="カテゴリ名を命名してください。">
                                        <div class="text-end mt-5">
                                            <button type="button" class="btn btn-primary rounded-pill" @click="createCategory()" :disabled="!isCreateCategoryText">Create</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 一覧 -->
                        <div v-for="isPostCategory in isPostCategorys" :key="isPostCategory.id" class="border-bottom">
                            <div class="row btn border-0 d-flex align-items-center justify-content-center p-0 py-1">
                                <div class="col-11 d-flex align-items-center justify-content-start" @click="isClickedCategoryID = isPostCategory.id, isCategoryText = isPostCategory.category, isClickedCategoryTitle = isPostCategory.category, isCategory = false, isProject = true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-folder-fill" viewBox="0 0 16 16">
                                        <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                                    </svg>
                                    <div class="fs-6 fw-bold ms-2" v-text="isPostCategory.category"></div>
                                    <div class="fs-6 text-secondary ms-2" v-text="isPostCategory.updated_at"></div>
                                </div>
                                <div class="col-1 px-1">
                                    <div class="dropdown-center text-end">
                                        <a class="btn border-0 p-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                            </svg>
                                        </a>
                                        <ul class="dropdown-menu text-bg-dark px-3">
                                            <li><button type="button" class="btn btn-dark rounded-pill text-center w-100" data-bs-toggle="modal" :data-bs-target="'#editModalKey' + isPostCategory.id" @click="isUpdateCategoryText = isPostCategory.category">編集</button></li>
                                            <li><button type="button" class="btn btn-dark rounded-pill text-center w-100" data-bs-toggle="modal" :data-bs-target="'#delModalKey' + isPostCategory.id">削除</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- カテゴリ編集モーダル -->
                            <div class="modal" :id="'editModalKey' + isPostCategory.id" tabindex="-1" :aria-labelledby="'editModalLabelKey' + isPostCategory.id" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-bg-dark border">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title text-truncate fw-bold" :id="'editModalLabelKey' + isPostCategory.id" v-text="isPostCategory.category + 'を編集する'"></h5>
                                        </div>
                                        <div class="modal-body pt-0">
                                            <input type="text" class="form-control rounded-pill" v-model="isUpdateCategoryText" placeholder="カテゴリ名を命名してください。">
                                            <div class="text-end mt-5">
                                                <button type="button" class="btn btn-primary rounded-pill" @click="updateCategoryText(isPostCategory.id)" :disabled="!isUpdateCategoryText">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- カテゴリ削除モーダル -->
                            <div class="modal" :id="'delModalKey' + isPostCategory.id" tabindex="-1" :aria-labelledby="'delModalLabelKey' + isPostCategory.id" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-bg-dark border">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title text-truncate fw-bold" :id="'delModalLabelKey' + isPostCategory.id" v-text="isPostCategory.category + 'を削除します'"></h5>
                                        </div>
                                        <div class="modal-body pt-0">
                                            <div class="d-flex align-items-center justify-content-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                                </svg>
                                                <div class="fs-5 ms-2">本当に削除しますか?</div>
                                            </div>
                                            <div class="text-secondary mt-1">カテゴリ内のプロジェクトはすべて削除されます</div>
                                            <div class="text-end mt-5">
                                                <button type="button" class="btn btn-danger rounded-pill" @click="deleteCategory(isPostCategory.id)" data-bs-dismiss="modal">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- !!!!!!!プロジェクト!!!!!!! -->
                    <div v-if="isCategoryText">
                        <!-- 新規作成 -->
                        <div class="row btn border-0 d-flex align-items-center justify-content-center border-bottom p-0 mb-3" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                            <div class="col-12 d-flex align-items-center justify-content-start">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle-dotted" viewBox="0 0 16 16">
                                    <path d="M8 0c-.176 0-.35.006-.523.017l.064.998a7.117 7.117 0 0 1 .918 0l.064-.998A8.113 8.113 0 0 0 8 0zM6.44.152c-.346.069-.684.16-1.012.27l.321.948c.287-.098.582-.177.884-.237L6.44.153zm4.132.271a7.946 7.946 0 0 0-1.011-.27l-.194.98c.302.06.597.14.884.237l.321-.947zm1.873.925a8 8 0 0 0-.906-.524l-.443.896c.275.136.54.29.793.459l.556-.831zM4.46.824c-.314.155-.616.33-.905.524l.556.83a7.07 7.07 0 0 1 .793-.458L4.46.824zM2.725 1.985c-.262.23-.51.478-.74.74l.752.66c.202-.23.418-.446.648-.648l-.66-.752zm11.29.74a8.058 8.058 0 0 0-.74-.74l-.66.752c.23.202.447.418.648.648l.752-.66zm1.161 1.735a7.98 7.98 0 0 0-.524-.905l-.83.556c.169.253.322.518.458.793l.896-.443zM1.348 3.555c-.194.289-.37.591-.524.906l.896.443c.136-.275.29-.54.459-.793l-.831-.556zM.423 5.428a7.945 7.945 0 0 0-.27 1.011l.98.194c.06-.302.14-.597.237-.884l-.947-.321zM15.848 6.44a7.943 7.943 0 0 0-.27-1.012l-.948.321c.098.287.177.582.237.884l.98-.194zM.017 7.477a8.113 8.113 0 0 0 0 1.046l.998-.064a7.117 7.117 0 0 1 0-.918l-.998-.064zM16 8a8.1 8.1 0 0 0-.017-.523l-.998.064a7.11 7.11 0 0 1 0 .918l.998.064A8.1 8.1 0 0 0 16 8zM.152 9.56c.069.346.16.684.27 1.012l.948-.321a6.944 6.944 0 0 1-.237-.884l-.98.194zm15.425 1.012c.112-.328.202-.666.27-1.011l-.98-.194c-.06.302-.14.597-.237.884l.947.321zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a6.999 6.999 0 0 1-.458-.793l-.896.443zm13.828.905c.194-.289.37-.591.524-.906l-.896-.443c-.136.275-.29.54-.459.793l.831.556zm-12.667.83c.23.262.478.51.74.74l.66-.752a7.047 7.047 0 0 1-.648-.648l-.752.66zm11.29.74c.262-.23.51-.478.74-.74l-.752-.66c-.201.23-.418.447-.648.648l.66.752zm-1.735 1.161c.314-.155.616-.33.905-.524l-.556-.83a7.07 7.07 0 0 1-.793.458l.443.896zm-7.985-.524c.289.194.591.37.906.524l.443-.896a6.998 6.998 0 0 1-.793-.459l-.556.831zm1.873.925c.328.112.666.202 1.011.27l.194-.98a6.953 6.953 0 0 1-.884-.237l-.321.947zm4.132.271a7.944 7.944 0 0 0 1.012-.27l-.321-.948a6.954 6.954 0 0 1-.884.237l.194.98zm-2.083.135a8.1 8.1 0 0 0 1.046 0l-.064-.998a7.11 7.11 0 0 1-.918 0l-.064.998zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                </svg>
                                <div class="fs-6 ms-2" v-text="'プロジェクトを作成'"></div>
                            </div>
                        </div>
                        <!-- プロジェクト作成モーダル -->
                        <div class="modal" id="createProjectModal" tabindex="-1" aria-labelledby="#createProjectModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content text-bg-dark border">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title text-truncate fw-bold" id="createProjectModalLabel" v-text="'プロジェクトを作る'"></h5>
                                    </div>
                                    <div class="modal-body pt-0">
                                        <input type="text" class="form-control rounded-pill" v-model="isCreateProjectText" placeholder="プロジェクト名を命名してください。">
                                        <div class="text-end mt-5">
                                            <button type="button" class="btn btn-primary rounded-pill" @click="createProject()" :disabled="!isCreateProjectText">Create</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 一覧 -->
                        <div v-for="isPostData in isPostDatas" :key="isPostData.id">
                            <div v-if="isPostData.category_id == isClickedCategoryID" class="border-bottom">
                                <div class="row btn border-0 d-flex align-items-center justify-content-center p-0 py-1">
                                    <div class="col-11 d-flex align-items-center justify-content-start" @click="isClickedProjectID = isPostData.id, isListView = new Array(), isListView[1] = true, isClickedProjectTitle = isPostData.title, editorContent = isPostData.content, isClickedProjectPublic = isPostData.public, isClickedProjectDate = isPostData.updated_at">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-filetype-html" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M14 4.5V11h-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5Zm-9.736 7.35v3.999h-.791v-1.714H1.79v1.714H1V11.85h.791v1.626h1.682V11.85h.79Zm2.251.662v3.337h-.794v-3.337H4.588v-.662h3.064v.662H6.515Zm2.176 3.337v-2.66h.038l.952 2.159h.516l.946-2.16h.038v2.661h.715V11.85h-.8l-1.14 2.596H9.93L8.79 11.85h-.805v3.999h.706Zm4.71-.674h1.696v.674H12.61V11.85h.79v3.325Z"/>
                                        </svg>
                                        <div class="fs-6 fw-bold ms-2" v-text="isPostData.title"></div>
                                        <div class="fs-6 text-secondary ms-2" v-text="isPostData.updated_at"></div>
                                    </div>
                                    <div class="col-1 px-1">
                                        <div class="dropdown text-end">
                                            <a class="btn border-0 p-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                </svg>
                                            </a>
                                            <ul class="dropdown-menu text-bg-dark px-3">
                                                <li><button type="button" class="btn btn-dark rounded-pill text-center w-100" data-bs-toggle="modal" :data-bs-target="'#moveModalSecondKey' + isPostData.id" @click="isClickedProjectID = isPostData.id">移動</button></li>
                                                <li><button type="button" class="btn btn-dark rounded-pill text-center w-100" data-bs-toggle="modal" :data-bs-target="'#copyModalSecondKey' + isPostData.id" @click="isClickedProjectID = isPostData.id">複製</button></li>
                                                <li><button type="button" class="btn btn-dark rounded-pill text-center w-100" data-bs-toggle="modal" :data-bs-target="'#editModalSecondKey' + isPostData.id" @click="isUpdateProjectText = isPostData.title">編集</button></li>
                                                <li><button type="button" class="btn btn-dark rounded-pill text-center w-100" data-bs-toggle="modal" :data-bs-target="'#delModalSecondKey' + isPostData.id">削除</button></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- プロジェクト移動モーダル -->
                                <div class="modal" :id="'moveModalSecondKey' + isPostData.id" tabindex="-1" :aria-labelledby="'moveModalSecondLabelKey' + isPostData.id" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-bg-dark border">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title text-truncate fw-bold" :id="'moveModalSecondLabelKey' + isPostData.id" v-text="isPostData.title + 'を移動する'"></h5>
                                            </div>
                                            <div class="modal-body pt-0">
                                                <div class="row mb-5">
                                                    <!-- 元のカテゴリ -->
                                                    <div class="col-5 bg-secondary rounded-3">
                                                        <div v-for="isPostCategory in isPostCategorys" :key="isPostCategory.id">
                                                            <div v-if="isPostCategory.id == isPostData.category_id">
                                                                <div class="d-flex align-items-center justify-content-start">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-folder-fill" viewBox="0 0 16 16">
                                                                        <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                                                                    </svg>
                                                                    <div class="fs-6 fw-bold ms-2 text-truncate" v-text="isPostCategory.category"></div>
                                                                </div>
                                                                <div class="d-flex align-items-center justify-content-start ms-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-return-right" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 0 1 2v4.8a2.5 2.5 0 0 0 2.5 2.5h9.793l-3.347 3.346a.5.5 0 0 0 .708.708l4.2-4.2a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 8.3H3.5A1.5 1.5 0 0 1 2 6.8V2a.5.5 0 0 0-.5-.5z"/>
                                                                    </svg>
                                                                    <div class="fs-6 text-truncate text-decoration-underline text-info ms-1" v-text="isPostData.title"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- 矢印 -->
                                                    <div class="col-2 d-flex align-items-center justify-content-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                                        </svg>
                                                    </div>
                                                    <!-- 移動先カテゴリ -->
                                                    <div class="col-5 bg-secondary rounded-3">
                                                        <div v-for="isPostCategory in isPostCategorys" :key="isPostCategory.id">
                                                            <div class="form-check">
                                                                <input :id="'moveProjectRadioKey' + isPostCategory.id" class="form-check-input" type="radio" name="toMoveProject" :value="isPostCategory.id">
                                                                <label class="form-check-label d-flex align-items-center justify-content-start" :for="'moveProjectRadioKey' + isPostCategory.id">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-folder-fill" viewBox="0 0 16 16">
                                                                        <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                                                                    </svg>
                                                                    <div class="fs-6 fw-bold ms-2 text-truncate" v-text="isPostCategory.category"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <button type="button" class="btn btn-primary rounded-pill" @click="moveProject('toMoveProject')" data-bs-dismiss="modal">Move</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- プロジェクト複製モーダル -->
                                <div class="modal" :id="'copyModalSecondKey' + isPostData.id" tabindex="-1" :aria-labelledby="'copyModalSecondLabelKey' + isPostData.id" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-bg-dark border">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title text-truncate fw-bold" :id="'copyModalSecondLabelKey' + isPostData.id" v-text="isPostData.title + 'を複製する'"></h5>
                                            </div>
                                            <div class="modal-body pt-0">
                                                <div class="row mb-5">
                                                    <!-- 元のカテゴリ -->
                                                    <div class="col-5 bg-secondary rounded-3">
                                                        <div v-for="isPostCategory in isPostCategorys" :key="isPostCategory.id">
                                                            <div v-if="isPostCategory.id == isPostData.category_id">
                                                                <div class="d-flex align-items-center justify-content-start">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-folder-fill" viewBox="0 0 16 16">
                                                                        <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                                                                    </svg>
                                                                    <div class="fs-6 fw-bold ms-2 text-truncate" v-text="isPostCategory.category"></div>
                                                                </div>
                                                                <div class="d-flex align-items-center justify-content-start ms-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-return-right" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 0 1 2v4.8a2.5 2.5 0 0 0 2.5 2.5h9.793l-3.347 3.346a.5.5 0 0 0 .708.708l4.2-4.2a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 8.3H3.5A1.5 1.5 0 0 1 2 6.8V2a.5.5 0 0 0-.5-.5z"/>
                                                                    </svg>
                                                                    <div class="fs-6 text-truncate text-decoration-underline text-info ms-1" v-text="isPostData.title"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- 矢印 -->
                                                    <div class="col-2 d-flex align-items-center justify-content-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                                        </svg>
                                                    </div>
                                                    <!-- 移動先カテゴリ -->
                                                    <div class="col-5 bg-secondary rounded-3">
                                                        <div v-for="isPostCategory in isPostCategorys" :key="isPostCategory.id">
                                                            <div class="form-check">
                                                                <input :id="'copyProjectRadioKey' + isPostCategory.id" class="form-check-input" type="radio" name="toCopyProject" :value="isPostCategory.id">
                                                                <label class="form-check-label d-flex align-items-center justify-content-start" :for="'copyProjectRadioKey' + isPostCategory.id">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-folder-fill" viewBox="0 0 16 16">
                                                                        <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                                                                    </svg>
                                                                    <div class="fs-6 fw-bold ms-2 text-truncate" v-text="isPostCategory.category"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <button type="button" class="btn btn-primary rounded-pill" @click="copyProject('toCopyProject')" data-bs-dismiss="modal">Copy</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- プロジェクト編集モーダル -->
                                <div class="modal" :id="'editModalSecondKey' + isPostData.id" tabindex="-1" :aria-labelledby="'editModalSecondLabelKey' + isPostData.id" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-bg-dark border">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title text-truncate fw-bold" :id="'editModalSecondLabelKey' + isPostData.id" v-text="isPostData.title + 'を編集する'"></h5>
                                                <div class="dropdown-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-three-dots text-light btn border-0 p-0" viewBox="0 0 16 16" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                                    </svg>
                                                    <ul class="dropdown-menu text-bg-dark px-3">
                                                        <li><button type="button" class="btn btn-dark rounded-pill text-center w-100" data-bs-toggle="modal" :data-bs-target="'#editModalSecondKey' + isPostData.id" @click="isUpdateProjectText = isPostData.title">移動</button></li>
                                                        <li><button type="button" class="btn btn-dark rounded-pill text-center w-100" data-bs-toggle="modal" :data-bs-target="'#delModalSecondKey' + isPostData.id">複製</button></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="modal-body pt-0">
                                                <input type="text" class="form-control rounded-pill" v-model="isUpdateProjectText" placeholder="プロジェクト名を命名してください。">
                                                <div class="d-flex align-items-center justify-content-end mt-5">
                                                    <button type="button" class="btn btn-dark rounded-pill me-2" @click="isListView = new Array(), isListView[1] = true, editorContent = isPostData.content" data-bs-dismiss="modal">Edit</button>
                                                    <button type="button" class="btn btn-primary rounded-pill" @click="updateProjectText(isPostData.id)" :disabled="!isUpdateProjectText">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- プロジェクト削除モーダル -->
                                <div class="modal" :id="'delModalSecondKey' + isPostData.id" tabindex="-1" :aria-labelledby="'delModalSecondLabelKey' + isPostData.id" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-bg-dark border">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title text-truncate fw-bold" :id="'delModalSecondLabelKey' + isPostData.id" v-text="isPostData.title + 'を削除します'"></h5>
                                            </div>
                                            <div class="modal-body pt-0">
                                                <div class="d-flex align-items-center justify-content-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                                    </svg>
                                                    <div class="fs-5 ms-2">本当に削除しますか?</div>
                                                </div>
                                                <div class="text-secondary mt-1">プロジェクトの中身はすべて削除されます</div>
                                                <div class="text-end mt-5">
                                                    <button type="button" class="btn btn-danger rounded-pill" @click="deleteProject(isPostData.id)" data-bs-dismiss="modal">Delete</button>
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
        </div>

        <!-- エディタView -->
        <div v-if="isListView[1]">
            <div class="row">
                <!-- メイン -->
                <div class="col-12 col-md-9">
                    <!-- 基本 -->
                    <div class="mb-3 text-truncate">
                        <input type="text" class="form-control rounded-pill border-dark" v-model="isClickedProjectTitle" @change="updateEditorContentText()" placeholder="タイトルを入力してください">
                        <div class="d-flex align-items-center justify-content-start text-secondary mt-1">
                            <div class="fw-bold">パーマリンク</div>
                            <div class="mx-2">:</div>
                            <a 
                                :href="Permalink + 'post/' + isClickedCategoryTitle + '/' + isClickedProjectTitle" target="_blank" v-text="Permalink + 'post/' + isClickedCategoryTitle + '/' + isClickedProjectTitle"
                                class="text-truncate"    
                            ></a>
                        </div>
                    </div>
                    <!-- テキストエディタ -->
                    <div id="QuillEditor" class="border mb-3">
                        <div class="text-center text-secondary" :class="{ 'd-none': isEditorMsg }">Mobile Editor</div>
                        <QuillEditor class="editor z-3" :theme="isTheme" toolbar="full" contentType="html" v-model:content="editorContent" @update:content="updateEditorContent" placeholder="入力してください" />
                    </div>
                    <!-- プレビュー -->
                    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-fullscreen">
                            <div class="modal-content text-bg-dark">
                                <div class="modal-header border-0 bg-dark py-1">
                                    <h1 id="previewModalLabel" class="modal-title fs-5 fw-bold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-tv-fill" viewBox="0 0 16 16">
                                            <path d="M2.5 13.5A.5.5 0 0 1 3 13h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zM2 2h12s2 0 2 2v6s0 2-2 2H2s-2 0-2-2V4s0-2 2-2z"/>
                                        </svg>   プレビュー
                                    </h1>
                                    <button type="button" class="btn btn-primary rounded-pill py-1" data-bs-dismiss="modal" aria-label="Close">
                                        閉じる
                                    </button>
                                </div>
                                <div class="modal-body p-0">
                                    <div class="container text-bg-light rounded-4 my-2">
                                        <p v-text="'投稿日時 : ' + isClickedProjectDate" class="text-secondary text-end"></p>
                                        <div class="ql-snow">
                                            <div class="ql-editor" v-html="editorContent"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <div class="collapse" :class="{ show: isEditorMsg }" id="publicSetting">
                            <div class="card-body rounded-bottom px-3 py-1" style="background: rgb(231, 227, 227);">
                                <!-- プレビュー -->
                                <button type="button" id="preView" class="btn border-primary text-primary rounded-pill py-1 w-100" data-bs-toggle="modal" data-bs-target="#previewModal">
                                    プレビュー
                                </button>
                                <!-- 公開設定 -->
                                <div class="d-flex align-items-center justify-content-start text-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" v-if="isClickedProjectPublic">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16" v-if="!isClickedProjectPublic">
                                        <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                                        <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
                                        <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
                                    </svg>
                                    <div class="fs-6 ms-1">公開設定:</div>
                                    <div class="fw-bold mx-1" v-if="isClickedProjectPublic">公開中</div>
                                    <div class="fw-bold mx-1" v-if="!isClickedProjectPublic">非公開</div>
                                    <!-- 公開設定ドロップ -->
                                    <div class="dropend">
                                            <div class="btn border-0 p-0 text-primary text-decoration-underline" data-bs-toggle="dropdown" aria-expanded="false">編集</div>
                                            <ul class="dropdown-menu text-bg-dark w-100">
                                                <li>
                                                    <div class="form-check form-switch d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" role="switch" id="publicSwitch" :checked="isClickedProjectPublic" @click="updatePublic()">
                                                        <label class="form-check-label ms-2" for="publicSwitch" v-if="isClickedProjectPublic">公開中</label>
                                                        <label class="form-check-label ms-2" for="publicSwitch" v-if="!isClickedProjectPublic">非公開</label>
                                                    </div>
                                                </li>
                                            </ul>
                                    </div>
                                </div>
                                <!-- カテゴリ情報 -->
                                <div class="d-flex align-items-center justify-content-start text-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-folder-fill" viewBox="0 0 16 16">
                                        <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                                    </svg>
                                    <div class="fs-6 ms-1">カテゴリ:</div>
                                    <div class="fw-bold mx-1" v-text="isClickedCategoryTitle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ステータス -->
                    <div class="card text-bg-secondary text-dark">
                        <div class="card-header d-flex align-items-center justify-content-start px-3 py-0" data-bs-toggle="collapse" data-bs-target="#categorySetting" aria-expanded="true" aria-controls="categorySetting" style="background: #b3b1b1;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                            <div class="fs-5 ms-2">ステータス</div>
                        </div>
                        <div class="collapse" :class="{ show: isEditorMsg }" id="categorySetting">
                            <div class="card-body rounded-bottom px-3 py-1" style="background: rgb(231, 227, 227);">
                                <!-- 下書き保存 -->
                                <div class="d-flex align-items-center justify-content-start text-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cloud-download" viewBox="0 0 16 16">
                                        <path d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                                        <path d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"/>
                                    </svg>
                                    <div class="fs-6 ms-1">下書き保存:</div>
                                    <div class="fw-bold mx-1">自動</div>
                                </div>
                                <!-- 保存中アニメーション -->
                                <div class="d-flex align-items-center justify-content-center text-secondary mt-3 mb-2 p-2 bg-light rounded-3" v-if="saveAnimation">
                                    <div class="spinner-border text-success" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="fw-bold fs-4 mx-1">保存中</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center text-secondary mt-3 mb-2 p-2 bg-light rounded-3" v-if="!saveAnimation">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                    <div class="fw-bold fs-4 mx-1">保存済み</div>
                                </div>
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
    #editView:hover {
        color: white !important;
        background: #28a745;
    }
    #delView:hover {
        color: white !important;
        background: #dc3545;
    }
</style>