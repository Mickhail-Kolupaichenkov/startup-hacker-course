<template>
    <AppLayout>
        <div v-if="$page.url === '/'" class="stat-block">
            <SStat title="Всего книг:">{{ stats.totalBooks }}</SStat>
            <SStat title="Средний рейтинг:">{{ stats.averageRating.toFixed(1) }}</SStat>
            <SStat title="Мои оценки:">{{ stats.myRatings }}</SStat>
        </div>
        <div class="booksContainer">
            <div v-if="books.length === 0" class="empty-state">
                <div class="empty-icon">📚</div>
                <h3>Книг пока нет</h3>
                <p>Будьте первым, кто добавит книгу в коллекцию!</p>
                <SButton v-if="canCreate" @click="showCreateForm">
                    Добавить книгу
                </SButton>
            </div>

            <BookCard v-for="book in books" :key="book.id" :book="book" :current-user="auth?.user"
                @edit="editBook(book)" @delete="deleteBook(book.id)" @update:stars="rateBook(book.id, $event)" />
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../layouts/AppLayout.vue'
import BookCard from '../components/BookCard.vue'
import { SButton, SStat } from 'startup-ui'
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    books: Array,
    canCreate: Boolean,
    auth: Object
})

function editBook(book) {
    router.get(`/books/${book.id}/edit`)
}

function deleteBook(id) {
    if (confirm('Вы уверены, что хотите удалить книгу?')) {
        router.delete(`/books/${id}`)
    }
}

function rateBook(bookId, stars) {
    router.post(`/books/${bookId}/rate`, { rating: stars })
}

function showCreateForm() {
    router.get('/books/create')
}

const stats = computed(() => {
    const totalBooks = props.books.length

    // Считаем средний рейтинг
    const booksWithRating = props.books.filter(book => book.stars > 0)
    const averageRating = booksWithRating.length > 0
        ? booksWithRating.reduce((sum, book) => sum + book.stars, 0) / booksWithRating.length
        : 0

    // Считаем мои оценки
    const myRatings = props.books.filter(book => book.user_rating).length

    return {
        totalBooks,
        averageRating,
        myRatings
    }
})
</script>

<style scoped>
.stat-block {
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin: 20px auto;
    max-width: 260px;
    padding: 30px 30px 10px 30px;
    background-color: #FFFFFF;
    border: 1px solid #E1E8ED;
    color: #F04F0A;
}

.booksContainer {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 40px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 8px;
    border: 2px dashed #e1e8ed;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 20px;
    color: #333;
    margin-bottom: 10px;
}

.empty-state p {
    color: #666;
    margin-bottom: 20px;
}
</style>