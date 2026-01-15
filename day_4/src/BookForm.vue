<template>
    <form @submit.prevent="editingBookId ? updateBook() : addBook()">
        <input placeholder="Название" v-model="newBook.title">

        <textarea placeholder="Описание" v-model="newBook.description"></textarea>

        <input placeholder="URL обложки" v-model="newBook.img">

        <label for="genres">Жанры:</label>
        <select name="genres" v-model="newBook.genres" multiple>
            <option value="Роман">Роман</option>
            <option value="Фантастика">Фантастика</option>
            <option value="Детектив">Детектив</option>
            <option value="Бизнес-книга">Бизнес книга</option>
        </select>

        <div class="checkbox">
            <input type="checkbox" id="adult" v-model="newBook.isAdult">
            <label for="adult">18+</label>
        </div>

        <div v-if="hasErrors" class="errors-block">
            <p v-if="errors.title">{{ errors.title }}</p>
            <p v-if="errors.genres">{{ errors.genres }}</p>
        </div>

        <div class="form-buttons">
            <button type="submit">{{ editingBookId ? 'Сохранить' : 'Добавить' }}</button>
            <button type="button" @click="cancelForm">Отменить</button>
        </div>
    </form>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps(['modelValue'])
const emit = defineEmits(['update:modelValue', 'submit', 'cancel'])

const formData = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value)
    }
})
</script>

<style scoped></style>