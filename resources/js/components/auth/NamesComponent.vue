<script setup>
    const props = defineProps({
        userData: Object,
    })
    const emit = defineEmits([
        'getLoginData'
    ])

    const updateUserData = (e) => {
        let setId = props.userData.id
        let inputFirstNameData = props.userData.first_name
        let inputLastNameData = props.userData.last_name
        let inputNameData = props.userData.name
        let emailAddress = props.userData.email

        if(e.target.id === 'inputFirstName') {
            inputFirstNameData = e.target.value
            runFetch()
        } else if(e.target.id === 'inputLastName') {
            inputLastNameData = e.target.value
            runFetch()
        } else if(e.target.id === 'inputName') {
            inputNameData = e.target.value
            runFetch()
        }

        function runFetch() {
            fetch('/tps-site/auth/update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: setId,
                    first_name: inputFirstNameData,
                    last_name: inputLastNameData,
                    name: inputNameData,
                    email: emailAddress,
                }),
            })
            .then((response) => response.json())
            .then(res => {
                emit('getLoginData')
            })
            .catch(error => {
                console.log(error)
            })
        }
    }
</script>

<template>
    <div class="p-3">
        <div class="form-floating mb-3">
            <input type="text" :value="props.userData.first_name" @change="updateUserData" class="form-control rounded-pill fw-bold" id="inputFirstName" placeholder="" />
            <label for="inputFirstName">姓</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" :value="props.userData.last_name" @change="updateUserData" class="form-control rounded-pill fw-bold" id="inputLastName" placeholder="" />
            <label for="inputLastName">名</label>
        </div>

        <div class="form-floating">
            <input type="text" :value="props.userData.name" @change="updateUserData" class="form-control rounded-pill fw-bold" id="inputName" placeholder="" />
            <label for="inputName">ニックネーム</label>
        </div>
    </div>
</template>