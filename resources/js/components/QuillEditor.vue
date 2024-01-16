<script setup>
    import { QuillEditor } from '@vueup/vue-quill'
    import { ref, watch, onMounted } from 'vue'
    import '@vueup/vue-quill/dist/vue-quill.snow.css'
    import '@vueup/vue-quill/dist/vue-quill.bubble.css'

    const props = defineProps({
        content: {
            type: String,
            default: ''
        }
    })
    const emit = defineEmits(['update:modelValue'])
    let content = ref(props.content)
    let editor = ref(null)

    watch(content, (newVal) => {
    emit('update:modelValue', newVal)
    }, { immediate: true });

    const updateContent = (newContent) => {
        var newText = newContent
        content.value = newText
        emit('update:modelValue', newText)

    }

    onMounted(() => {
        editor.value.setHTML(content.value)
    })
</script>

<template>
    <div>
        <QuillEditor ref="editor" class="editor" theme="snow" toolbar="full" v-model="content" contentType="html" @update:content="updateContent" />
    </div>
</template>