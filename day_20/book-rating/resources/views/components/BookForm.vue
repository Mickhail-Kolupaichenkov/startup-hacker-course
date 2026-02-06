<template>
    <form @submit.prevent="handleSubmitForm" class="book-form" enctype="multipart/form-data">
        <div class="form-group">
            <label>Название книги:</label>
            <input v-model="formData.title" placeholder="Введите название книги" class="form-input"
                :class="{ 'error': errors.title }" />
            <div v-if="errors.title" class="error-message">{{ errors.title }}</div>
        </div>

        <div class="form-group">
            <label>Описание:</label>
            <textarea v-model="formData.description" placeholder="Описание книги" class="form-textarea"></textarea>
            <div v-if="errors.description" class="error-message">{{ errors.description }}</div>
        </div>

        <div class="form-group">
            <label>Обложка:</label>

            <div v-if="existingCover && !formData.removeCover" class="cover-preview">
                <img :src="existingCover" alt="Current cover" class="preview-image" />
                <SButton type="button" size="small" @click="removeExistingCover">Удалить обложку</SButton>
            </div>

            <div v-else>
                <input type="file" @change="handleCoverUpload" accept="image/*" class="form-input" />
                <div v-if="errors.cover" class="error-message">{{ errors.cover }}</div>
                <div v-if="previewUrl" class="cover-preview">
                    <img :src="previewUrl" alt="New cover preview" class="preview-image" />
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Жанры:</label>
            <select v-model="formData.genres" multiple class="form-select" :class="{ 'error': errors.genres }">
                <option v-for="(label, key) in genreOptions" :key="key" :value="key">{{ label }}</option>
            </select>
            <div v-if="errors.genres" class="error-message">{{ errors.genres }}</div>
            <div v-if="errors['genres.0']" class="error-message">{{ errors['genres.0'] }}</div>
            <div class="selected-genres" v-if="formData.genres.length > 0">
                Выбрано: {{formData.genres.map(g => genreOptions[g] || g).join(', ')}}
            </div>
        </div>

        <div class="form-group checkbox-group">
            <label>
                <input type="checkbox" v-model="formData.isAdult" />
                18+
            </label>
            <div v-if="errors.isAdult" class="error-message">{{ errors.isAdult }}</div>
        </div>

        <div class="form-buttons">
            <SButton @click="handleSubmitForm" :disabled="processing">
                {{ submitText }}
            </SButton>
            <SButton type="button" outlined @click="handleCancel" :disabled="processing">
                Отменить
            </SButton>
        </div>
    </form>
</template>

<script setup>
import { SButton } from 'startup-ui'
import { ref, watch } from 'vue'

const props = defineProps({
    initialData: {
        type: Object,
        default: () => ({
            title: '',
            description: '',
            genres: [],
            isAdult: false,
            cover: null,
            existingCover: null,
            removeCover: false
        })
    },
    errors: {
        type: Object,
        default: () => ({})
    },
    submitText: {
        type: String,
        default: 'Сохранить'
    },
    processing: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['submit', 'cancel'])

const formData = ref({ ...props.initialData })
const previewUrl = ref(null)
const existingCover = ref(props.initialData.existingCover)

const genreOptions = {
    'Роман': 'Роман',
    'Фантастика': 'Фантастика',
    'Детектив': 'Детектив',
    'Бизнес-книга': 'Бизнес-книга',
    'Техническая-литература': 'Техническая литература'
}

function handleCoverUpload(event) {
    const file = event.target.files[0]
    if (file) {
        formData.value.cover = file
        formData.value.removeCover = false
        existingCover.value = null

        const reader = new FileReader()
        reader.onload = (e) => {
            previewUrl.value = e.target.result
        }
        reader.readAsDataURL(file)
    }
}

function removeExistingCover() {
    formData.value.removeCover = true
    formData.value.cover = null
    existingCover.value = null
    previewUrl.value = null
}

function handleSubmitForm() {
    // Проверка обязательных полей
    if (!formData.value.title || !formData.value.title.trim()) {
        alert('Пожалуйста, введите название книги')
        return
    }

    if (!formData.value.genres || formData.value.genres.length === 0) {
        alert('Пожалуйста, выберите хотя бы один жанр')
        return
    }

    emit('submit', formData.value)
}

function handleCancel() {
    emit('cancel')
}

watch(() => formData.value.cover, (newCover) => {
    if (!newCover) {
        previewUrl.value = null
    }
})

// Обновляем форму при изменении initialData
watch(() => props.initialData, (newData) => {
    formData.value = { ...newData }
    existingCover.value = newData.existingCover
    previewUrl.value = null
}, { deep: true })
</script>

<style scoped>
.book-form {
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.form-input,
.form-textarea,
.form-select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box;
}

.form-input.error,
.form-select.error {
    border-color: #e0245e;
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
}

.form-select {
    height: 100px;
}

.error-message {
    color: #e0245e;
    font-size: 12px;
    margin-top: 5px;
}

.selected-genres {
    margin-top: 10px;
    font-size: 14px;
    color: #666;
}

.cover-preview {
    margin-top: 10px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
}

.preview-image {
    max-width: 200px;
    max-height: 200px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.checkbox-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    cursor: pointer;
}

.form-buttons {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

input[type="file"] {
    padding: 5px;
}
</style>