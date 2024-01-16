<script setup>
    import { ref, onMounted, onUpdated } from 'vue'
    import LoadingComponent from '../../components/loadingcomponent.vue'

    const isLoading = ref(true)
    const isPostData = ref([])
    const isPostCategory = ref([])
    const isListView = ref([true])
    
    onMounted(() => {
        getPosts()
    })

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
        isListView.value = []

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
</script>

<template>
    <LoadingComponent v-if="isLoading" />
    <div class="container" v-if="!isLoading">
        <div class="h4">投稿</div>
        <!-- ツールメニュー -->
        <div class="d-flex mb-3">
            <!-- リスト -->
            <div class="card text-bg-primary border-0 rounded-pill me-1">
                <div id="clickItem" class="card-body btn rounded-pill border-0 p-0 px-2" :class="{ 'clicked':isListView[0] }" @click="setToolMenu(0)">
                    リスト
                </div>
            </div>
            <!-- 新規登録 -->
            <div class="card text-bg-primary border-0 rounded-pill">
                <div id="clickItem" class="card-body btn rounded-pill border-0 p-0 px-2" :class="{ 'clicked':isListView[1] }" @click="setToolMenu(1)">
                    新規追加
                </div>
            </div>
            <div class="vr mx-2"></div>
        </div>
        
        <!-- リスト -->
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

        <!-- 新規追加 -->
        <div v-if="isListView[1]">
            <div class="row">
                <!-- メイン -->
                <div class="col-12 col-md-9">
                    aa
                </div>
                <!-- サブ -->
                <div class="col-12 col-md-3">
                    
                    <div class="card text-bg-secondary text-dark">
                        <div class="card-header px-3 py-0" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="true" aria-controls="collapseExample" style="background: #b3b1b1;">
                            <div class="fs-5">公開</div>
                        </div>
                        <div class="collapse show" id="collapseExample">
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
        background: #3d3d3d;
    }
    #clickItem:hover {
        background: #3d3d3d;
    }
</style>