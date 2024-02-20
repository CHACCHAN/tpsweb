<script setup>
    import { ref, onMounted, onUpdated, onUnmounted } from 'vue'
    import Cropper from 'cropperjs'
    import "cropperjs/dist/cropper.css"

    const cropper = ref(null)
    const cropperOptions = ref({
        aspectRatio: 16/9
    })
    const refImage = ref(null)
    const image = ref(null)
    const groupImage = ref(null)
    const defaultImage = location.protocol + '//' + location.host + '/tps-site/images/components/defaultMediaImage.jpg'
    const inputGroupName = ref(null)
    const inputName = ref(null)
    const selectGroupOption = ref()
    const userDatas = ref([])
    const HistoryLists = ref([])
    const viewHistoryLists = ref([])
    const viewHistoryLength = ref(2)
    const mediaLists = ref([])
    const isClickedMediaGroupName = ref()
    const isClickedMediaGroupID = ref()
    const isClickedMediaGroupImage = ref()
    const isClickedMediaGroupCreated = ref()
    const isClickedMediaGroupUpdated = ref()
    const isHistoryChangeView = ref(null)
    const isChangeZoomView = ref(false)
    const zoomMediaImage = ref()
    const zoomMediaName = ref()
    const mediaChangeView = ref(false)
    const isClickedMediaGroupSecondID = ref()
    const isClickedMediaID = ref()
    const isClickedMediaName = ref()
    const isClickedMediaImage = ref()
    const isClickedDropdownMediaName = ref()
    const isClickedDropdownMediaGroupName = ref()
    const isNotificalText = ref()

    onMounted(() => {
        changeResponsive()
        window.addEventListener('resize', changeResponsive)
        getData()
        selectMediaGroup()
        connectSession()
    })

    onUpdated(() => {
        if(image.value && !cropper.value) {
            cropper.value = new Cropper(refImage.value, cropperOptions.value)
        } else if(image.value && cropper.value) {
            cropper.value.destroy()
            cropper.value = new Cropper(refImage.value, cropperOptions.value)
        }

        if(isNotificalText.value) {
            setTimeout(() => {
                isNotificalText.value = ''
            }, 2000)
        }
    })

    onUnmounted(() => {
        connectSession()
    })

    const getData = async() => {
        const getUserDataPromise = getUserData()
        const getMediaPromise = getMedia()
        const getMediaGroupPromise = getMediaGroup()

        await Promise.all([getUserDataPromise, getMediaPromise, getMediaGroupPromise])
    }

    const changeResponsive = () => {
        if(window.screen.width <= 768) {
            isHistoryChangeView.value = true
        } else {
            isHistoryChangeView.value = false
        }
    }

    const connectSession = () => {
        setInterval(async() => {
            if(!viewHistoryLists.value[0]) {
                const selectMediaGroupPromise = selectMediaGroup()

                await Promise.all([selectMediaGroupPromise])
            }
        }, 5000)
    }

    const uploadDiscord = (url, image) => {
        fetch('/tps-site/post/discord/media/webhook', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'url': url,
                'image': image,
            }),
        })
        .then((response) => response.json())
        .then(res => {})
        .catch(error => {
            console.log(error)
        })
    }

    // ユーザ
    const getUserData = () => {
        return new Promise((resolve, reject) => {
            fetch('/tps-site/auth/get/')
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

    // メディア
    const resetMedia = () => {
        if(cropper.value) {
            cropper.value.destroy()
            cropper.value = null
        }
        image.value = null
        inputName.value = null
        selectGroupOption.value = null
    }

    const getMedia = () => {
        return new Promise((resolve, reject) => {
            fetch('/tps-site/get/media')
            .then((response) => response.json())
            .then(res => {
                mediaLists.value = res.responseData
                resolve()
            })
            .catch(error => {
                console.log(error)
                reject(error)
            })
        })
    }
    
    const getUploadImage = (e) => {
        const render = new FileReader()
        render.readAsDataURL(e.target.files[0])
        render.onload = () => {
            image.value = render.result
        }
    }

    const createUpload = (e) => {
        if(inputName.value && image.value && selectGroupOption.value) {
            const inputData = inputName.value
            const canvas = cropper.value.getCroppedCanvas()
            const selectData = selectGroupOption.value
            const beforeButtonText = e.target.innerHTML
            e.target.disabled = true
            e.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   送信中'

            fetch('/tps-site/post/media/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'enctype': 'multipart/form-data',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    'name': inputData,
                    'image': canvas.toDataURL(),
                    'media_group_id': selectData
                }),
            })
            .then((response) => response.json())
            .then(res => {
                resetMedia()
                getData()
                e.target.disabled = false
                e.target.innerHTML = beforeButtonText
                isNotificalText.value = '画像が投稿されました'
                uploadDiscord(location.protocol + '//' + location.host + '/tps-site/image/' + inputData, location.protocol + '//' + location.host + '/tps-site/storage/media/Image/' + res.responseData)
            })
            .catch(error => {
                console.log(error)
            })
        }
    }

    const deleteMedia = () => {
        const MediaID = isClickedMediaID.value
        fetch('/tps-site/post/media/delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'id': MediaID
            }),
        })
        .then((response) => response.json())
        .then(res => {
            getData()
            isNotificalText.value = '投稿が削除されました'
        })
        .catch(error => {
            console.log(error)
        })
    }

    // メディアグループ
    const resetMediaGroup = () => {
        groupImage.value = null
        inputGroupName.value = null
    }

    const getMediaGroup = () => {
        return new Promise((resolve, reject) => {
            fetch('/tps-site/get/mediagroup')
            .then((response) => response.json())
            .then(res => {
                HistoryLists.value = res.responseData
                resolve()
            })
            .catch(error => {
                console.log(error)
                reject(error)
            })
        })
    }

    const selectMediaGroup = (e) => {
        if(e) {
            e.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   読み込み中'
        }
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                viewHistoryLists.value = new Array()
                for(let i = 0; i <= HistoryLists.value.length - 1; i++) {
                    if(i <= viewHistoryLength.value) {
                        viewHistoryLists.value.push(HistoryLists.value[i])
                    } else {
                        break
                    }
                }
                if(e) {
                    e.target.innerHTML = '続きを読み込む'
                }
                viewHistoryLength.value = viewHistoryLength.value + 3
                resolve()
            }, 500)
        })
    }

    const getUploadGroupImage = (e) => {
        const render = new FileReader()
        render.readAsDataURL(e.target.files[0])
        render.onload = () => {
            groupImage.value = render.result
        }
    }

    const createGroupUpload = (e) => {
        if(inputGroupName.value) {
            const inputData = inputGroupName.value
            const inputImage = groupImage.value
            const beforeButtonText = e.target.innerHTML
            e.target.disabled = true
            e.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   送信中'

            fetch('/tps-site/post/mediagroup/create', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'enctype': 'multipart/form-data',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    'name': inputData,
                    'image': inputImage
                }),
            })
            .then((response) => response.json())
            .then(res => {
                resetMediaGroup()
                e.target.disabled = false
                e.target.innerHTML = beforeButtonText
                getData()
                selectMediaGroup()
                isNotificalText.value = 'グループが追加されました'
            })
            .catch(error => {
                console.log(error)
            })
        }
    }

    const deleteMediaGroup = () => {
        const MediaGroupID = isClickedMediaGroupID.value
        fetch('/tps-site/post/mediagroup/delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'id': MediaGroupID
            }),
        })
        .then((response) => response.json())
        .then(res => {
            getData()
            selectMediaGroup()
            isNotificalText.value = 'グループと傘下メディアが削除されました'
        })
        .catch(error => {
            console.log(error)
        })
    }

    const updateGroupContent = () => {
        const groupID = isClickedMediaGroupID.value
        const inputData = isClickedMediaGroupName.value

        if(inputData) {
            fetch('/tps-site/post/mediagroup/update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    'id': groupID,
                    'name': inputData
                }),
            })
            .then((response) => response.json())
            .then(res => {
                getData()
                selectMediaGroup()
                isClickedMediaGroupUpdated.value = res.responseData.updated_at
                isClickedMediaGroupCreated.value = res.responseData.created_at
                isNotificalText.value = 'グループが更新されました'
            })
            .catch(error => {
                console.log(error)
            })
        }
    }

    const updateGroupImage = (e) => {
        const groupID = isClickedMediaGroupID.value
        const render = new FileReader()
        render.readAsDataURL(e.target.files[0])
        render.onload = () => {
            const inputImage = render.result
            fetch('/tps-site/post/mediagroup/update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'enctype': 'multipart/form-data',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    'id': groupID,
                    'image': inputImage
                }),
            })
            .then((response) => response.json())
            .then(res => {
                getData()
                selectMediaGroup()
                isClickedMediaGroupImage.value = res.responseData.image
                isClickedMediaGroupUpdated.value = res.responseData.updated_at
                isClickedMediaGroupCreated.value = res.responseData.created_at
                isNotificalText.value = 'グループアイコンが更新されました'
            })
            .catch(error => {
                console.log(error)
            })
        }
    }
