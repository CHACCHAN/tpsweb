<script setup>
    import { ref } from 'vue'
    
    const LatestTankImage = location.href + 'images/home/WhatTPS/LatestTankImage.png'
    const LatestPartyImage = location.href + 'images/home/WhatTPS/LatestPartyImage.png'
</script>

<template>
    <div class="container">
        <div class="h4 fw-bold border-bottom border-dark border-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cursor-fill" viewBox="0 0 16 16">
                <path d="M14.082 2.182a.5.5 0 0 1 .103.557L8.528 15.467a.5.5 0 0 1-.917-.007L5.57 10.694.803 8.652a.5.5 0 0 1-.006-.916l12.728-5.657a.5.5 0 0 1 .556.103z"/>
            </svg>   ようこそ！戦車写真部ホームページへ
        </div>
        <div class="card mb-5">
            <div class="card-header bg-primary border-0 text-light fw-bold fs-5 py-1">
                あいさつ
            </div>
            <div class="card-body py-1">
                WoTB戦車写真部(-TPS-)のホームページへようこそ！<br>
                ここでは、今まで撮影してきた写真など、様々なコンテンツを用意しています！お楽しみいただけたら幸いです！
            </div>
        </div>
        <div class="card mb-5">
            <div class="card-header bg-danger border-0 text-light fw-bold fs-5 py-1">
                ご注意
            </div>
            <div class="card-body py-1">
                こちらのホームページに収録されている写真を動画素材等、公で使用する場合は、使用許可をお問い合わせフォームで申請するようお願いします。<br>
                アイコンや背景などで使用する場合は勝手に使用していただいても結構です。
            </div>
        </div>

        <div class="h4 fw-bold border-bottom border-dark border-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cursor-fill" viewBox="0 0 16 16">
                <path d="M14.082 2.182a.5.5 0 0 1 .103.557L8.528 15.467a.5.5 0 0 1-.917-.007L5.57 10.694.803 8.652a.5.5 0 0 1-.006-.916l12.728-5.657a.5.5 0 0 1 .556.103z"/>
            </svg>   お知らせ
        </div>
        <div class="mb-5">
            <h5>
                WoTB戦車写真部の管理するクラン(-TPS-)は解散し、活動を終了いたしました。<br>
                今までのご愛嬌ありがとうございました。<br>
                YouTube、TikTokでの活動は現在休止しています。<br>
                申し訳ございません
            </h5>
        </div>

        <div class="h4 fw-bold border-bottom border-dark border-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cursor-fill" viewBox="0 0 16 16">
                <path d="M14.082 2.182a.5.5 0 0 1 .103.557L8.528 15.467a.5.5 0 0 1-.917-.007L5.57 10.694.803 8.652a.5.5 0 0 1-.006-.916l12.728-5.657a.5.5 0 0 1 .556.103z"/>
            </svg>   最新の投稿
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <h5>ソ連戦車のIS-3</h5>
                <div class="card mb-5">
                    <div class="card-body p-0">
                        <img class="rounded-1" :src="LatestTankImage" width="100%" alt="">
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <h5>オフ会</h5>
                <div class="card mb-5">
                    <div class="card-body p-0">
                        <img class="rounded-1" :src="LatestPartyImage" width="100%" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>