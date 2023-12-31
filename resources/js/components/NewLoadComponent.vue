<script setup>
    import { onMounted, ref } from 'vue'

    onMounted(() => {
        Key()
    })
</script>

<template>
    <div class="position-relative bg-dark" style="width: 100%; height: 100vh">
        <div class="position-absolute text-light" style="top: 50%; left: 50%;">
            <router-link to="/home" id="LinkKey" @click="isLoadedView">ホームへのリンク</router-link>
        </div>
    </div>
</template>