<template>
    <div class="book-form">
        <div class="form-group">
            <label>Название книги:</label>
            <input v-model="formData.title" placeholder="Введите название книги" class="form-input"
                :class="{ 'error': errors.title }" />
            <div v-if="errors.title" class="error-message">{{ errors.title }}</div>
        </div>

        <div class="form-group">
            <label>Описание:</label>
            <textarea v-model="formData.description" placeholder="Описание книги" class="form-textarea"></textarea>
        </div>

        <div class="form-group">
            <label>URL обложки:</label>
            <input v-model="formData.img" placeholder="URL изображения" class="form-input" />
        </div>

        <div class="form-group">
            <label>Жанры:</label>
            <select v-model="formData.genres" multiple class="form-select" :class="{ 'error': errors.genres }">
                <option v-for="(label, key) in genreOptions" :key="key" :value="key">{{ label }}</option>
            </select>
            <div v-if="errors.genres" class="error-message">{{ errors.genres }}</div>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" v-model="formData.isAdult" />
                18+
            </label>
        </div>

        <div class="form-buttons">
            <SButton @click="$emit('submit')">{{ submitText }}</SButton>
            <SButton outlined @click="$emit('cancel')">Отменить</SButton>
        </div>
    </div>
</template>

<script setup>
import { SButton } from 'startup-ui'
import { computed } from 'vue'

const props = defineProps({
    modelValue: Object,
    errors: Object,
    submitText: String
})

const emit = defineEmits(['update:modelValue', 'submit', 'cancel'])

const formData = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value)
    }
})

const genreOptions = {
    'Роман': 'Роман',
    'Фантастика': 'Фантастика',
    'Детектив': 'Детектив',
    'Бизнес-книга': 'Бизнес-книга',
    'Техническая-литература': 'Техническая литература'
}
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

.form-buttons {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}
</style>