</script>

<template>
    <div class="container">
        <div class="h4">メディア</div>
        <div class="row">
            <!-- メディア投稿フォーム -->
            <div class="col-12 col-md-6">
                <div id="clickMenuItem" class="card text-bg-dark mb-3">
                    <!-- Cropper -->
                    <div class="card-header border-secondary p-0">
                        <!-- デフォルト -->
                        <div class="card text-bg-dark border-0" v-if="!image">
                            <div class="card-body p-3 pb-0">
                                <label for="inputCropperImage" class="position-relative btn border-0 p-0">
                                    <img :src="defaultImage" class="rounded-3 border border-light opacity-50" width="100%" />
                                    <div id="hoverOpacity" class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center top-0 opacity-0">
                                        クリックで画像追加
                                    </div>
                                </label>
                                <input type="file" id="inputCropperImage" class="d-none" @change="getUploadImage" accept="image/png, image/jpeg" />
                            </div>
                        </div>
                        <!-- トリミング用 -->
                        <div class="m-3">
                            <img ref="refImage" :src="image" width="100%" v-if="image"/>
                        </div>
                    </div>
                    <!-- フォーム -->
                    <div class="card-body">
                        <div class="h5 border-bottom">メディア投稿フォーム</div>
                        <!-- 名前を入力 -->
                        <div class="mb-3">
                            <label for="inputTankName" class="form-label">名前を入力</label>
                            <input type="text" id="inputTankName" class="form-control border-0" v-model="inputName" placeholder="Smasher">
                        </div>
                        <!-- グループ選択 -->
                        <div class="mb-3">
                            <label for="selectGroupOption" class="form-label">グループを選択</label>
                            <select id="selectGroupOption" class="form-select" aria-label="Default select example" v-model="selectGroupOption">
                                <option 
                                    v-for="HistoryList in HistoryLists"
                                    :key="HistoryList.id" 
                                    :value="HistoryList.id"
                                    v-text="HistoryList.name">
                                </option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center justify-content-end">
                            <button type="button" class="btn btn-danger me-2 rounded-pill" @click="resetMedia()">リセット</button>
                            <button type="button" class="btn btn-success rounded-pill" @click="createUpload">送信</button>
                        </div>
                    </div>
                </div>
            </div>



            <!-- メディアグループ作成フォーム -->
            <div class="col-12 col-md-6">
                <div id="clickMenuItem" class="card mb-3">
                    <div class="card-body">
                        <!-- 作成フォーム -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="h5 text-primary fw-bold">グループ作成</div>
                                <input type="text" class="form-control border-0" v-model="inputGroupName" placeholder="グループ名を入力" />
                                <input type="file" id="inputGroupImage" class="d-none" @input="getUploadGroupImage" accept="image/png, image/jpeg" />
                                <div class="d-flex align-items-center justify-content-end mt-3">
                                    <label for="inputGroupImage" class="btn btn-dark rounded-pill me-1" v-if="!groupImage">グループアイコン</label>
                                    <label for="inputGroupImage" class="btn btn-dark rounded-pill me-1" v-if="groupImage">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                        </svg>
                                    </label>
                                    <button type="button" class="btn btn-success text-light rounded-pill" data-bs-toggle="modal" data-bs-target="#noneImageWarningPopup" v-if="!groupImage">作成</button>
                                    <button type="button" class="btn btn-success text-light rounded-pill" @click="createGroupUpload" v-if="groupImage">作成</button>
                                </div>
                            </div>
                        </div>
                        <!-- History -->
                        <ul class="list-group">
                            <!-- History Header -->
                            <li class="list-group-item list-group-item-dark d-flex align-items-center justify-content-start">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                    <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
                                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
                                    <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
                                </svg>
                                <div class="ms-2 fw-bold">History</div>
                            </li>
                            <!-- History Content -->
                            <li class="my-3" style="list-style: none;" v-if="!viewHistoryLists[0]">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </li>
                            <li id="clickItem" class="list-group-item" 
                                v-for="viewHistoryList in viewHistoryLists" 
                                :key="viewHistoryList.id" 
                                v-text="viewHistoryList.name"
                                style="cursor: pointer;"
                                data-bs-toggle="modal"
                                data-bs-target="#historyDetail"
                                @click="
                                    isClickedMediaGroupName = viewHistoryList.name,
                                    isClickedMediaGroupID = viewHistoryList.id,
                                    isClickedMediaGroupImage = viewHistoryList.image,
                                    isClickedMediaGroupCreated = viewHistoryList.created_at,
                                    isClickedMediaGroupUpdated = viewHistoryList.updated_at,
                                    isHistoryChangeView = false
                                ">
                            </li>
                            <!-- History Footer -->
                            <li class="mt-1" style="list-style: none;" v-if="viewHistoryLists[0]">
                                <button type="button" id="clickItem" class="btn border-0 rounded-pill text-secondary" @click="selectMediaGroup">
                                    続きを読み込む
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>



            <!-- メディア関連操作と一覧 -->
            <div class="col-12 mt-3">
                <h4>グループとメディアの一覧</h4>
            </div>
            <div class="col-12" v-if="!HistoryLists[0]">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="col-12" :class="{ 'col-md-4': !mediaChangeView }" v-for="HistoryList in HistoryLists" :key="HistoryList.id">
                <div id="clickMenuItem" class="card mb-3" v-if="!mediaChangeView">
                    <!-- ヘッダ切替パネル -->
                    <div class="card-body bg-light border-0 rounded pb-0" v-if="!mediaChangeView">
                        <div class="row mb-3">
                            <!-- グループネーム -->
                            <div class="col d-flex align-items-center justify-content-start">
                                <input 
                                    type="text" 
                                    class="form-control text-primary fs-5 fw-bold text-truncate p-0 border-0" 
                                    v-model="HistoryList.name" 
                                    @change="
                                        isClickedMediaGroupName = HistoryList.name, 
                                        isClickedMediaGroupID = HistoryList.id,
                                        updateGroupContent()
                                    ">
                            </div>
                            <!-- 切替ボタン -->
                            <div class="col d-flex align-items-center justify-content-end">
                                <button 
                                    type="button" 
                                    class="btn btn-dark text-light py-1 rounded-pill" 
                                    v-text="'メディアへ切替'" 
                                    v-if="!mediaChangeView" 
                                    @click="
                                        mediaChangeView = !mediaChangeView,
                                        isClickedMediaGroupSecondID = HistoryList.id,
                                        isClickedMediaName = HistoryList.name">
                                </button>
                            </div>
                        </div>
                        <!-- サムネ -->
                        <div v-if="!mediaChangeView">
                            <label for="inputGroupUpdateImage" class="position-relative btn border-0 p-0" @click="isClickedMediaGroupID = HistoryList.id">
                                <img :src="'/tps-site/storage/media/groupImage/' + HistoryList.image" class="rounded-3" width="100%" loading="lazy" v-if="HistoryList.image" />
                                <img :src="'/tps-site/images/components/defaultMediaImage.jpg'" class="rounded-3" width="100%" loading="lazy" v-if="!HistoryList.image" />
                                <div id="hoverOpacity" class="position-absolute text-light w-100 h-100 d-flex align-items-center justify-content-center top-0 opacity-0">
                                    クリックで変更
                                </div>
                            </label>
                            <input type="file" id="inputGroupUpdateImage" class="d-none" @change="updateGroupImage" accept="image/png, image/jpeg" />
                        </div>
                        <!-- 削除 -->
                        <div class="text-end">
                            <button 
                                type="button" 
                                id="clickItem" 
                                class="btn border-0 py-1 my-1 text-danger rounded-pill" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteMediaGroupModal" 
                                @click="
                                    isClickedDropdownMediaGroupName = HistoryList.name,
                                    isClickedMediaGroupID = HistoryList.id
                                ">削除
                            </button>
                        </div>
                    </div>
                </div>
                <!-- メディア関連 -->
                <div id="clickMenuItem" class="card mb-3" v-if="mediaChangeView && isClickedMediaGroupSecondID == HistoryList.id">
                    <div class="card-header bg-light pb-0 border-0 rounded">
                        <div class="row mb-3">
                            <div class="col d-flex align-items-center justify-content-start">
                                <input 
                                    type="text" 
                                    class="form-control text-primary fs-5 fw-bold text-truncate p-0 border-0" 
                                    v-model="HistoryList.name" 
                                    @change="
                                        isClickedMediaGroupName = HistoryList.name, 
                                        isClickedMediaGroupID = HistoryList.id,
                                        updateGroupContent()
                                    ">
                            </div>
                            <div class="col d-flex align-items-center justify-content-end">
                                <button 
                                    type="button" 
                                    class="btn btn-primary text-light py-1 rounded-pill" 
                                    v-text="'グループへ切替'" 
                                    v-if="mediaChangeView" 
                                    @click="mediaChangeView = !mediaChangeView">
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-2 pt-0">
                        <div class="row">
                            <div class="col-6 col-md-3" v-for="mediaList in mediaLists" :key="mediaList.id" v-show="isClickedMediaGroupSecondID == mediaList.media_group_id">
                                <div class="card text-bg-dark my-2">
                                    <img 
                                        :src="'/tps-site/storage/media/Image/' + mediaList.image" 
                                        @click="isClickedMediaImage = mediaList.image"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#mediaPopupModal" 
                                        style="cursor: pointer;" 
                                        class="rounded-top" 
                                        width="100%" 
                                        loading="lazy" />
                                    <div class="card-body pt-0 pb-1 pe-0">
                                        <div class="fw-bold" v-text="mediaList.name"></div>
                                        <!-- ドロップダウンメニュー -->
                                        <div class="dropdown-center text-end me-1">
                                            <button type="button" id="clickItem" class="btn border-0 text-light p-1 rounded-circle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                                    <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                                </svg>
                                            </button>
                                            <ul class="dropdown-menu text-bg-dark px-3">
                                                <li>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-dark rounded-pill text-center w-100" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteMediaModal" 
                                                        @click="
                                                            isClickedDropdownMediaName = mediaList.name,
                                                            isClickedMediaID = mediaList.id
                                                        ">削除
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- グループ画像未挿入注意モーダル -->
            <div class="modal" id="noneImageWarningPopup" tabindex="-1" aria-labelledby="noneImageWarningPopupLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content text-bg-dark border">
                        <div class="modal-header border-0">
                            <h5 class="modal-title text-truncate fw-bold" id="noneImageWarningPopupLabel" v-text="'グループアイコンが未設定です'"></h5>
                        </div>
                        <div class="modal-body pt-0">
                            <div class="d-flex align-items-center justify-content-start">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                                </svg>
                                <div class="fs-5 ms-2">このまま続行しますか?</div>
                            </div>
                            <div class="text-secondary mt-1">未設定の場合はテンプレート画像が使用されます</div>
                            <div class="text-end mt-5">
                                <button type="button" class="btn btn-dark rounded-pill me-1" data-bs-dismiss="modal">戻る</button>
                                <button type="button" class="btn btn-success rounded-pill" @click="createGroupUpload">作成</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- History詳細、メディア一覧モーダル -->
            <div class="modal" id="historyDetail" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content text-bg-dark">
                        <div class="modal-body">
                            <div class="row">
                                <!-- 切り替えメニュー -->
                                <div class="col-12 mb-3">
                                    <div class="d-flex align-items-center justify-content-start">
                                        <button type="button" class="btn rounded-pill py-1 me-2" :class="{ 'btn-primary': !isHistoryChangeView, 'btn-secondary': isHistoryChangeView }" @click="isHistoryChangeView = !isHistoryChangeView">グループ詳細</button>
                                        <button type="button" class="btn rounded-pill py-1" :class="{ 'btn-primary': isHistoryChangeView, 'btn-secondary': !isHistoryChangeView }" @click="isHistoryChangeView = !isHistoryChangeView">メディア一覧</button>
                                    </div>
                                </div>
                                <hr>
                                <!-- メディアグループ詳細 -->
                                <div class="col-12" v-if="!isHistoryChangeView">
                                    <div class="row">
                                        <div class="col-12 col-md-4 mb-3">
                                            <label for="inputGroupUpdateImage" class="position-relative btn border-0 p-0">
                                                <img :src="'/tps-site/storage/media/groupImage/' + isClickedMediaGroupImage" class="rounded-3" v-if="isClickedMediaGroupImage" width="100%" alt="" />
                                                <img :src="'/tps-site/images/components/defaultMediaImage.jpg'" class="rounded-3" v-if="!isClickedMediaGroupImage" width="100%" alt="" />
                                                <div id="hoverOpacity" class="position-absolute text-light w-100 h-100 d-flex align-items-center justify-content-center top-0 opacity-0">
                                                    クリックで変更
                                                </div>
                                            </label>
                                            <input type="file" id="inputGroupUpdateImage" class="d-none" @change="updateGroupImage" accept="image/png, image/jpeg" />
                                        </div>
                                        <div class="col-12 col-md-8">
                                            <div class="mb-3">
                                                <label for="modalGroupName" class="form-label">グループ名 :</label>
                                                <input type="text" id="modalGroupName" class="form-control border-0" v-model="isClickedMediaGroupName" @change="updateGroupContent()">
                                                <div class="form-text text-secondary">書き換えで更新できます</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="modalGroupID" class="form-label">グループID :</label>
                                                <input type="text" id="modalGroupID" class="form-control border-0" v-model="isClickedMediaGroupID" disabled>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="modalGroupCreate" class="form-label">作成 :</label>
                                                    <input type="text" id="modalGroupCreate" class="form-control" v-model="isClickedMediaGroupCreated" disabled>
                                                </div>
                                                <div class="col">
                                                    <label for="modalGroupUpdated" class="form-label">更新 :</label>
                                                    <input type="text" id="modalGroupUpdated" class="form-control" v-model="isClickedMediaGroupUpdated" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- メディア一覧 -->
                                <div class="col-12" v-if="isHistoryChangeView">
                                    <div class="card text-bg-dark border" v-if="!isChangeZoomView">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 col-md-3 mb-3" v-for="mediaList in mediaLists" :key="mediaList.id" v-show="isClickedMediaGroupID == mediaList.media_group_id">
                                                    <div 
                                                        id="clickMenuItem" 
                                                        class="card text-bg-dark border-0" 
                                                        v-if="isClickedMediaGroupID == mediaList.media_group_id" 
                                                        @click="
                                                            isChangeZoomView = !isChangeZoomView,
                                                            zoomMediaImage = '/tps-site/storage/media/Image/' + mediaList.image,
                                                            zoomMediaName = mediaList.name
                                                        "
                                                        style="cursor: pointer;">
                                                        <div class="card-header border-0 p-1">
                                                            <img :src="'/tps-site/storage/media/Image/' + mediaList.image" class="rounded-3" width="100%" loading="lazy" />
                                                        </div>
                                                        <div class="card-body p-1">
                                                            <div class="fw-bold text-center" v-text="mediaList.name"></div>
                                                            <div v-for="userData in userDatas" :key="userData.id">
                                                                <div 
                                                                    class="text-secondary text-center" 
                                                                    style="font-size: 12px;" 
                                                                    v-if="userData.id == mediaList.user_id"
                                                                    v-text="userData.name + 'が' + mediaList.created_at + 'に投稿しました'">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card text-bg-dark border" v-if="isChangeZoomView">
                                        <div class="card-header p-0 py-1">
                                            <div class="row">
                                                <div class="col d-flex align-items-center justify-content-start">
                                                    <div class="fw-bold text-truncate fs-5 ms-1" v-text="zoomMediaName"></div>
                                                </div>
                                                <div class="col d-flex align-items-center justify-content-end">
                                                    <button 
                                                        type="button" 
                                                        id="clickItem"
                                                        class="btn border-0 rounded-pill text-light py-1 me-1" 
                                                        @click="isChangeZoomView = !isChangeZoomView">閉じる</button>
                                                </div>
                                            </div>
                                        </div>
                                        <img :src="zoomMediaImage" class="rounded-bottom" width="100%" alt="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- メディア拡大モーダル -->
            <div class="modal" id="mediaPopupModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content text-bg-dark border">
                        <img :src="'/tps-site/storage/media/Image/' + isClickedMediaImage" class="rounded-2" witdh="100%" alt="">
                    </div>
                </div>
            </div>


            <!-- メディア削除モーダル -->
            <div class="modal" id="deleteMediaModal" tabindex="-1" aria-labelledby="deleteMediaModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content text-bg-dark border">
                        <div class="modal-header border-0">
                            <h5 class="modal-title text-truncate fw-bold" id="deleteMediaModalLabel" v-text="isClickedDropdownMediaName + 'を削除します'"></h5>
                        </div>
                        <div class="modal-body pt-0">
                            <div class="d-flex align-items-center justify-content-start">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                </svg>
                                <div class="fs-5 ms-2">本当に削除しますか?</div>
                            </div>
                            <div class="text-secondary mt-1">メディアは削除されます</div>
                            <div class="text-end mt-5">
                                <button type="button" class="btn btn-danger rounded-pill" data-bs-dismiss="modal" @click="deleteMedia()">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- メディアグループ削除モーダル -->
            <div class="modal" id="deleteMediaGroupModal" tabindex="-1" aria-labelledby="deleteMediaGroupModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content text-bg-dark border">
                        <div class="modal-header border-0">
                            <h5 class="modal-title text-truncate fw-bold" id="deleteMediaGroupModalLabel" v-text="isClickedDropdownMediaGroupName + 'を削除します'"></h5>
                        </div>
                        <div class="modal-body pt-0">
                            <div class="d-flex align-items-center justify-content-start">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                </svg>
                                <div class="fs-5 ms-2">本当に削除しますか?</div>
                            </div>
                            <div class="text-secondary mt-1">グループ内のメディアはすべて削除されます</div>
                            <div class="text-end mt-5">
                                <button type="button" class="btn btn-danger rounded-pill" data-bs-dismiss="modal" @click="deleteMediaGroup()">Delete</button>
                            </div>
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
    #clickMenuItem {
        transition: 0.5s;
    }
    #clickMenuItem:hover {
        transform: scale(1.03);
        box-shadow: 0 0 10px rgb(0, 0, 0);
        transition: 0.5s;
    }
    #hoverOpacity:hover {
        text-shadow: 0 0 5px black;
        opacity: 1 !important;
    }
</style>