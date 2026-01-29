<template>
    <AppLayout :books="books" @edit-book="editBook" @delete-book="deleteBook" @update-stars="updateStars">
        <template #default="{ books, editBook, deleteBook, updateStars }">
            <div class="booksContainer">
                <BookCard v-for="book in books" :key="book.id" :book="book" @edit="editBook(book)"
                    @delete="deleteBook(book.id)" @update:stars="updateStars(book.id, $event)" />
            </div>
        </template>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../layouts/AppLayout.vue'
import BookCard from '../components/BookCard.vue'

const props = defineProps({
    books: Array
})

const emit = defineEmits(['edit-book', 'delete-book', 'update-stars'])

function editBook(book) {
    emit('edit-book', book)
}

function deleteBook(id) {
    emit('delete-book', id)
}

function updateStars(bookId, stars) {
    emit('update-stars', bookId, stars)
}
</script>

<style scoped>
.booksContainer {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 40px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}
</style>