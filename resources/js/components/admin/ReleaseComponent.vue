<script setup>
    import { ref } from 'vue'
    const defaultDeveloper = ref('CHACCHAN')
    const defaultDeveloperIcon = ref('https://cdn.discordapp.com/avatars/427075700625899520/e113e64b48d3cd2b6c341d7f7447f955.webp?size=128')
    const defaultDeveloperGitHub = ref('https://github.com/CHACCHAN')
    const updateCards = ref([
        {
            version: '1.0',
            dev: defaultDeveloper.value,
            dev_icon: defaultDeveloperIcon.value,
            github: defaultDeveloperGitHub.value,
            date: '2024年02月19日',
            comment: `
                初期リリースです。<br>
                まだマニュアルがないので、開発者に直接問い合わせください。
            `,
        }
    ])
</script>

<template>
    <div class="container">
        <div class="h4">更新</div>
        <div class="card mb-3" v-for="(updateCard, index) in updateCards" :key="updateCard.version">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-start mb-3">
                    <div class="fw-bold fs-4">{{ 'v' + updateCard.version }}</div>
                    <div class="ms-3 text-success fw-bold border rounded-pill p-1 py-0" v-if="0 == index">Latest</div>
                </div>
                <div class="d-flex align-items-center justify-content-start">
                    <img :src="updateCard.dev_icon" class="rounded-circle" width="20px" />
                    <a :href="updateCard.github" class="ms-1 fw-bold text-secondary text-decoration-none" target="_blank">{{ updateCard.dev }}</a>
                </div>
            </div>
            <div class="card-body">
                <div v-html="updateCard.comment"></div>
            </div>
            <div class="card-footer bg-light border-0 py-0 text-secondary text-end">
                {{ updateCard.date }}
            </div>
        </div>
    </div>
</template>