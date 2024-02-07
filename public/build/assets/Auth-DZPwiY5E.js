import{u as T,r as t,o as E,b as h,c as N,h as w,i as y,e,t as $,q as b,v as I,s as P,F as L,_ as F,x as j,p as B,g as R,j as A,d as O,w as M,k as U,n as Z,l as q}from"./app-mM3nzJHX.js";import{L as S}from"./LoadingComponent-IEZKlMOA.js";const D={class:"text-center text-light"},z=e("svg",{xmlns:"http://www.w3.org/2000/svg",width:"100",height:"100",fill:"currentColor",class:"bi bi-envelope-at-fill",viewBox:"0 0 16 16"},[e("path",{d:"M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2H2Zm-2 9.8V4.698l5.803 3.546L0 11.801Zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586l-1.239-.757ZM16 9.671V4.697l-5.803 3.546.338.208A4.482 4.482 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671Z"}),e("path",{d:"M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034v.21Zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791Z"})],-1),J=["textContent"],K=e("hr",null,null,-1),X=e("h2",null,"認証コード",-1),H={class:"form-text text-danger text-start"},G={class:"text-end mt-5"},W={__name:"RegisterEmailComponent",props:{InputFirstName:String,InputLastName:String,InputNickName:String,InputEmail:String,InputPassword:String,Token:Number},setup(p){const x=T(),u=p,o=t(),r=t(!1),i=t(!1);E(()=>{document.title="メール確認",document.getElementById("InputToken").addEventListener("input",()=>{r.value=!1}),document.addEventListener("keypress",s=>{s.key==="Enter"&&a()})});function a(){i.value=!0,fetch("register/create",{method:"POST",headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content,"Content-Type":"application/json"},body:JSON.stringify({Token:u.Token,InputToken:o.value,first_name:u.InputFirstName,last_name:u.InputLastName,name:u.InputNickName,email:u.InputEmail,password:u.InputPassword})}).then(s=>s.json()).then(s=>{s.check?x.push({name:"home"}):(o.value="",r.value=!0,i.value=!1)}).catch(s=>{console.log(s),i.value=!1})}return(s,l)=>(h(),N(L,null,[i.value?(h(),w(S,{key:0})):y("",!0),e("div",D,[z,e("h5",{class:"my-5 text-start",textContent:$(p.InputEmail+"にメール送信しました。30分以内にメール記載の6桁の認証コードを入力してください。")},null,8,J),K,X,b(e("input",{type:"number",id:"InputToken",class:"form-control border-success border-3","onUpdate:modelValue":l[0]||(l[0]=m=>o.value=m),placeholder:"認証コードを入力してください"},null,512),[[I,o.value]]),b(e("div",H,"認証コードが不正です",512),[[P,r.value]])]),e("div",G,[e("button",{type:"button",class:"btn btn-primary",onClick:l[1]||(l[1]=m=>a())},"確認する")])],64))}},C=p=>(B("data-v-d1f84a10"),p=p(),R(),p),Q={key:1},Y={class:"mb-3 d-flex"},ee={class:"w-100 me-3"},te=C(()=>e("label",{for:"FirstName",class:"form-label m-0"},"姓",-1)),se={class:"w-100 ms-3"},oe=C(()=>e("label",{for:"LastName",class:"form-label m-0"},"名",-1)),ne={class:"mb-3"},ae=C(()=>e("label",{for:"Name",class:"form-label m-0"},"ニックネーム",-1)),le=C(()=>e("div",{class:"form-text text-secondary m-0"},"半角英字、数字、ピリオドを使用できます。",-1)),re={class:"mb-3"},ie=C(()=>e("label",{for:"Email",class:"form-label m-0"},"メールアドレス",-1)),ue={class:"form-text text-danger text-start"},de={class:"mb-3"},ce=C(()=>e("label",{for:"Password",class:"form-label m-0"},"パスワード",-1)),me=["textContent"],ve={class:"text-center mt-3 mx-2"},pe={class:"row"},he=C(()=>e("div",{class:"col text-start"},[e("a",{href:"login",class:"text-decoration-none"},"代わりにログイン")],-1)),fe={class:"col text-end"},_e=["disabled"],be={__name:"RegisterComponent",setup(p){const x=t(),u=t(Math.floor(Math.random()*888889)+111111),o=t(),r=t(),i=t(),a=t(),s=t(),l=t(!1),m=t(!0),k=t(!0),n=t(!1),v=t(!1);E(()=>{document.title="新規登録",g(),document.addEventListener("keypress",f=>{f.key==="Enter"&&!m.value&&c()})}),j(()=>{o.value&&r.value&&i.value&&a.value&&s.value?a.value.includes("@")&&(m.value=!1):m.value=!0});function g(){let f=document.getElementById("Password");l.value?(f.type="text",x.value="パスワードを非表示",l.value=!1):(f.type="password",x.value="パスワードを表示",l.value=!0)}function c(){n.value=!0,fetch("",{method:"POST",headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content,"Content-Type":"application/json"},body:JSON.stringify({subject:"テストメール",email:a.value,onetime_token:u.value})}).then(f=>f.json()).then(f=>{n.value=!1,f.emailCheck?k.value=!1:v.value=!0}).catch(f=>{console.log(f),n.value=!1})}return(f,d)=>(h(),N(L,null,[n.value?(h(),w(S,{key:0})):y("",!0),k.value?(h(),N("form",Q,[e("div",Y,[e("div",ee,[te,b(e("input",{type:"text",id:"FirstName",class:"form-control text-bg-dark border-secondary","onUpdate:modelValue":d[0]||(d[0]=_=>o.value=_)},null,512),[[I,o.value]])]),e("div",se,[oe,b(e("input",{type:"text",id:"LastName",class:"form-control text-bg-dark border-secondary","onUpdate:modelValue":d[1]||(d[1]=_=>r.value=_)},null,512),[[I,r.value]])])]),e("div",ne,[ae,b(e("input",{type:"text",id:"Name",class:"form-control text-bg-dark border-secondary","onUpdate:modelValue":d[2]||(d[2]=_=>i.value=_)},null,512),[[I,i.value]]),le]),e("div",re,[ie,b(e("input",{type:"email",id:"Email",class:"form-control text-bg-dark border-secondary","onUpdate:modelValue":d[3]||(d[3]=_=>a.value=_)},null,512),[[I,a.value]]),b(e("div",ue,"既に存在しているメールアドレスです",512),[[P,v.value]])]),e("div",de,[ce,b(e("input",{type:"password",id:"Password",class:"form-control text-bg-dark border-secondary","onUpdate:modelValue":d[4]||(d[4]=_=>s.value=_)},null,512),[[I,s.value]]),e("button",{type:"button",id:"clickItem",class:"form-text text-secondary btn p-1 border-0",onClick:d[5]||(d[5]=_=>g()),textContent:$(x.value)},null,8,me)]),e("div",ve,[e("div",pe,[he,e("div",fe,[e("button",{type:"button",class:"btn btn-primary px-5",onClick:d[6]||(d[6]=_=>c()),disabled:m.value},"次へ",8,_e)])])])])):y("",!0),k.value?y("",!0):(h(),w(W,{key:2,InputFirstName:o.value,InputLastName:r.value,InputNickName:i.value,InputEmail:a.value,InputPassword:s.value,Token:u.value},null,8,["InputFirstName","InputLastName","InputNickName","InputEmail","InputPassword","Token"]))],64))}},ge=F(be,[["__scopeId","data-v-d1f84a10"]]),V=p=>(B("data-v-100db641"),p=p(),R(),p),xe={class:"mb-3"},ye=V(()=>e("label",{for:"Email",class:"form-label m-0"},"メールアドレス",-1)),ke={class:"mb-3"},Ie=V(()=>e("label",{for:"Password",class:"form-label m-0"},"パスワード",-1)),we={class:"form-text text-danger text-start"},Ce=["textContent"],Ne={class:"mt-3"},$e={class:"text-center"},Ee=["disabled"],Le=V(()=>e("div",{class:"d-flex align-items-center justify-content-center mt-1"},[e("button",{type:"button",class:"btn text-primary text-truncate border-0 p-0"},"登録情報を忘れた方"),e("div",{class:"vr mx-2 my-1"}),e("a",{href:"/tps-site/auth/register",class:"btn text-light text-truncate border-0 p-0"},"新規登録")],-1)),Se={__name:"LoginComponent",setup(p){const x=T(),u=t(),o=t(),r=t(),i=t(!1),a=t(!1),s=t(!1),l=t(!0);E(()=>{document.title="ログイン",m(),document.addEventListener("keypress",n=>{n.key==="Enter"&&!l.value&&k()})}),j(()=>{o.value&&r.value?o.value.includes("@")&&(l.value=!1):l.value=!0});function m(){let n=document.getElementById("Password");i.value?(n.type="text",u.value="パスワードを非表示",i.value=!1):(n.type="password",u.value="パスワードを表示",i.value=!0)}function k(){a.value=!0,fetch("",{method:"POST",headers:{"X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content,"Content-Type":"application/json"},body:JSON.stringify({email:o.value,password:r.value})}).then(n=>n.json()).then(n=>{document.querySelector('meta[name="csrf-token"]').content=n.csrf,n.check?x.push({name:"home"}):s.value=!0,a.value=!1}).catch(n=>{console.log(n),a.value=!1,s.value=!0})}return(n,v)=>(h(),N(L,null,[a.value?(h(),w(S,{key:0})):y("",!0),e("div",xe,[ye,b(e("input",{type:"email",id:"Email",class:"form-control text-bg-dark border-secondary","onUpdate:modelValue":v[0]||(v[0]=g=>o.value=g)},null,512),[[I,o.value]])]),e("div",ke,[Ie,b(e("input",{type:"password",id:"Password",class:"form-control text-bg-dark border-secondary","onUpdate:modelValue":v[1]||(v[1]=g=>r.value=g)},null,512),[[I,r.value]]),b(e("div",we,"メールアドレスまたはパスワードが不明です",512),[[P,s.value]]),e("button",{type:"button",id:"clickItem",class:"form-text text-secondary btn p-1 border-0",onClick:v[2]||(v[2]=g=>m()),textContent:$(u.value)},null,8,Ce)]),e("div",Ne,[e("div",$e,[e("button",{type:"button",class:"btn btn-primary rounded-pill w-100",onClick:v[3]||(v[3]=g=>k()),disabled:l.value},"ログイン",8,Ee),Le])])],64))}},Te=F(Se,[["__scopeId","data-v-100db641"]]),Pe={key:1,class:"bg-dark d-flex align-items-center justify-content-center",style:{width:"100%",height:"100vh"}},Fe={class:"card-header border-0"},Ve={class:"d-flex align-items-center justify-content-center"},je={class:"text-center"},Be=["textContent"],Re={class:"card-body overflow-auto"},Ae={__name:"Auth",setup(p){const x=T(),u=t(!1),o=t(""),r=t(),i=t(),a=t(),s=t(!0),l=t([]),m=t();E(()=>{k(),n(),g(),v(),window.addEventListener("resize",v)});const k=()=>{fetch("/tps-site/auth/login/check").then(c=>c.json()).then(c=>{c.ResponseData?x.push({name:"home"}):(s.value=!1,u.value=!0)}).catch(c=>{console.log(c)})},n=()=>{fetch("/tps-site/get/env").then(c=>c.json()).then(c=>{o.value=c.ResponseData}).catch(c=>{console.log(c)})},v=()=>{window.screen.width<=768?(r.value="100%",i.value="100vh",a.value=!1):(r.value="35%",i.value="60%",a.value=!0)},g=()=>{location.pathname==="/tps-site/auth/register"?(l.value[0]=!0,m.value="アカウントの作成"):location.pathname==="/tps-site/auth/login"&&(l.value[1]=!0,m.value="ログイン")};return(c,f)=>{const d=A("router-link");return h(),N(L,null,[s.value?(h(),w(S,{key:0})):y("",!0),u.value?(h(),N("div",Pe,[e("div",{class:U(["card text-bg-dark colorBackGradient",{"border-light":a.value}]),style:Z({width:r.value,height:i.value})},[e("div",Fe,[e("div",Ve,[e("div",je,[O(d,{to:"/",class:"fw-bold fs-1 text-light text-decoration-none"},{default:M(()=>[q($(o.value),1)]),_:1}),e("h4",{textContent:$(o.value+m.value)},null,8,Be)])])]),e("div",Re,[l.value[0]?(h(),w(ge,{key:0})):y("",!0),l.value[1]?(h(),w(Te,{key:1})):y("",!0)])],6)])):y("",!0)],64)}}},Ue=F(Ae,[["__scopeId","data-v-81e136bf"]]);export{Ue as default};
