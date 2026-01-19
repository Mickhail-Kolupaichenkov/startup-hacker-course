<template>
    <div class="booksContainer">
        <BookCard v-for="book in books" :key="book.id" :book="book" @edit="$emit('edit-book', book)"
            @delete="$emit('delete-book', book.id)" @update:stars="$emit('update-stars', book.id, $event)" />
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'
import BookCard from '../components/BookCard.vue'

const props = defineProps({
    books: {
        type: Array,
        required: true,
        default: () => []
    }
})

const emit = defineEmits(['edit-book', 'delete-book', 'update-stars'])
</script>