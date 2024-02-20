<script setup>
    import { ref, onMounted, onUnmounted } from 'vue'
    import gsap from 'gsap'
    import { ScrollTrigger } from 'gsap/all'

    const pickupToggel = ref(true)
    const titles = ref([])
    const contents = ref([])
    const subContents = ref([])
    const clickContent = ref(false)
    const fadeIns = ref([])
    const rotations = ref([])
    const zooms = ref([])
    const boundElms = ref([])
    const boundElms2 = ref([])
    const boundElms3 = ref([])
    const introductions = ref([
        {
            role: '部長',
            name: '佐々木大和(18)',
            comment: '大学は東京都市大学です。<br>毎日艦これで過酷な自家発電をしています。',
            image: 'https://cdn.discordapp.com/avatars/845633537072037929/44c236f5d68d535034fbd53441b6e770.webp?size=128',
        },
        {
            role: '副部長',
            name: 'NEO(18)',
            comment: '早慶以下はFランですwww<br>ちなみに私は愛知大学です。',
            image: 'https://cdn.discordapp.com/avatars/741176758472015922/5fee77a050899c7d102eee8042035e2b.webp?size=128',
        },
        {
            role: '開発者',
            name: 'CHACCHAN(18)',
            comment: 'TPSのエンジニア枠?<br>サッサくんは17万で8万のPCを買いました。',
            image: 'https://cdn.discordapp.com/avatars/427075700625899520/e113e64b48d3cd2b6c341d7f7447f955.webp?size=128',
        },
    ])
    const clubMembers = ref([
        {
            role: 'みんなのお父さん',
            name: 'Yamagumo_Destroyer(17)',
            comment: 'うんち?うんち!?',
            image: 'https://cdn.discordapp.com/avatars/607552557602177045/2cfd0182885c9b419a98375b9a3f9f84.webp?size=128',
        },
        {
            role: 'おにぎり',
            name: 'しにがみ(17)',
            comment: 'サッサゴミ',
            image: 'https://cdn.discordapp.com/guilds/845634545800839168/users/512764577843380235/avatars/fe0e335fb7a1fbcce9a90e7b2a373944.webp?size=128',
        },
        {
            role: '高学歴を逃した男',
            name: 'たにおか(19)',
            comment: '早稲田大学受かったけど落ちました<br>青学受験急かされてます;;',
            image: 'https://cdn.discordapp.com/avatars/1007468956119990373/22e919d302a5cb5a7719e43400ab5577.webp?size=128',
        },
        {
            role: '東京大学首席',
            name: 'きさらぎ(17)',
            comment: 'ブリッツガチ勢(笑)<br>日本は共産化すべきです。',
            image: 'https://cdn.discordapp.com/avatars/761893746333450271/8833851f9e9d104a5c0c21f3223c134f.webp?size=128',
        },
        {
            role: '中退ヤリチン',
            name: 'たけうち(17)',
            comment: '数学出来なすぎて中退しました。<br>なので高校1年生です!',
            image: 'https://cdn.discordapp.com/avatars/988066620146606140/e1e06e30cd0352bbb875a752e9c8a868.webp?size=128',
        },
        {
            role: '不良品のプロ',
            name: 'ぶー(17)',
            comment: '新品買ったら不良品だったので、交換しました。<br>すると、不良品でした。',
            image: 'https://cdn.discordapp.com/avatars/961145143921111070/487547c39844ba47a9dd43ceed6043b4.webp?size=128',
        },
        {
            role: '原神が青春',
            name: 'かもん(17)',
            comment: '原神が僕の人生です。<br>成績なんて知りません。',
            image: 'https://cdn.discordapp.com/avatars/738721299626721320/889c44b21d71f0fbe5545e13bf0e4e9e.webp?size=128',
        },
        {
            role: '絵師',
            name: 'えるを(19)',
            comment: 'We are 共産党!',
            image: 'https://cdn.discordapp.com/avatars/714123236899684352/9d789b5f062b414c7050d6ccc182ef89.webp?size=128',
        },
        {
            role: 'ケモナー絵師',
            name: 'ぜろー(18)',
            comment: 'ぼくぜろー!',
            image: 'https://cdn.discordapp.com/avatars/846298084949753856/4e07fb908a7956c68b90898a6f0baa70.webp?size=128',
        },
        {
            role: '性欲Maxショタ',
            name: 'ゆいっく(18)',
            comment: '最近受験してました。<br>東大は名前書いたら受かりますw',
            image: 'https://cdn.discordapp.com/avatars/562531360569294868/de1157745beda2f006bb5b72d481371e.webp?size=128',
        },
        {
            role: '残業マシマシ社畜',
            name: 'あびす(19)',
            comment: '労働こそが正義。<br>平均与ダメ1000目指してます!',
            image: 'https://cdn.discordapp.com/avatars/796233349601296405/f231993ea47e9bc15a5bf3848597f3d3.webp?size=128',
        },
        {
            role: 'エアガンヲタク',
            name: 'えっさん(17)',
            comment: '見た目は中年、中身は子供。<br>魔法男児えーーーっさん!',
            image: 'https://cdn.discordapp.com/avatars/714338520784830474/52223695ba65883e1832e80d5cf94702.webp?size=80',
        },
    ])
    const enterSchoolLists = ref([
        {
            university: '東京都市大学',
            image: 'https://www.tcu.ac.jp/tcucms/wp-content/uploads/2022/12/yokohamacampus_01.jpg',
            member: 1,
        },
        {
            university: '千葉工業大学',
            image: 'https://www.it-chiba.ac.jp/media/institute251.jpg',
            member: 1,
        },
        {
            university: '東京外国語大学',
            image: 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Tokyo_University_of_Foreign_Studies_Building_for_lectures_and_studies.jpg/1200px-Tokyo_University_of_Foreign_Studies_Building_for_lectures_and_studies.jpg',
            member: 1,
        },
        {
            university: '広島大学',
            image: 'https://www.hiroshima-u.ac.jp/system/files/158049/09_%E7%8F%BE%E5%9C%A8%E3%81%AE%E6%9D%B1%E5%BA%83%E5%B3%B6%E3%82%AD%E3%83%A3%E3%83%B3%E3%83%91%E3%82%B9.jpg',
            member: 1,
        },
        {
            university: '早稲田大学',
            image: 'https://www.tokyo12univ.com/wp-content/uploads/2020/02/mv_waseda.jpg',
            member: 1,
        },
        {
            university: '愛知大学',
            image: 'https://p1.ssl-cdn.jp/var/data/u/dd/5fa34cce688/img/8b/8b6034b104750e3c3318e4e0593c2ac2.jpeg',
            member: 1,
        }
    ])

    onMounted(() => {
        titles.value = document.querySelectorAll('.title')
        contents.value = document.querySelectorAll('.content')
        subContents.value = document.querySelectorAll('.subContent')
        fadeIns.value = document.querySelectorAll('.fadeIn')
        rotations.value = document.querySelectorAll('.rotation')
        zooms.value = document.querySelectorAll('.zoom')
        boundElms.value = document.querySelectorAll('.boundElm')
        boundElms2.value = document.querySelectorAll('.boundElm2')
        boundElms3.value = document.querySelectorAll('.boundElm3')
        setTimeout(() => {
            StartGsap()
        }, 1000)
    })

    onUnmounted(() => {
        StartGsap()
    })

    const StartGsap = () => {
        gsap.registerPlugin(ScrollTrigger)
        gsap.utils.toArray(titles.value).forEach((target) => {
            gsap.timeline({
                scrollTrigger: {
                    trigger: target,
                    start: 'top bottom',
                    end: 'top center',
                    toggleActions: 'play none none reset',
                    id: target,
                    scrub: 0.5,
                }
            })
            .from(target, {
                opacity: 0,
                x: '-5vw',
                duration: 3,
            })
        })

        gsap.utils.toArray(contents.value).forEach((target) => {
            gsap.timeline({
                scrollTrigger: {
                    trigger: target,
                    start: 'top bottom',
                    end: 'top center',
                    toggleActions: 'play none none reset',
                    id: target,
                    scrub: 0.5,
                }
            })
            .from(target, {
                opacity: 0,
                y: '10vw',
                duration: 3,
            })
        })

        gsap.utils.toArray(subContents.value).forEach((target) => {
            gsap.timeline({
                scrollTrigger: {
                    trigger: target,
                    start: 'top bottom',
                    end: 'center center',
                    toggleActions: 'play none none reset',
                    id: target,
                    scrub: 0.5,
                }
            })
            .from(target, {
                opacity: 0,
                duration: 3,
            })
        })

        gsap.utils.toArray(fadeIns.value).forEach((target) => {
            gsap.timeline({
                scrollTrigger: {
                    trigger: target,
                    start: 'top bottom',
                }
            })
            .from(target, 1, {
                opacity: 0,
                y: 20,
            })
        })

        gsap.utils.toArray(rotations.value).forEach((target) => {
            gsap.timeline({
                scrollTrigger: {
                    trigger: target,
                    start: 'top bottom',
                    end: '90% center',
                    toggleActions: 'play none none reset',
                    id: target,
                    scrub: 0.5,
                }
            })
            .from(target, {
                opacity: 0,
                y: '10vw',
                rotate: 360,
                scale: 0,
                duration: 3,
            })
        })

        gsap.utils.toArray(zooms.value).forEach((target) => {
            gsap.timeline({
                scrollTrigger: {
                    trigger: target,
                    start: 'top bottom',
                    end: 'top center',
                    toggleActions: 'play none none reset',
                    id: target,
                    scrub: 0.5,
                }
            })
            .from(target, {
                opacity: 0,
                scale: 0,
                duration: 3,
            })
        })

        gsap.utils.toArray(boundElms.value).forEach((target) => {
            gsap.timeline({
                repeat: -1,
                repeatDelay: 0,
                yoyo: true,
            })
            .from(target, 2, 
            {
                y: '-50px',
            })
        }) 

        gsap.utils.toArray(boundElms2.value).forEach((target) => {
            gsap.timeline({
                repeat: -1,
                repeatDelay: 0,
                yoyo: true,
            })
            .from(target, 1, 
            {
                y: '50px',
            })
        }) 

        gsap.utils.toArray(boundElms3.value).forEach((target) => {
            gsap.timeline({
                repeat: -1,
                repeatDelay: 0,
                yoyo: true,
            })
            .from(target, 1.5, 
            {
                y: '-30px',
            })
        }) 
    }
