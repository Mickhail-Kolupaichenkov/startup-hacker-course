<template>
    <div class="s-timesegment">
        <div class="s-timesegment-wrapper">
            <div class="s-timesegment-number" :class="{ 's-timesegment-number_animate': isAnimating }">
                <span class="s-timesegment-digit">
                    {{ number.charAt(0) }}
                </span>
                <span class="s-timesegment-digit">
                    {{ number.charAt(1) }}
                </span>
            </div>
            <span class="s-timesegment-label">
                {{ getLabel(value, type) }}
            </span>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    value: {
        type: Number,
        required: true,
        default: 0
    },
    number: {
        type: String,
        required: true,
        default: '00'
    },
    type: {
        type: String,
        required: true,
        validator: (value) => ['days', 'hours', 'minutes', 'seconds'].includes(value)
    },
    isAnimating: {
        type: Boolean,
        default: false
    }
})

// Получение правильного склонения для единиц времени
const getLabel = (value, type) => {
    const lastDigit = value % 10
    const lastTwoDigits = value % 100

    const rules = {
        days: { '1': 'день', '2-4': 'дня', 'default': 'дней' },
        hours: { '1': 'час', '2-4': 'часа', 'default': 'часов' },
        minutes: { '1': 'минута', '2-4': 'минуты', 'default': 'минут' },
        seconds: { '1': 'секунда', '2-4': 'секунды', 'default': 'секунд' }
    }

    const typeRules = rules[type]

    if (lastTwoDigits >= 11 && lastTwoDigits <= 19) {
        return typeRules.default
    }

    if (lastDigit === 1) {
        return typeRules['1']
    }

    if (lastDigit >= 2 && lastDigit <= 4) {
        return typeRules['2-4']
    }

    return typeRules.default
}
</script>

<style lang="scss">
.s-timesegment {
    &-wrapper {
        display: flex;
        flex-direction: column;
        gap: calc(var(--s-base-padding) / 4);
        align-items: center;
    }

    &-number {
        background-color: var(--s-gray);
        padding: var(--s-base-padding);
        border-radius: var(--s-border-radius);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 2px;
        min-width: 80px;
        position: relative;
        overflow: hidden;

        &_animate {
            animation: flip 0.6s ease-out;
        }
    }

    &-digit {
        font-size: 2.2rem;
        font-weight: bold;
        color: var(--s-text);
        font-family: monospace;
    }

    &-label {
        font-size: calc(var(--s-font-size) * 0.9);
        color: var(--s-text-light);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
}

@keyframes flip {
    0% {
        transform: rotateX(0deg);
    }

    50% {
        transform: rotateX(-90deg);
        background-color: var(--s-primary-light);
    }

    100% {
        transform: rotateX(0deg);
    }
}
</style>