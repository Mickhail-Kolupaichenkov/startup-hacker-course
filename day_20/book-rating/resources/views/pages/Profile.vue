<template>
    <AppLayout>
        <div class="profile-page">
            <div class="profile-header">
                <h1>📚 Мои книги</h1>
                <p>Здесь отображаются все книги, которые вы добавили</p>
            </div>

            <div v-if="books.length === 0" class="empty-state">
                <div class="empty-icon">📖</div>
                <h3>У вас пока нет книг</h3>
                <p>Добавьте свою первую книгу в коллекцию!</p>
                <SButton @click="goToCreateBook">Добавить книгу</SButton>
            </div>

            <div v-else>
                <div class="stats-summary">
                    <div class="stat-card">
                        <div class="stat-number">{{ books.length }}</div>
                        <div class="stat-label">Всего книг</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ averageRating.toFixed(1) }}</div>
                        <div class="stat-label">Средний рейтинг</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ totalRatings }}</div>
                        <div class="stat-label">Всего оценок</div>
                    </div>
                </div>

                <div class="books-grid">
                    <BookCard v-for="book in books" :key="book.id" :book="book" :current-user="auth?.user"
                        @edit="editBook(book)" @delete="deleteBook(book.id)"
                        @update:stars="rateBook(book.id, $event)" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../layouts/AppLayout.vue'
import BookCard from '../components/BookCard.vue'
import { SButton } from 'startup-ui'
import { router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    books: Array,
    auth: Object
})

// Вычисляемые свойства для статистики
const averageRating = computed(() => {
    if (props.books.length === 0) return 0
    const booksWithRating = props.books.filter(book => book.stars > 0)
    if (booksWithRating.length === 0) return 0
    const sum = booksWithRating.reduce((total, book) => total + book.stars, 0)
    return sum / booksWithRating.length
})

const totalRatings = computed(() => {
    return props.books.reduce((total, book) => total + (book.ratings_count || 0), 0)
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

function goToCreateBook() {
    router.get('/books/create')
}
</script>

<style scoped>
.profile-page {
    max-width: 1200px;
    margin: 30px auto;
    padding: 0 40px;
}

.profile-header {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e1e8ed;
}

.profile-header h1 {
    font-size: 28px;
    color: #333;
    margin-bottom: 10px;
}

.profile-header p {
    color: #666;
    font-size: 16px;
}

.empty-state {
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

.stats-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #e1e8ed;
}

.stat-number {
    font-size: 32px;
    font-weight: bold;
    color: #F04F0A;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 14px;
    color: #666;
}

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
}
</style>