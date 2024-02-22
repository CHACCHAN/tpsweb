<script setup>
    import { ref, onMounted } from 'vue'
    import Cropper from 'cropperjs'
    import "cropperjs/dist/cropper.css"

    const emit = defineEmits([
        'getLoginData'
    ])
    const cropper = ref(null)
    const cropperOptions = ref({
        aspectRatio: 1/1
    })
    const image = ref(null)
    const cropperImage = ref(null)

    const installImage = (e) => {
        new Promise((resolve, reject) => {
            const render = new FileReader()
            render.readAsDataURL(e.target.files[0])
            render.onload = () => {
                image.value = render.result
                resolve()
            }
        })
        .then(() => {
            cropper.value = new Cropper(cropperImage.value, cropperOptions.value)
        })
    }

    const updateUserAvatar = (e) => {
        const beforeButtonText = e.target.innerHTML
        const canvas = cropper.value.getCroppedCanvas()
        e.target.disabled = true
        e.target.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   送信中'

        fetch('/tps-site/auth/change/avatar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'enctype': 'multipart/form-data',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'image': canvas.toDataURL(),
            }),
        })
        .then((response) => response.json())
        .then(res => {
            e.target.disabled = beforeButtonText
            e.target.disabled = false
            cropper.value.destroy()
            image.value = null
            emit('getLoginData')
        })
        .catch(error => {
            console.log(error)
        })
    }
</script>

<template>
    <div v-if="!image">
        <label for="inputCropperImage" class="position-relative w-100" style="cursor: pointer;">
            <img :src="'/tps-site/images/components/IconImage.png'" class="w-100" />
            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center text-light top-0" style="text-shadow: 0 0 10px black;">
                クリックで画像追加
            </div>
        </label>
        <input type="file" id="inputCropperImage" class="d-none" @change="installImage" accept="image/png, image/jpeg" />
    </div>
    <div v-else>
        <!-- トリミング用 -->
        <img ref="cropperImage" :src="image" class="w-100 z-3" />
    </div>
    <div class="text-end mx-3 my-1" v-if="image">
        <button type="button" class="btn btn-danger rounded-pill text-light" @click="image = null, cropper.destroy()">リセット</button>
        <button type="button" class="btn btn-success rounded-pill text-light ms-2" @click="updateUserAvatar">送信</button>
    </div>
</template>