<template>
    <div class="s-memory-app">
        <div class="s-memory">
            <div class="s-memory-header">
                <h1 class="s-memory-title">Игра Memory</h1>
            </div>

            <div class="s-memory-stats">
                <div class="s-memory-stat">
                    <span class="s-memory-stat-label">Уровень:</span>
                    <SSelect v-model="difficulty" :options="difficultyOptions" placeholder="Выберите уровень:"
                        @change="startNewGame" />
                </div>

                <div class="s-memory-stat">
                    <span class="s-memory-stat-label">Ходы:</span>
                    <span class="s-memory-stat-value">{{ moves }}</span>
                </div>

                <div class="s-memory-stat">
                    <span class="s-memory-stat-label">Осталось:</span>
                    <span class="s-memory-stat-value">{{ changePairsText }}</span>
                </div>

                <div class="s-memory-stat">
                    <span class="s-memory-stat-label">Время:</span>
                    <span class="s-memory-stat-value">{{ formattedTime }}</span>
                </div>

                <SButton @click="startNewGame">Новая игра</SButton>
            </div>

            <div class="s-memory-game" v-if="!gameCompleted">
                <div class="s-memory-grid" :style="gridStyle">
                    <Card v-for="card in cards" :key="card.id" :card="card"
                        :is-flipped="card.isFlipped || card.isMatched" :is-matched="card.isMatched"
                        @click="flipCard(card)" :style="{ visibility: card.isMatched ? 'hidden' : 'visible' }" />
                </div>
            </div>

            <div v-if="gameCompleted" class="s-memory-completed">
                <div class="s-memory-completed-content">
                    <h2 class="s-memory-completed-title">Поздравляем!</h2>

                    <div class="s-memory-completed-stats">
                        <div class="s-memory-completed-stat">
                            <SStat title="Уровень сложности:">{{ getDifficultyName }}</SStat>
                        </div>
                        <div class="s-memory-completed-stat">
                            <SStat title="Всего ходов:">{{ moves }}</SStat>
                        </div>
                        <div class="s-memory-completed-stat">
                            <SStat title="Затраченное время:">{{ formattedTime }}</SStat>
                        </div>
                        <div class="s-memory-completed-stat">
                            <SStat title="Оценка:">{{ grade }}</SStat>
                        </div>
                    </div>

                    <SButton @click="startNewGame">Играть снова</SButton>
                </div>
            </div>

            <div class="s-memory-instructions">
                <p><strong>Как играть:</strong> Находите пары одинаковых карт. Кликните на карту, чтобы перевернуть её.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Card from '@/components/Card.vue'
import { SButton, SSelect, SStat } from 'startup-ui';

// Состояние игры
const difficulty = ref('12')
const cards = ref([])
const flippedCards = ref([])
const moves = ref(0)
const gameCompleted = ref(false)
const startTime = ref(null)
const timerInterval = ref(null)
const elapsedTime = ref(0)

const difficultyOptions = {
    '12': 'Легкий (12 карт)',
    '24': 'Средний (24 карты)',
    '36': 'Сложный (36 карт)'
}


// Генерация карт
const generateCards = (count) => {
    // Создаем пары чисел
    const values = []
    const pairsCount = count / 2

    for (let i = 1; i <= pairsCount; i++) {
        values.push(i)
    }

    // Дублируем для пар и перемешиваем
    const pairedValues = [...values, ...values]

    for (let i = pairedValues.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1))
            ;[pairedValues[i], pairedValues[j]] = [pairedValues[j], pairedValues[i]]
    }

    // Создаем объекты карт
    return pairedValues.map((value, index) => ({
        id: index,
        value: value,
        isFlipped: false,
        isMatched: false
    }))
}

// Начало новой игры
const startNewGame = () => {
    // Останавливаем старый таймер
    if (timerInterval.value) {
        clearInterval(timerInterval.value)
    }

    // Сбрасываем состояние
    cards.value = generateCards(parseInt(difficulty.value))
    flippedCards.value = []
    moves.value = 0
    gameCompleted.value = false
    elapsedTime.value = 0
    startTime.value = new Date()

    // Запускаем новый таймер
    timerInterval.value = setInterval(() => {
        if (startTime.value) {
            elapsedTime.value = Math.floor((Date.now() - startTime.value.getTime()) / 1000)
        }
    }, 1000)
}

