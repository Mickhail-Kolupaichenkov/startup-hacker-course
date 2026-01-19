<template>
    <form @submit.prevent="$emit('submit')">
        <input placeholder="Название" v-model="formData.title" ref="titleInput">

        <textarea placeholder="Описание" v-model="formData.description"></textarea>

        <input placeholder="URL обложки" v-model="formData.img">

        <label for="genres">Жанры:</label>
        <select name="genres" v-model="formData.genres" multiple>
            <option value="Роман">Роман</option>
            <option value="Фантастика">Фантастика</option>
            <option value="Детектив">Детектив</option>
            <option value="Бизнес-книга">Бизнес-книга</option>
            <option value="Техническая-литература">Техническая-литература</option>
        </select>

        <span>Зажмите Ctrl для выбора нескольких жанров</span>

        <div class="checkbox">
            <input type="checkbox" id="adult" v-model="formData.isAdult">
            <label for="adult">18+</label>
        </div>

        <div v-if="errors && (errors.title || errors.genres)" class="errors-block">
            <p v-if="errors.title">{{ errors.title }}</p>
            <p v-if="errors.genres">{{ errors.genres }}</p>
        </div>

        <div class="form-buttons">
            <button type="submit">{{ submitText }}</button>
            <button type="button" @click="$emit('cancel')">Отменить</button>
        </div>
    </form>
</template>

<script setup>
//Встроенные функции
import { computed, onMounted, watch } from 'vue'
import { useTemplateRef } from 'vue'
import { debounce } from 'lodash-es'
//Props
const props = defineProps({
    modelValue: Object,
    errors: Object,
    submitText: String
})
//Emits
const emit = defineEmits(['update:modelValue', 'submit', 'cancel'])

const titleInput = useTemplateRef('titleInput')
//Фокус на ref ссылку на input title
onMounted(() => {
    titleInput.value?.focus()
})

// Двустороннее связывание данных формы
const formData = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value)
    }
})

//Вывод в консоль по прекращению ввода названия
watch(
    () => formData.value.title,
    debounce((newTitle) => {
        if (newTitle && newTitle.trim().length > 0) {
            console.log(`Отправили "${newTitle}" на сервер`)
        }
    }, 500)
)
</script>

<style scoped>
select[multiple] {
    height: 100px;
}

.form input,
.form textarea,
.form select {
    width: 100%;
    margin: 8px 0;
    padding: 8px;
    border: 1px solid #ddd;
    box-sizing: border-box;
}

.form-buttons {
    display: flex;
    gap: 10px;
    margin-top: 16px;
}

.form-buttons button {
    flex: 1;
    padding: 8px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.form-buttons button:first-child {
    background: #1da1f2;
    color: white;
}

.form-buttons button:last-child {
    background: #ddd;
}

.checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 8px 0;
}

.checkbox input[type="checkbox"] {
    width: auto;
    margin: 0;
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