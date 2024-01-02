<script setup>
    import { ref } from 'vue'

    const TankOptions = [
        'アメリカ', 'ドイツ', 'ソ連', 'イギリス', 'フランス', '日本', '中国', '欧州', '多国籍', 'その他'
    ]


</script>

<template>
    <div class="container">
        <div class="row text-center bg-dark">
            <div class="col-12 text-light border-bottom border-secondary border-3">
                <h1>戦車写真集</h1>
                <h5>今まで撮ってきたアメリカ戦車写真が全て収録されています！お楽しみください。</h5>
            </div>
            <div class="col-4 col-md-2 p-2" v-for="TankOption in TankOptions" :key="TankOption">
                <div class="btn btn-dark rounded-3 border-light w-100" v-text="TankOption + '戦車'"></div>
            </div>
        </div>
    </div>
</template>