// Переворот карты
const flipCard = (card) => {
    // Проверяем можно ли перевернуть карту
    if (
        card.isFlipped ||
        card.isMatched ||
        flippedCards.value.length === 2 ||
        gameCompleted.value
    ) {
        return
    }

    // Переворачиваем карту
    card.isFlipped = true
    flippedCards.value.push(card)

    // Если перевернуты две карты
    if (flippedCards.value.length === 2) {
        moves.value++

        const [firstCard, secondCard] = flippedCards.value

        if (firstCard.value === secondCard.value) {
            // Карты совпали
            setTimeout(() => {
                firstCard.isMatched = true
                secondCard.isMatched = true
                flippedCards.value = []

                // Проверяем завершение игры
                if (remainingPairs.value === 0) {
                    gameCompleted.value = true
                    if (timerInterval.value) {
                        clearInterval(timerInterval.value)
                    }
                }
            }, 500)
        } else {
            // Карты не совпали
            setTimeout(() => {
                firstCard.isFlipped = false
                secondCard.isFlipped = false
                flippedCards.value = []
            }, 1000)
        }
    }
}

// Вычисляемые свойства
const remainingPairs = computed(() => {
    return cards.value.filter(card => !card.isMatched).length / 2
})

const formattedTime = computed(() => {
    const minutes = Math.floor(elapsedTime.value / 60)
    const seconds = elapsedTime.value % 60
    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
})

const getDifficultyName = computed(() => {
    switch (difficulty.value) {
        case '12': return 'Легкий'
        case '24': return 'Средний'
        case '36': return 'Сложный'
        default: return 'Легкий'
    }
})

// Стили для сетки
const gridStyle = computed(() => {
    let columns = 4
    if (difficulty.value === '24') columns = 6
    if (difficulty.value === '36') columns = 9

    return {
        'grid-template-columns': `repeat(${columns}, 1fr)`
    }
})

// Оценка результата
const grade = computed(() => {
    if (!gameCompleted.value) return ''

    const totalPairs = parseInt(difficulty.value) / 2
    const optimalMoves = totalPairs // Идеальное количество ходов
    const efficiency = optimalMoves / moves.value

    if (efficiency >= 0.8) return 'Отлично!'
    if (efficiency >= 0.6) return 'Хорошо!'
    if (efficiency >= 0.4) return 'Неплохо!'
    return 'Можно лучше!'
})

const gradeClass = computed(() => {
    if (!gameCompleted.value) return ''

    const totalPairs = parseInt(difficulty.value) / 2
    const optimalMoves = totalPairs
    const efficiency = optimalMoves / moves.value

    if (efficiency >= 0.8) return 's-memory-grade-excellent'
    if (efficiency >= 0.6) return 's-memory-grade-good'
    if (efficiency >= 0.4) return 's-memory-grade-average'
    return 's-memory-grade-poor'
})

// Склонение остатка карточек
const changePairsText = computed(() => {
    const pairs = remainingPairs.value;

    if (pairs === 1) return '1 пара';
    if (pairs >= 2 && pairs <= 4) return `${pairs} пары`;
    return `${pairs} пар`;
});

// Инициализация
onMounted(() => {
    startNewGame()
})
</script>

<style scoped lang="scss">
.s-memory-app {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: var(--s-bg);
    font-family: var(--s-font-family);
    padding: var(--s-base-padding);
}

