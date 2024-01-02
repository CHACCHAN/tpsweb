import { ref } from 'vue'

export default function() {
    const getAPP_NAME = ref()
    
    fetch('/tps-site/api/get/env')
    .then((response) => response.json())
    .then(res => {
        getAPP_NAME.value = res.ResponseData
    })
    .catch(error => {
        console.log(error)
    })

    return {
        getAPP_NAME,
    }
}