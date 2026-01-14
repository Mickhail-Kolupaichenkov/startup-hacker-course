<template>
<div class="app">
    <Dialog v-if="showBookForm" :title="currentBook?.id ? 'Редактировать книгу' : 'Добавить книгу'" @close="hideForm()">
        <BookForm v-if="showBookForm" :modelValue="currentBook"
        @update:modelValue="saveBook" @cancel="hideForm" />
    </Dialog>
    <div class="book-actions-list-wrapper">
        <div class="book-actions-list-info">
            <span>Кол-во книг: {{ totalBooks }}</span>
            <span> / </span>
            <span>Средний рейтинг: {{ averageRating }}</span>
        </div>
        <div class="book-actions-list-actions">
            <button @click="removeRatings">Сбросить рейтинг</button>
            <button :class="{ 'button-primary': !showBookForm, 'button-disabled': showBookForm }"
                @click="showForm" :disabled="showBookForm" >
                Добавить книгу
            </button>
        </div>
    </div>
    <div class="book-list">
        <BookCard v-for="book in books" :key="book.id" :book="book"
            v-bind:update:rating="updateRating" @edit="editBook" @remove="removeBook" />
    </div>
</div>
</template>

<script setup>
import BookCard from "@/BookCard.vue";
import { items } from "./books.json";
import { computed, ref, defineAsyncComponent } from "vue";

const Dialog = defineAsyncComponent(() => import("@/Dialog.vue"));
const BookForm = defineAsyncComponent(() => import("@/BookForm.vue"));

const books = ref(items);
const currentBook = ref();
function updateRating(id, rating) {
    books.value = books.value.map((book) => {
    if (book.id === id) {
        book.rating = rating;
    }
    return book;
    });
}
function removeBook(id) {
    books.value.filter((book) => book.id !== id);
}
function editBook(id) {
    currentBook.value = books.value.find((book) => book.id === id);
    showForm();
}
function saveBook(data) {
    const isNew = !books.value.find((book) => book.id === data.id);
    if (isNew) {
        addBook(data);
    } else {
        updateBook(data);
    }
}

function updateBook(data) {
    books.value = books.value.map((m) => {
    if (m.id === data.id) {
        data.rating = m.rating;
        return data;
    }
    return data;
    });
    hideForm();
}
function addBook(data) {
    books.value.push(data);
    hideForm();
}

const showBookForm = ref(false);
function hideForm() {
    showBookForm.value = false;
    currentBook.value = null;
}
function showForm() {
    showBookForm.value = true;
}
const averageRating = computed(() => {
    const avg = books.value.map((book) => parseInt(book.rating || 0)).reduce((a, b) => a + b, 0);
    return Number(avg / books.value.length).toFixed(1);
});
const totalBooks = computed(() => {
    return books.value.length;
});
function removeRatings() {
    books.value = books.value.map((book) => {
        book.rating = null;
        return book;
    });
}
</script>
<style lang="scss">
.book {
    &-list {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    &-actions {
        &-list {
            &-wrapper {
                margin-bottom: 20px;
            }
            &-info {
                margin-bottom: 20px;
            }
            &-actions {
                display: flex;
                gap: 20px;
            }
        }
    }
}
</style>
