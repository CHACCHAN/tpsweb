import{L as C}from"./LoadingComponent-IEZKlMOA.js";import{T as D,_ as I}from"./FooterComponent-Mzi7-c5p.js";import"./vue-quill.bubble-B1t1Vazk.js";import{r as s,o as M,b as u,c as v,h as N,i as h,d as P,e as g,t as b,F as w}from"./app-mM3nzJHX.js";const x={class:"container bg-light rounded-4"},E=["textContent"],F={class:"ql-snow"},j=["innerHTML"],q={__name:"PostView",setup(B){const p=s(!0),d=location.protocol+"//"+location.host+"/tps-site/images/components/IconImage.png",l=s(),c=s(),i=s(),a=s([]),_=s([]),r=s(),n=s();M(()=>{A(),f(),L()});const m=()=>{const t=decodeURI(location.pathname).split("/")[4];for(let e=0;e<a.value.length;e++)if(a.value[e].title===t&&a.value[e].public){document.title=a.value[e].title,r.value=a.value[e].content,n.value=a.value[e].updated_at;break}else if(a.value[e].title===t&&!a.value[e].public){document.title="非公開",r.value="このページは非公開設定になっています",n.value=!1;break}},A=()=>{fetch("/tps-site/get/env").then(t=>t.json()).then(t=>{l.value=t.ResponseData}).catch(t=>{console.log(t)})},f=()=>{fetch("/tps-site/auth/login/check").then(t=>t.json()).then(t=>{c.value=t.ResponseData,i.value=t.Adminstrator}).catch(t=>{console.log(t)})},L=async()=>{const t=k(),e=y();await Promise.all([t,e]),m(),p.value=!1},k=()=>new Promise((t,e)=>{fetch("/tps-site/get/postdata").then(o=>o.json()).then(o=>{a.value=o.responseData,t()}).catch(o=>{console.log(o),e(o)})}),y=()=>new Promise((t,e)=>{fetch("/tps-site/get/postcategory").then(o=>o.json()).then(o=>{_.value=o.responseData,t()}).catch(o=>{console.log(o),e(o)})});return(t,e)=>(u(),v(w,null,[p.value?(u(),N(C,{key:0})):h("",!0),P(D,{APP_NAME:l.value,LoginFlag:c.value,Adminstrator:i.value,IconImage:d,style:{"padding-bottom":"60px"},onClick:e[0]||(e[0]=o=>m())},null,8,["APP_NAME","LoginFlag","Adminstrator"]),g("div",x,[n.value?(u(),v("p",{key:0,textContent:b("投稿日時 : "+n.value),class:"text-secondary text-end"},null,8,E)):h("",!0),g("div",F,[g("div",{class:"ql-editor",innerHTML:r.value},null,8,j)])]),P(I,{APP_NAME:l.value,LoginFlag:c.value,Adminstrator:i.value,IconImage:d},null,8,["APP_NAME","LoginFlag","Adminstrator"])],64))}};export{q as default};