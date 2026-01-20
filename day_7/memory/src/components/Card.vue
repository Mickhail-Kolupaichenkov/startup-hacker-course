<template>
    <div class="s-card" :class="{
        's-card_flipped': isFlipped,
        's-card_matched': isMatched
    }" @click="$emit('click')">
        <div class="s-card-inner">
            <div class="s-card-front">
                <span class="s-card-icon">?</span>
            </div>
            <div class="s-card-back">
                {{ card.value }}
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    card: {
        type: Object,
        required: true
    },
    isFlipped: {
        type: Boolean,
        default: false
    },
    isMatched: {
        type: Boolean,
        default: false
    }
})

defineEmits(['click'])
</script>

<style scoped lang="scss">
.s-card {
    aspect-ratio: 4/4;
    cursor: pointer;
    perspective: 1000px;
    transition: transform 0.3s ease;

    &:hover:not(.s-card_matched) {
        transform: translateY(-4px);
    }

    &_flipped {
        cursor: default;

        &:hover {
            transform: none;
        }
    }

    &_matched {
        cursor: default;
        opacity: 0.6;

        &:hover {
            transform: none;
        }

        .s-card-back {
            background: linear-gradient(135deg, var(--s-green-light), var(--s-green));
            color: var(--s-white);
            border-color: var(--s-green-dark);
        }
    }

    &-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transition: transform 0.6s;
        transform-style: preserve-3d;
        border-radius: var(--s-border-radius);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    &_flipped &-inner,
    &_matched &-inner {
        transform: rotateY(180deg);
    }

    &_matched &-inner {
        animation: matchSuccess 0.8s ease;
    }

    &-front,
    &-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        border-radius: calc(var(--s-border-radius) - 2px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        overflow: hidden;
    }

    &-front {
        background: linear-gradient(135deg, var(--s-primary), var(--s-primary-dark));
        color: var(--s-white);
        border: 2px solid var(--s-white);

        .s-card-icon {
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
    }

    &-back {
        background: var(--s-white);
        color: var(--s-text);
        transform: rotateY(180deg);
        font-size: 2rem;
        border: 2px solid var(--s-border);
    }
}

@keyframes matchSuccess {
    0% {
        transform: rotateY(180deg) scale(1);
    }

    50% {
        transform: rotateY(180deg) scale(1.1);
        box-shadow: 0 0 20px rgba(83, 183, 83, 0.5);
    }

    100% {
        transform: rotateY(180deg) scale(1);
    }
}

@media (max-width: 768px) {
    .s-card {
        &-front .s-card-icon {
            font-size: 1.8rem;
        }

        &-back {
            font-size: 1.5rem;
        }
    }
}

@media (max-width: 480px) {
    .s-card {
        &-front .s-card-icon {
            font-size: 1.5rem;
        }

        &-back {
            font-size: 1.2rem;
        }
    }
}
</style>