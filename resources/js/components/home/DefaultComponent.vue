<script setup>
    import { ref, onMounted } from 'vue'

    const playVideos = ref([
        {
            link: 'https://cdn.discordapp.com/attachments/877880817504899122/1207565551291994112/A7A4B8FB-07AE-49FD-B4B9-79C912EA4187-1026-0000276E699A3AF1.mov?ex=65e01c0e&is=65cda70e&hm=1413176083b4ad15da58eee5e8e8a0b62f3c112c5cf328c1f60f648b7fe9ac0c&',
        },
        {
            link: 'https://cdn.discordapp.com/attachments/877880817504899122/1207565567947444234/474A34B2-4740-4442-A4F5-22B2E53AFC19-1769-00002775F574FD3D.mov?ex=65e01c12&is=65cda712&hm=9e3da8b4dc810346991409c68c0e64a51beb18b3634ee7436359f433948ed60c&',
        },
        {
            link: 'https://cdn.discordapp.com/attachments/877880817504899122/1207565584879984680/58626B21-D447-467E-9D46-110FACC4D5EC-2111-0000277CD25ECA93.mov?ex=65e01c16&is=65cda716&hm=99089a7c80f59941afddd92ca178b73ad12add1cbbb371bf4f2572f6bc997c32&',
        },
    ])
</script>

<template>
    <div>
        <div id="carouselViewImage" class="carousel slide carousel-fade z-1" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselViewImage" class="active" aria-current="true" data-bs-slide-to="0" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselViewImage" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselViewImage" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <!-- 1 -->
                <div class="carousel-item active">
                    <video class="d-block topImage" loop muted autoplay playsinline>
                        <source 
                            :src="'/tps-site/videos/backVideo1.mp4'"
                            type="video/mp4"
                        />
                    </video>
                    <div class="carousel-caption d-none d-md-block">
                        <div class="h3 fw-bold" v-text="'ドイツ&ソ連'"></div>
                    </div>
                    <div class="position-absolute w-100" style="top: 20%;">
                        <!-- PC -->
                        <div class="h1 text-light fw-bold mb-0 ms-5 d-none d-md-block fadeIn" style="font-size: 100px;">Shot on TPS</div>
                        <p class="h1 text-light fw-bold ms-5 d-none d-md-block fadeIn">World of Tanks Blitz 写真集をもっと</p>
                        <p class="h1 fw-bold ms-5 d-none d-md-block" v-text="'- ドイツ&ソ連を表示中'" style="color: rgba(245, 106, 106, 0.808);"></p>
                        <!-- MOBILE -->
                        <div class="h1 text-light text-center fw-bold mb-0 d-block d-md-none fadeIn" style="font-size: 60px;">Shot on TPS</div>
                        <p class="h3 text-light text-center fw-bold d-block d-md-none fadeIn">World of Tanks Blitz 写真集をもっと</p>
                        <p class="h1 fw-bold ms-5 d-block d-md-none" v-text="'- ドイツ&ソ連を表示中'" style="color: rgba(245, 106, 106, 0.808);"></p>
                    </div>
                </div>
                <!-- 2 -->
                <div class="carousel-item">
                    <video class="d-block topImage" loop muted autoplay playsinline>
                        <source 
                            :src="'/tps-site/videos/backVideo2.mp4'"
                            type="video/mp4"
                        />
                    </video>
                    <div class="carousel-caption d-none d-md-block">
                        <div class="h3 fw-bold" v-text="'ShPTK-TVP 100'"></div>
                    </div>
                    <div class="position-absolute w-100" style="top: 20%;">
                        <!-- PC -->
                        <div class="h1 text-light fw-bold mb-0 ms-5 d-none d-md-block fadeIn" style="font-size: 100px;">Shot on TPS</div>
                        <p class="h1 text-light fw-bold ms-5 d-none d-md-block fadeIn">World of Tanks Blitz 写真集をもっと</p>
                        <p class="h1 fw-bold ms-5 d-none d-md-block" v-text="'- ShPTK-TVP 100を表示中'" style="color: rgba(245, 106, 106, 0.808);"></p>
                        <!-- MOBILE -->
                        <div class="h1 text-light text-center fw-bold mb-0 d-block d-md-none fadeIn" style="font-size: 60px;">Shot on TPS</div>
                        <p class="h3 text-light text-center fw-bold d-block d-md-none fadeIn">World of Tanks Blitz 写真集をもっと</p>
                        <p class="h1 fw-bold ms-5 d-block d-md-none" v-text="'- ShPTK-TVP 100を表示中'" style="color: rgba(245, 106, 106, 0.808);"></p>
                    </div>
                </div>
                <!-- 3 -->
                <div class="carousel-item">
                    <video class="d-block topImage" loop muted autoplay playsinline>
                        <source 
                            :src="'/tps-site/videos/backVideo3.mp4'"
                            type="video/mp4"
                        />
                    </video>
                    <div class="carousel-caption d-none d-md-block">
                        <div class="h3 fw-bold" v-text="'Caliban'"></div>
                    </div>
                    <div class="position-absolute w-100" style="top: 20%;">
                        <!-- PC -->
                        <div class="h1 text-light fw-bold mb-0 ms-5 d-none d-md-block fadeIn" style="font-size: 100px;">Shot on TPS</div>
                        <p class="h1 text-light fw-bold ms-5 d-none d-md-block fadeIn">World of Tanks Blitz 写真集をもっと</p>
                        <p class="h1 fw-bold ms-5 d-none d-md-block" v-text="'- Calibanを表示中'" style="color: rgba(245, 106, 106, 0.808);"></p>
                        <!-- MOBILE -->
                        <div class="h1 text-light text-center fw-bold mb-0 d-block d-md-none fadeIn" style="font-size: 60px;">Shot on TPS</div>
                        <p class="h3 text-light text-center fw-bold d-block d-md-none fadeIn">World of Tanks Blitz 写真集をもっと</p>
                        <p class="h1 fw-bold ms-5 d-block d-md-none" v-text="'- Calibanを表示中'" style="color: rgba(245, 106, 106, 0.808);"></p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselViewImage" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselViewImage" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</template>

<style scoped>
    .topImage {
        width: 100%;
        height: 100vh;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
        object-fit: cover;
    }
    .fadeIn:nth-child(1) {
        animation: fadeIn 1s both;
        animation-delay: .2s;
    }
    .fadeIn:nth-child(2) {
        animation: fadeIn 1s both;
        animation-delay: .5s;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
            transform: translate(0, 20px);
        }
        100% {
            opacity: 1;
            transform: translate(0, 0);
        }
    }
</style>