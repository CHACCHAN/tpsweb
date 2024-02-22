import{_ as H,r as n,e as D,g as R,o as C,c as k,h as f,a as t,v as r,x as h,y as B,j as A,w as O,b as N,i as L,p as U,d as z}from"./app-kdUhGple.js";import{T as Z,_ as q}from"./FooterComponent-sYsgU-c0.js";import{P as G}from"./PrivacyPolicy-Dhv6FZ3n.js";const c=b=>(U("data-v-b87c12f0"),b=b(),z(),b),J={id:"backImage"},K={class:"container h-100"},W=c(()=>t("div",{class:"fw-bold text-light d-none d-md-block",style:{"text-shadow":"0 0 5px rgb(0, 0, 0)","font-size":"40px"}},"Contact/問い合わせ",-1)),X={class:"card card-body mb-5 rounded-0",style:{background:"rgba(255, 255, 255, 0.8)",margin:"60px 0"}},Y={class:"row mt-5"},$=N('<div class="col-3 d-none d-md-block" data-v-b87c12f0><div class="card card-body fw-bold border-0 rounded-0 text-bg-dark d-flex align-items-center justify-content-center w-100" style="padding:50px 0;" data-v-b87c12f0><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16" data-v-b87c12f0><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" data-v-b87c12f0></path></svg><div class="fs-5 mt-3" data-v-b87c12f0> Contact/問い合わせ </div></div></div>',1),Q={class:"col-12 col-md-9"},tt={class:"card card-body h-100 border-0 rounded-0 pt-0"},et=c(()=>t("div",{class:"border-start border-5 border-primary",style:{padding:"52px 0"}},[t("div",{class:"fs-1 fw-bold ms-5"},"Contact/問い合わせ")],-1)),ot={key:0},at={class:"my-3"},st=c(()=>t("div",{class:"form-label"},"苗字とお名前",-1)),nt={class:"row"},lt={class:"col"},ct={class:"col"},dt={class:"mb-3"},it=c(()=>t("label",{for:"inputEmailAddress",class:"form-label"},"Emailアドレス",-1)),rt={class:"mb-3"},ut=c(()=>t("label",{for:"inputSubject",class:"form-label"},"件名",-1)),pt={class:"mb-3"},vt=c(()=>t("label",{for:"inputContent",class:"form-label"},"内容",-1)),mt={class:"form-check mb-3"},ht=c(()=>t("label",{class:"form-check-label",for:"flexCheckLicence"},[t("a",{href:"#","data-bs-toggle":"modal","data-bs-target":"#LicenceModal"},"プライバシーポリシー"),L("に同意する ")],-1)),bt={class:"modal fade",id:"LicenceModal",tabindex:"-1","aria-hidden":"true"},_t={class:"modal-dialog modal-xl modal-dialog-centered"},ft={class:"modal-content"},gt=c(()=>t("div",{class:"modal-header border-0"},[t("button",{type:"button",class:"btn-close","data-bs-dismiss":"modal","aria-label":"Close"})],-1)),yt={class:"modal-body"},xt={class:"text-end"},wt=["disabled"],Ct={key:1},kt={class:"d-flex align-items-center justify-content-center"},Pt={class:"text-center"},At=N('<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-send-check-fill" viewBox="0 0 16 16" data-v-b87c12f0><path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 1.59 2.498C8 14 8 13 8 12.5a4.5 4.5 0 0 1 5.026-4.47L15.964.686Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z" data-v-b87c12f0></path><path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Zm-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686Z" data-v-b87c12f0></path></svg><div class="fs-1 fw-bold" data-v-b87c12f0>受付しました</div><div class="text-secondary" data-v-b87c12f0>回答は後日メールからご返信させていただきます。</div><div class="text-secondary" data-v-b87c12f0>このページは閉じても大丈夫です。</div>',4),Nt={__name:"ContactView",setup(b){const P=location.protocol+"//"+location.host+"/tps-site/images/components/IconImage.png",g=n(),y=n(),x=n(),u=n(null),p=n(null),v=n(null),d=n(null),i=n(null),_=n(!1),w=n(!0);D(()=>{document.title="問い合わせ",T(),E()});const M=e=>{const s=e.target;let o=getComputedStyle(s).lineHeight;o=o.replace(/[^-\d\.]/g,"");const l=(s.value+`
`).match(/\n/g).length;s.style.height=o*l+20+"px"},S=async e=>{if(u.value&&p.value&&v.value&&d.value&&i.value&&_.value){const a=e.target.innerHTML;e.target.innerHTML='<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   送信中',e.target.disabled=!0;const s=j(),o=I();await Promise.all([s,o]),e.target.innerHTML=a,e.target.disabled=!1,w.value=!1}},j=()=>new Promise((e,a)=>{const s=u.value,o=p.value,l=v.value,V=d.value,F=i.value;fetch("/tps-site/post/contact/reception",{method:"POST",headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content,"Content-Type":"application/json"},body:JSON.stringify({first_name:s,last_name:o,email:l,subject:V,content:F})}).then(m=>m.json()).then(m=>{e()}).catch(m=>{console.log(m),a(m)})}),I=()=>{const e=d.value,a=i.value;return new Promise((s,o)=>{fetch("/tps-site/post/discord/contact/webhook",{method:"POST",headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content,"Content-Type":"application/json"},body:JSON.stringify({subject:e,content:a})}).then(l=>l.json()).then(l=>{s()}).catch(l=>{console.log(l),o(l)})})},T=()=>{fetch("/tps-site/get/env").then(e=>e.json()).then(e=>{g.value=e.ResponseData}).catch(e=>{console.log(e)})},E=()=>{fetch("/tps-site/auth/login/check").then(e=>e.json()).then(e=>{y.value=e.ResponseData,x.value=e.Adminstrator}).catch(e=>{console.log(e)})};return(e,a)=>{const s=R("router-link");return C(),k("div",J,[f(Z,{APP_NAME:g.value,LoginFlag:y.value,Adminstrator:x.value,IconImage:P,style:{"padding-bottom":"60px"}},null,8,["APP_NAME","LoginFlag","Adminstrator"]),t("div",K,[W,t("div",X,[t("div",Y,[$,t("div",Q,[t("div",tt,[et,w.value?(C(),k("div",ot,[t("div",at,[st,t("div",nt,[t("div",lt,[r(t("input",{type:"text","onUpdate:modelValue":a[0]||(a[0]=o=>u.value=o),class:"form-control rounded-pill",placeholder:"姓","aria-label":"姓"},null,512),[[h,u.value]])]),t("div",ct,[r(t("input",{type:"text","onUpdate:modelValue":a[1]||(a[1]=o=>p.value=o),class:"form-control rounded-pill",placeholder:"名","aria-label":"名"},null,512),[[h,p.value]])])])]),t("div",dt,[it,r(t("input",{type:"email","onUpdate:modelValue":a[2]||(a[2]=o=>v.value=o),class:"form-control rounded-pill",id:"inputEmailAddress",placeholder:"email@example.com"},null,512),[[h,v.value]])]),t("div",rt,[ut,r(t("input",{type:"text","onUpdate:modelValue":a[3]||(a[3]=o=>d.value=o),class:"form-control rounded-pill",id:"inputSubject",placeholder:"提供依頼/改善の提案"},null,512),[[h,d.value]])]),t("div",pt,[vt,r(t("textarea",{"onUpdate:modelValue":a[4]||(a[4]=o=>i.value=o),class:"form-control",id:"inputContent",style:{height:"44px",resize:"none",overflow:"hidden"},placeholder:"入力してください",onInput:M},`
                                `,544),[[h,i.value]])]),t("div",mt,[r(t("input",{class:"form-check-input",type:"checkbox","onUpdate:modelValue":a[5]||(a[5]=o=>_.value=o),value:"",id:"flexCheckLicence"},null,512),[[B,_.value]]),ht]),t("div",bt,[t("div",_t,[t("div",ft,[gt,t("div",yt,[f(G)])])])]),t("div",xt,[t("button",{type:"button",class:"btn btn-primary text-light py-1 rounded-pill",onClick:S,disabled:!u.value||!p.value||!v.value||!d.value||!i.value||!_.value},"送信 ",8,wt)])])):A("",!0),w.value?A("",!0):(C(),k("div",Ct,[t("div",kt,[t("div",Pt,[At,f(s,{to:{name:"home"},class:"btn btn-primary py-1 mt-5 rounded-pill"},{default:O(()=>[L("ホーム")]),_:1})])])]))])])])])]),f(q,{APP_NAME:g.value,LoginFlag:y.value,Adminstrator:x.value,IconImage:P},null,8,["APP_NAME","LoginFlag","Adminstrator"])])}}},jt=H(Nt,[["__scopeId","data-v-b87c12f0"]]);export{jt as default};