.s-memory {
    width: 100%;
    max-width: var(--s-base-width);
    background-color: var(--s-white);
    border-radius: var(--s-border-radius);
    box-shadow: var(--s-shadow);
    padding: var(--s-base-padding);

    &-header {
        text-align: center;
        margin-bottom: var(--s-base-margin);
    }

    &-title {
        font-size: var(--s-h1-font-size);
        color: var(--s-text);
        margin: 0;
    }


    &-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--s-base-margin);
        background-color: var(--s-gray);
        padding: var(--s-base-padding);
        border-radius: var(--s-border-radius);
        margin-bottom: var(--s-base-margin);
        align-items: center;
    }

    &-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: calc(var(--s-base-margin) / 4);

        &-label {
            font-size: calc(var(--s-font-size) * 0.9);
            color: var(--s-text-light);
            font-weight: 600;
        }

        &-value {
            font-size: var(--s-h3-font-size);
            color: var(--s-primary);
            font-weight: bold;
        }
    }

    &-select {
        width: 100%;
        padding: calc(var(--s-base-padding) / 2);
        border: 1px solid var(--s-border);
        border-radius: var(--s-border-radius);
        background-color: var(--s-white);
        color: var(--s-text);
        font-family: var(--s-font-family);
        font-size: var(--s-font-size);
        cursor: pointer;
        transition: border-color 0.3s ease;

        &:focus {
            outline: none;
            border-color: var(--s-primary);
        }
    }

    &-button {
        padding: calc(var(--s-base-padding) / 2) var(--s-base-padding);
        border: none;
        border-radius: var(--s-border-radius);
        background-color: var(--s-primary);
        color: var(--s-white);
        font-size: var(--s-font-size);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        height: var(--s-field-height);
        grid-column: 1 / -1;
        justify-self: center;
        min-width: 200px;

        &:hover {
            background-color: var(--s-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        &_large {
            padding: var(--s-base-padding) calc(var(--s-base-padding) * 1.5);
            font-size: var(--s-h4-font-size);
        }
    }

    &-game {
        background-color: var(--s-bg);
        border-radius: var(--s-border-radius);
        padding: var(--s-base-padding);
        margin-bottom: var(--s-base-margin);
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    &-grid {
        display: grid;
        gap: calc(var(--s-base-margin) / 2);
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
    }

    &-completed {
        background-color: var(--s-primary-lightest);
        border-radius: var(--s-border-radius);
        padding: calc(var(--s-base-padding) * 2);
        margin-bottom: var(--s-base-margin);
        text-align: center;
        border: 2px solid var(--s-primary-light);

        &-content {
            max-width: 500px;
            margin: 0 auto;
        }

        &-title {
            font-size: var(--s-h2-font-size);
            color: var(--s-primary);
            margin-bottom: var(--s-base-margin);
        }

        &-stats {
            background-color: var(--s-white);
            border-radius: var(--s-border-radius);
            padding: var(--s-base-padding);
            margin-bottom: var(--s-base-margin);
            text-align: left;
        }

        &-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: calc(var(--s-base-padding) / 2) 0;
            border-bottom: 1px solid var(--s-border-light);

            &:last-child {
                border-bottom: none;
            }

            span {
                color: var(--s-text-light);
            }

            strong {
                color: var(--s-text);
                font-size: var(--s-h4-font-size);
            }
        }
    }

    &-grade {
        &-excellent {
            color: var(--s-green) !important;
        }

        &-good {
            color: var(--s-yellow) !important;
        }

        &-average {
            color: var(--s-primary) !important;
        }

        &-poor {
            color: var(--s-red) !important;
        }
    }

    &-instructions {
        background-color: var(--s-yellow-lightest);
        border-radius: var(--s-border-radius);
        padding: var(--s-base-padding);
        border-left: 4px solid var(--s-yellow);
        font-size: calc(var(--s-font-size) * 0.9);
        color: var(--s-text);

        p {
            margin: 0;
        }

        strong {
            color: var(--s-yellow-dark);
        }
    }
}

@media (max-width: 768px) {
    .s-memory {
        &-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        &-grid {
            gap: calc(var(--s-base-margin) / 4);
        }

        &-completed {
            padding: var(--s-base-padding);
        }
    }
}

@media (max-width: 480px) {
    .s-memory {
        &-stats {
            grid-template-columns: 1fr;
        }
    }
}
</style>