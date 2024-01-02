import { ref } from 'vue'

export default function() {
    const getLoginFlag = ref()
    
    fetch('/tps-site/api/auth/login/check')
    .then((response) => response.json())
    .then(res => {
        getLoginFlag.value = res.ResponseData
    })
    .catch(error => {
        console.log(error)
    })

    return {
        getLoginFlag,
    }
}