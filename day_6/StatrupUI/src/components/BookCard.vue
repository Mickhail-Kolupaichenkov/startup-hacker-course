<template>
    <div class="cardItem" :class="{ 'rated': rated }">
        <div class="cardItem_info">
            <h3>{{ book.title }}</h3>
            <p>{{ book.description }}</p>
            <span style="display: flex; gap: 10px;">Жанр:
                <STag color="primary-lightest">
                    {{ displayGenre }}
                </STag>
                <STag v-if="book.isAdult" color="red">18+</STag>
            </span>
            <div class="rating-stars">
                <span>Рейтинг: </span>
                <span v-for="n in 5" :key="n" class="star" :class="{
                    'active': n <= book.stars,
                    'inactive': n > book.stars
                }" @click="setStars(n)">
                    <FontAwesomeIcon icon="star" />
                </span>
            </div>
        </div>

        <div class="cardItem_img">
            <div class="big-rating-star" :class="{ 'no-rating': book.stars === 0 }">
                <span class="star-value">
                    {{ book.stars > 0 ? book.stars : '—' }}
                </span>
                <FontAwesomeIcon icon="star" class="star-icon" />
            </div>
            <div class="preview-wrapper">
                <SImagePreview :src="book.img" :alt="book.title" />
            </div>
        </div>

        <div class="book-actions">
            <SButton outlined @click="$emit('edit')">Редактировать</SButton>
            <SButton @click="$emit('delete')">Удалить</SButton>
        </div>

    </div>
</template>

<script setup>
import { SButton, SImagePreview, STag } from 'startup-ui';
import { computed } from 'vue'
const props = defineProps({
    book: Object
})

const rated = computed(() => props.book.stars >= 1)

const emit = defineEmits(['edit', 'delete', 'update:stars'])

const setStars = (stars) => {
    emit('update:stars', stars)
}

//Преобразование жанра в строку
const displayGenre = computed(() => {
    const genres = props.book.genres

    if (Array.isArray(genres)) {
        if (genres.length > 0 && genres[0].length === 1) {
            return genres.join('')
        }
        return genres[0] || ''
    }

    return genres || ''
})
</script>

<style>
.cardItem {
    background: white;
    border: 1px solid #e1e8ed;
    border-radius: 4px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
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

.preview-wrapper {
    width: 190px;
    height: 270px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.preview-wrapper .s-imagepreview img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    object-position: center !important;
    border-radius: 4px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: block !important;
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

.rating-stars>span.star svg {
    width: 100%;
    height: 100%;
    display: block;
}

.rating-stars>span.star.active svg {
    color: #FFD700 !important;
}

.rating-stars>span.star.inactive svg {
    color: #e0e0e0 !important;
}

.rating-stars>span.star:hover svg {
    transform: scale(1.3);
    color: #ffcc00 !important;
}

.big-rating-star {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 40px;
    height: 40px;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    background: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
}

.big-rating-star:not(.no-rating) .star-icon {
    color: #FFD700 !important;
    filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.5));
}

.big-rating-star.no-rating .star-icon {
    color: #b0b0b0 !important;
}

.big-rating-star .star-icon {
    position: absolute;
    width: 100%;
    height: 100%;
    font-size: 40px;
}

.big-rating-star .star-value {
    position: relative;
    z-index: 11;
    font-size: 14px;
    font-weight: bold;
    color: white;
    pointer-events: none;
}

.big-rating-star.no-rating .star-value {
    color: #666;
}

.book-actions {
    display: flex;
    gap: 8px;
    padding: 15px 20px;
    border-top: 1px solid #e1e8ed;
    background: #f9f9f9;
}

.cardItem.rated {
    border: 2px solid #FFD700;
    background: linear-gradient(to right, rgba(255, 215, 0, 0.05), white);
}
</style>