</script>

<template>
    <div class="container text-bg-dark">
        <!-- ピックアップ -->
        <div class="position-fixed z-3" style="top: 80%; right: 10px;">
            <div class="card card-body rounded-pill border-0 p-1 ">
                <div class="d-flex align-items-center justify-content-start text-dark" v-if="pickupToggel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                    <router-link :to="{ name: 'news' }" class="fw-bold ms-2 text-decoration-none">NEWS/ピックアップ</router-link>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" @click="pickupToggel = !pickupToggel" class="bi bi-caret-right-fill" viewBox="0 0 16 16" style="cursor: pointer;">
                        <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                    </svg>
                </div>
                <div class="d-flex align-items-center justify-content-start text-dark" v-if="!pickupToggel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" @click="pickupToggel = !pickupToggel" class="bi bi-caret-left-fill" viewBox="0 0 16 16" style="cursor: pointer;">
                        <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="row my-5">
            <div class="col-12 col-md-6">
                <!-- PC -->
                <div class="d-none d-md-block">
                    <div class="fw-bold title" style="font-size: 50px;">
                        World of Tanks Blitz<br>
                        プレイヤー向けの<br>
                        幅広い写真コレクション
                    </div>
                    <div class="text-secondary mb-5 subContent">
                        TPSはWorld of Tanks Blitz内で完結することを大切にしています。
                        私たちはあなたと同じようにきれいな写真を愛しています。
                        私たちの活動はゲーム内の写真を収めることです。
                        すべての写真は、素晴らしいプレイヤーに提供するために慎重に考慮されています。
                    </div>
                    <div class="subContent">
                        <router-link :to="{ name: 'image' }" class="btn btn-primary fw-bold rounded-pill py-1">START VIEW</router-link>
                    </div>
                </div>
                <!-- Mobile -->
                <div class="d-block d-md-none">
                    <img src="https://picsum.photos/1920/1080" class="rounded-5 w-100 mb-3 content" loading="lazy" />
                </div>
            </div>
            <div class="col-12 col-md-6">
                <!-- PC -->
                <div class="d-none d-md-block">
                    <img src="https://picsum.photos/1920/1080" class="rounded-5 w-100 content" loading="lazy" />
                </div>
                <!-- Mobile -->
                <div class="d-block d-md-none">
                    <div class="fw-bold title" style="font-size: 30px;">
                        World of Tanks Blitz<br>
                        プレイヤー向けの<br>
                        幅広い写真コレクション
                    </div>
                    <div class="text-secondary mb-5 subContent">
                        TPSはWorld of Tanks Blitz内で完結することを大切にしています。
                        私たちはあなたと同じようにきれいな写真を愛しています。
                        私たちの活動はゲーム内の写真を収めることです。
                        すべての写真は、素晴らしいプレイヤーに提供するために慎重に考慮されています。
                    </div>
                    <div class="subContent">
                        <router-link :to="{ name: 'image' }" class="btn btn-primary fw-bold rounded-pill py-1">START VIEW</router-link>
                    </div>
                </div>
            </div>
            <div class="position-relative" style="margin: 100px 0;">
                <div class="position-absolute" style="top: 0; right: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-box boundElm" viewBox="0 0 16 16">
                        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                    </svg>
                </div>
                <div class="position-absolute" style="bottom: 0; left: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-box" viewBox="0 0 16 16">
                        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <img :src="'/tps-site/images/components/basePoint.jpg'" class="rounded-5 w-100 content" loading="lazy" />
            </div>
            <div class="col-12 col-md-6">
                <div class="fw-bold d-none d-md-block title" style="font-size: 50px;">
                    私たちの拠点
                </div>
                <div class="fw-bold d-block d-md-none title" style="font-size: 30px;">
                    私たちの拠点
                </div>
                <div class="text-secondary mb-5 subContent">
                    私たちは神奈川県横浜市を拠点にして活動しています。
                    また、主にDISCORDでコミニティを築いています。
                    ここでは、World of Tanks Blitzをはじめ、さまざまなゲーム、コミニティが栄えています。
                    参加と詳細は以下からはじめてください。
                </div>
                <a 
                    href="https://discord.gg/8BWqNzDZP2" 
                    class="btn btn-primary fw-bold rounded-pill py-1 me-3 subContent"
                    target="_blank"
                    >JOIN
                </a>
                <!-- Detail -->
                <button 
                    type="button" 
                    class="btn btn-secondary fw-bold rounded-pill py-1 subContent" 
                    @click="clickContent = !clickContent"
                    >DETAIL
                </button>
                <div class="card card-body text-bg-dark mt-5 border subContent" v-if="clickContent">
                    このサーバーはみなさんの節度あるご利用の上で、2年間無事に運営を続けてまいることができました。適切な利用を
                    こころがけていただきますよう、心よりお願い申し上げます。
                </div>
            </div>
            <div class="position-relative" style="margin: 100px 0;">
                <div class="position-absolute" style="bottom: 10; left: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-box boundElm3" viewBox="0 0 16 16">
                        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                    </svg>
                </div>
                <div class="position-absolute text-center w-100" style="top: 50px; left: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-box" viewBox="0 0 16 16">
                        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                    </svg>
                </div>
                <div class="position-absolute text-center" style="bottom: 0; right: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="55" height="55" fill="currentColor" class="bi bi-box boundElm2" viewBox="0 0 16 16">
                        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="fw-bold text-center mb-3 content" style="font-size: 50px;">
                    私たちの活動
                </div>
                <div class="row mb-5">
                    <div class="col-5 text-end">
                        <img :src="'/tps-site/images/components/IconImage.png'" class="rounded-circle w-50 rotation" loading="lazy" />
                    </div>
                    <div class="col-2 d-flex align-items-center justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
                        </svg>
                    </div>
                    <div class="col-5 text-start">
                        <img :src="'/tps-site/images/components/WotbIcon.png'" class="rounded-circle w-50 rotation" loading="lazy" />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card card-body text-bg-dark border-3 border-light p-2 rounded-5">
                            <div class="fw-bold fs-3 mx-5 my-2">
                                <div class="h1 text-center fw-bold mb-3 d-none d-md-block" style="font-size: 30px;">World of Tanks Blitz 日本公式からコメントいただきました!</div>
                                <div class="h1 text-center fw-bold mb-3 d-block d-md-none" style="font-size: 30px;">World of Tanks Blitz<br>日本公式からコメントいただきました!</div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <img :src="'/tps-site/images/components/CommentTwitterForTPS.png'" class="rounded-5 w-100 content" />
                                    </div>
                                    <div class="d-block d-md-none" style="margin: 15px 0;"></div>
                                    <div class="col-12 col-md-6">
                                        <img :src="'/tps-site/images/components/CommentTwitterForTPS2.png'" class="rounded-5 w-100 content" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="fw-bold text-center mb-3 content" style="font-size: 50px;">
                            進学状況
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3" v-for="enterSchoolList in enterSchoolLists" :key="enterSchoolList.university">
                                <div class="card card-body rounded-5 fadeIn">
                                    <div class="row">
                                        <div class="col-4">
                                            <img :src="enterSchoolList.image" class="w-100 rounded-5 shadow" style="aspect-ratio: 1 / 1; object-fit: cover;" loading="lazy" />
                                        </div>
                                        <div class="col-8">
                                            <div class="fw-bold text-dark" style="font-size: 30px;" v-text="enterSchoolList.university"></div>
                                            <div class="fw-bold text-dark ms-3" style="font-size: 30px;" v-text="enterSchoolList.member + '人'"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-relative" style="margin: 100px 0;">
                <div class="position-absolute text-center" style="bottom: 0; right: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-box boundElm3" viewBox="0 0 16 16">
                        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="fw-bold text-center mb-3 content" style="font-size: 50px;">
                    運営者紹介
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 mb-5" v-for="introduction in introductions" :key="introduction.name">
                        <div class="card card-body text-dark rounded-5 p-2 zoom">
                            <div class="row">
                                <div class="col-4 border-end border-3">
                                    <img 
                                        :src="introduction.image"
                                        class="rounded-5 w-100"
                                    />
                                </div>
                                <div class="col-8">
                                    <h1 class="fw-bold" v-text="introduction.role"></h1>
                                    <div class="ms-3">
                                        <h1 class="fs-2" v-text="introduction.name"></h1>
                                        <div class="fs-6 text-secondary" v-html="introduction.comment"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="fw-bold text-center mb-3 content" style="font-size: 50px;">
                    主要部員紹介
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 mb-5" v-for="clubMember in clubMembers" :key="clubMember.name">
                        <div class="card card-body text-dark rounded-5 p-2 zoom">
                            <div class="row">
                                <div class="col-4 border-end border-3">
                                    <img 
                                        :src="clubMember.image"
                                        class="rounded-5 w-100"
                                    />
                                </div>
                                <div class="col-8">
                                    <h1 class="fw-bold" v-text="clubMember.role"></h1>
                                    <div class="ms-3">
                                        <h1 class="fs-2" v-text="clubMember.name"></h1>
                                        <div class="fs-6 text-secondary" v-html="clubMember.comment"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-relative" style="margin: 100px 0;">
                <div class="position-absolute" style="top: 0; left: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-box boundElm" viewBox="0 0 16 16">
                        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                    </svg>
                </div>
                <div class="position-absolute" style="bottom: 0; right: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-box" viewBox="0 0 16 16">
                        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>