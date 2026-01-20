<template>
    <div class="s-timer-app">
        <div class="s-timer">
            <TimerHeader title="Будем в эфире через" />

            <div class="s-timer-display">
                <TimerSegment :value="daysValue" :number="days" type="days" :is-animating="isAnimatingDays" />
                <TimerSegment :value="hoursValue" :number="hours" type="hours" :is-animating="isAnimatingHours" />
                <TimerSegment :value="minutesValue" :number="minutes" type="minutes"
                    :is-animating="isAnimatingMinutes" />
                <TimerSegment :value="secondsValue" :number="seconds" type="seconds"
                    :is-animating="isAnimatingSeconds" />
            </div>

            <div class="s-timer-controls">
                <SButton @click="resetTimer">Сбросить таймер</SButton>
            </div>

            <div v-if="isStreamLive" class="s-timer-live">
                <div class="s-timer-live-indicator"></div>
                <span>В ЭФИРЕ</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import TimerHeader from '@/components/TimerHeader.vue'
import TimerSegment from '@/components/TimerSegment.vue'
import { SButton } from 'startup-ui';

// Константы
const DEFAULT_STREAM_TIME = 24 * 60 * 60 * 1000 // 24 часа по умолчанию

// Состояние таймера
const isStreamLive = ref(false)
const remainingTime = ref(DEFAULT_STREAM_TIME)

// Состояния анимации для каждой единицы времени
const isAnimatingDays = ref(false)
const isAnimatingHours = ref(false)
const isAnimatingMinutes = ref(false)
const isAnimatingSeconds = ref(false)

// Предыдущие значения для сравнения
const previousDays = ref(0)
const previousHours = ref(0)
const previousMinutes = ref(0)
const previousSeconds = ref(0)

// Числовые значения для склонения
const daysValue = computed(() => Math.floor(remainingTime.value / (1000 * 60 * 60 * 24)))
const hoursValue = computed(() => Math.floor((remainingTime.value % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)))
const minutesValue = computed(() => Math.floor((remainingTime.value % (1000 * 60 * 60)) / (1000 * 60)))
const secondsValue = computed(() => Math.floor((remainingTime.value % (1000 * 60)) / 1000))

// Форматирование времени для отображения
const days = computed(() => {
    const value = daysValue.value
    if (value !== previousDays.value) {
        isAnimatingDays.value = true
        setTimeout(() => isAnimatingDays.value = false, 300)
        previousDays.value = value
    }
    return String(value).padStart(2, '0')
})

const hours = computed(() => {
    const value = hoursValue.value
    if (value !== previousHours.value) {
        isAnimatingHours.value = true
        setTimeout(() => isAnimatingHours.value = false, 300)
        previousHours.value = value
    }
    return String(value).padStart(2, '0')
})

const minutes = computed(() => {
    const value = minutesValue.value
    if (value !== previousMinutes.value) {
        isAnimatingMinutes.value = true
        setTimeout(() => isAnimatingMinutes.value = false, 300)
        previousMinutes.value = value
    }
    return String(value).padStart(2, '0')
})

const seconds = computed(() => {
    const value = secondsValue.value
    if (value !== previousSeconds.value) {
        isAnimatingSeconds.value = true
        setTimeout(() => isAnimatingSeconds.value = false, 300)
        previousSeconds.value = value
    }
    return String(value).padStart(2, '0')
})

// Таймер
let timerInterval = null

const startTimer = () => {
    // Проверяем, не наступило ли время стрима
    if (remainingTime.value <= 0) {
        isStreamLive.value = true
        return
    }

    isStreamLive.value = false

    if (timerInterval) {
        clearInterval(timerInterval)
    }

    timerInterval = setInterval(() => {
        remainingTime.value = Math.max(0, remainingTime.value - 1000)

        if (remainingTime.value === 0) {
            isStreamLive.value = true
            clearInterval(timerInterval)
        }
    }, 1000)
}

const resetTimer = () => {
    isStreamLive.value = false
    remainingTime.value = DEFAULT_STREAM_TIME

    if (timerInterval) {
        clearInterval(timerInterval)
        timerInterval = null
    }

    startTimer()
}

// Инициализация
onMounted(() => {
    startTimer()
})

onUnmounted(() => {
    if (timerInterval) {
        clearInterval(timerInterval)
    }
})
</script>

<style lang="scss">
.s-timer-app {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: var(--s-bg);
    font-family: var(--s-font-family);
}

.s-timer {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: var(--s-base-padding);
    border: 1px solid var(--s-border);
    border-radius: var(--s-border-radius);
    box-shadow: var(--s-shadow);
    background-color: var(--s-white);
    min-width: 600px;
    max-width: var(--s-base-width);
    margin: 0 auto;

    &-display {
        display: flex;
        justify-content: center;
        gap: var(--s-base-margin);
        margin-bottom: calc(var(--s-base-margin) * 1.5);
    }

    &-controls {
        display: flex;
        gap: calc(var(--s-base-margin) / 2);
        margin-bottom: var(--s-base-margin);
        justify-content: center;
    }
}

@keyframes pulse {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: 0.8;
    }
}

@keyframes blink {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: 0.5;
    }
}
</style>