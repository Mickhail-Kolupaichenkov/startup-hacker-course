<template>
<div class="book-card">
    <div class="book-card-img-wrapper">
        <div class="book-card-star">
            <FontAwesomeIcon id="rating" icon="star" class="book-card-star-icon"
            :class="{ 'yellow': !notRated, 'gray': notRated }" />
            <div class="book-card-star-wrapper">
                <span v-if="!notRated" id="rating-stars">{{ book.rating }}</span>
                <span v-else class="book-card-star-content-notrated">-</span>
            </div>
        </div>
        <img v-if="book?.image" :src="book.image" class="book-card-img" />
        <span v-else class="book-card-noimg">
            <span>Нет обложки</span>
        </span>
    </div>

    <div class="book-card-content">
        <div class="book-card-title-wrapper">
            <h3 class="book-card-title">{{ book.name }}</h3>
            <div class="book-card-genres">
                <span v-for="genre in book.genres" :key="`${book.id}-${genre}`" class="book-card-genres-tag">{{ genre }}</span>
            </div>
        </div>
        <p class="book-card-description">{{ book.description }}</p>
        <div class="book-card-rating">
            <span class="book-card-rating-text">Рейтинг: ({{ book.rating }}/5)</span>
            <div class="book-card-rating-stars">
                <FontAwesomeIcon v-for="star in 5" :key="star" icon="star"
                    :class="[ star <= book.rating ? 'yellow' : 'gray']" :disabled="star === book.rating"
                    @click="updateRating(star)"/>
            </div>
        </div>
        <div class="book-card-actions-list-wrapper">
            <button class="book-card-action-edit-button" @click="editbook()">
                <FontAwesomeIcon icon="pencil" />
            </button>
            <button class="book-car d-action-remove-button" @click="removebook()">
                <FontAwesomeIcon icon="trash" />
            </button>
        </div>
    </div>
</div>
</template>
<script setup>
import { computed } from "vue";
const props = defineProps({
    book: { type: Object, default: null },
    index: { type: Number, default: null },
});

const emit = defineEmits(["edit", "remove", "update:rating"]);

const notRated = computed(() => {
    return Boolean(!props.book?.rating);
});

function updateRating(rating) {
    emit("update:rating", props.book.id, rating);
}

function removebook() {
    emit("remove", props.book.id);
}

function editbook() {
    emit("edit", props.book.id);
}
</script>
<style lang="scss">
.book-card {
    width: 400px;
    padding: 20px;
    border: 1px solid rgb(87, 84, 84);
    border-radius: 6px;
    
    &-img-wrapper {
        position: relative;
    }

    &-img {
        display: flex;
        justify-content: center;
        align-items: center;
        max-width: 100%;
        min-height: 200px;
        border-radius: 6px;
    }

    &-noimg {
        display: flex;
        justify-content: center;
        align-items: center;
        max-width: 100%;
        min-height: 200px;
    }

    &-star {
        position: absolute;
        top: 10px;
        left: 10px;
        height: 31px;
        width: 31px;
        span {
            position: absolute;
            top: 50%;
            left: 50%;
            z-index: 1000;
            transform: translate(-35%, -50%);
        }

        &-icon {
            position: absolute;
            font-size: 27px;
            z-index: 10;
            inset: 0;
        }
    }

    &-genres {
        display: flex;
        gap: 5px;

        &-tag {
            padding: 2px 4px;
            border: 1px solid rgb(26, 42, 70);
            border-radius: 3px;
            background-color: rgba(37, 37, 237, 0.352);
        }
    }

    &-rating {
        &-stars {
            margin-bottom: 20px;
            svg {
                cursor: pointer;
            }
        }
    }
}

.yellow {
    color: yellow;
}

.gray {
    color: gray;
}
</style>