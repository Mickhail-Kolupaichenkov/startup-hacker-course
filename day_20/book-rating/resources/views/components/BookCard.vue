<template>
    <div class="cardItem" :class="{ 'rated': book.user_rating !== null }">
        <div class="cardItem_info">
            <h3>{{ book.title }}</h3>
            <p>{{ book.description }}</p>
            <div class="book-meta">
                <div class="book-genres">
                    <span>Жанры: </span>
                    <STag v-for="genre in book.genres" :key="genre" color="primary-lightest" class="genre-tag">
                        {{ genre }}
                    </STag>
                    <STag v-if="book.isAdult" color="red">18+</STag>
                </div>
                <div class="book-author">
                    Автор: {{ book.user?.name || 'Неизвестно' }}
                </div>
            </div>
            <div class="rating-stars">
                <span>Рейтинг: </span>
                <span v-for="n in 5" :key="n" class="star" :class="{
                    'active': n <= Math.round(book.stars || 0),
                    'inactive': n > Math.round(book.stars || 0)
                }" @click="setStars(n)">
                    ★
                </span>
                <span class="rating-text">
                    {{ book.stars?.toFixed(1) || '0.0' }}
                    ({{ ratingsText }})
                </span>
            </div>
        </div>

        <div class="cardItem_img">
            <div class="big-rating-star" :class="{ 'no-rating': book.stars === 0 }">
                <span class="star-icon">★</span>
                <span class="star-value">
                    {{ book.stars > 0 ? book.stars.toFixed(1) : '—' }}
                </span>
            </div>
            <div class="preview-wrapper">
                <img v-if="book.img" :src="book.img" :alt="book.title" class="book-image" />
                <div v-else class="no-image">📚</div>
            </div>
        </div>

        <div class="book-actions">
            <SButton v-if="canEdit" outlined @click="editBook">
                Редактировать
            </SButton>
            <SButton v-if="canEdit" @click="deleteBook">Удалить</SButton>
        </div>
    </div>
</template>

<script setup>
import { SButton, STag } from 'startup-ui'
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    book: Object,
    currentUser: Object
})

const emit = defineEmits(['update:stars'])

// Функция склонения оценок
function declensionOfRatings(count) {
    if (!count || count === 0) return 'нет оценок'

    const lastDigit = count % 10
    const lastTwoDigits = count % 100

    if (lastTwoDigits >= 11 && lastTwoDigits <= 19) {
        return `${count} оценок`
    }

    if (lastDigit === 1) {
        return `${count} оценка`
    }

    if (lastDigit >= 2 && lastDigit <= 4) {
        return `${count} оценки`
    }

    return `${count} оценок`
}

// Вычисляемое свойство для текста с оценками
const ratingsText = computed(() => {
    return declensionOfRatings(props.book.ratings_count || 0)
})

const canEdit = computed(() => {
    if (!props.currentUser) return false
    const isOwner = props.currentUser.id === props.book.user_id
    const isAdmin = props.currentUser?.role === 'admin'
    return isOwner || isAdmin
})

const setStars = (stars) => {
    if (!props.currentUser) {
        alert('Войдите в систему чтобы оценивать книги')
        return
    }
    emit('update:stars', stars)
}

function editBook() {
    router.get(`/books/${props.book.id}/edit`)
}

async function deleteBook() {
    if (confirm('Вы уверены, что хотите удалить книгу?')) {
        try {
            await router.delete(`/books/${props.book.id}`, {
                preserveScroll: true
            })
        } catch (error) {
            alert('Не удалось удалить книгу')
        }
    }
}
</script>

<style scoped>
.cardItem {
    background: white;
    border: 1px solid #e1e8ed;
    border-radius: 4px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.cardItem.rated {
    border: 2px solid #FFD700;
    background: linear-gradient(to right, rgba(255, 215, 0, 0.05), white);
}

.cardItem_info {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.cardItem_info h3 {
    margin: 0 0 12px 0;
    font-size: 18px;
    font-weight: 700;
    color: #14171a;
    line-height: 1.3;
    overflow: hidden;
    min-height: 46px;
}

.cardItem_info p {
    margin: 0 0 16px 0;
    color: #657786;
    font-size: 14px;
    line-height: 1.5;
    overflow: hidden;
    flex-grow: 1;
}

.book-meta {
    margin-bottom: 16px;
}

.book-genres {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
    margin-bottom: 8px;
}

.book-genres>span:first-child {
    color: #657786;
    font-size: 14px;
}

.genre-tag {
    font-size: 12px;
}

.book-author {
    font-size: 13px;
    color: #657786;
    margin-top: 4px;
}

.cardItem_img {
    position: relative;
    height: 280px;
    background: #f5f8fa;
    border-bottom: 1px solid #e1e8ed;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    overflow: hidden;
}

.big-rating-star {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 50px;
    height: 50px;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
}

.big-rating-star:not(.no-rating) .star-icon {
    color: #FFD700 !important;
    font-size: 50px;
    text-shadow: 0 0 8px rgba(255, 215, 0, 0.6);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
}

.big-rating-star.no-rating .star-icon {
    color: #d0d0d0 !important;
    font-size: 50px;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
}

.big-rating-star .star-value {
    position: relative;
    z-index: 11;
    font-size: 16px;
    font-weight: bold;
    color: white;
    pointer-events: none;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
    margin-top: 1px;
}

.big-rating-star.no-rating .star-value {
    color: #666;
    text-shadow: none;
}

.preview-wrapper {
    width: 190px;
    height: 270px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
    z-index: 1;
}

.book-image {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    object-position: center !important;
    border-radius: 4px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: block !important;
    position: relative;
    z-index: 1;
}

.no-image {
    font-size: 64px;
    color: #ccc;
    position: relative;
    z-index: 1;
}

.rating-stars {
    display: flex;
    align-items: center;
    margin-top: 10px;
    flex-wrap: nowrap;
    overflow: visible;
}

.rating-stars>span:first-child {
    color: #657786;
    font-size: 14px;
    margin-right: 8px;
    flex-shrink: 0;
    white-space: nowrap;
}

.rating-stars>span.star {
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 18px;
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    margin: 0 2px;
}

.rating-stars>span.star.active {
    color: #FFD700 !important;
}

.rating-stars>span.star.inactive {
    color: #e0e0e0 !important;
}

.rating-stars>span.star:hover {
    transform: scale(1.3);
    color: #ffcc00 !important;
}

.rating-text {
    margin-left: 10px;
    font-size: 13px;
    color: #657786;
}

.book-actions {
    display: flex;
    gap: 8px;
    padding: 15px 20px;
    border-top: 1px solid #e1e8ed;
    background: #f9f9f9;
}
</style>