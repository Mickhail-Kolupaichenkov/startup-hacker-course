<template>
    <SForm v-model="formData" @submit="$emit('submit')" :errors="errors">
        <SInput v-model="formData.title" placeholder="Введите название книги" :error="errors.title" ref="titleInput" />
        <SInput v-model="formData.description" type="textarea" placeholder="Описание" />
        <SInput v-model="formData.img" placeholder="URL обложки" />

        <label for="genres">Жанры:</label>
        <SSelect v-model="formData.genres" :options="genreOptions" placeholder="Выберите жанры"
            :error="errors.genres" />

        <div class="checkbox">
            <SCheckbox v-model="formData.isAdult">18+</SCheckbox>
        </div>

        <div v-if="errors && (errors.title || errors.genres)" class="errors-block">
            <p v-if="errors.title">{{ errors.title }}</p>
            <p v-if="errors.genres">{{ errors.genres }}</p>
        </div>

        <div class="form-buttons">
            <SButton>{{ submitText }}</SButton>
            <SButton outlined @click="$emit('cancel')">Отменить</SButton>
        </div>
    </SForm>
</template>

<script setup>
import { SForm, SFormRow, SInput, SSelect, SCheckbox, SButton } from 'startup-ui'
import { computed, onMounted, watch } from 'vue'
import { useTemplateRef } from 'vue'
import { debounce } from 'lodash-es'

const props = defineProps({
    modelValue: Object,
    errors: Object,
    submitText: String
})

const emit = defineEmits(['update:modelValue', 'submit', 'cancel'])

const titleInput = useTemplateRef('titleInput')

//Фокус на ref ссылку на input title
onMounted(() => {
    if (titleInput.value?.$el) {
        const inputElement = titleInput.value.$el.querySelector('input')
        if (inputElement) {
            inputElement.focus()
        }
    }
})

const formData = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value)
    }
})

watch(
    () => formData.value.title,
    debounce((newTitle) => {
        if (newTitle && newTitle.trim().length > 0) {
            console.log(`Отправили "${newTitle}" на сервер`)
        }
    }, 500)
)

const genreOptions = {
    'Роман': 'Роман',
    'Фантастика': 'Фантастика',
    'Детектив': 'Детектив',
    'Бизнес-книга': 'Бизнес-книга',
    'Техническая-литература': 'Техническая литература'
}
</script>

<style scoped>
.form-buttons {
    display: flex;
    gap: 10px;
    margin-top: 16px;
}

.checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 8px 0;
}

/*Стили ошибок */
.errors-block {
    background: #fee;
    border: 1px solid #e0245e;
    border-radius: 4px;
    padding: 10px;
    margin: 16px 0;
}

.errors-block p {
    color: #e0245e;
    margin: 4px 0;
    font-size: 12px;
}

.errors-block p:first-child {
    margin-top: 0;
}

.errors-block p:last-child {
    margin-bottom: 0;
}
</style>