<template>
    <div class="s-rocket-game">
        <div class="s-rocket-game-background"></div>

        <div class="s-rocket-game-left-panel">
            <!-- Меню выбора кораблей -->
            <div class="s-rocket-game-menu">
                <h2 class="s-rocket-game-menu-title">Выберите корабль</h2>
                <div class="s-rocket-game-ships-list">
                    <div v-for="shipType in shipTypes" :key="shipType.id" class="s-rocket-game-ship-card"
                        @click="selectShip(shipType)">
                        <img :src="shipType.image" :alt="shipType.name" class="s-rocket-game-ship-image" />
                        <div class="s-rocket-game-ship-info">
                            <h3 class="s-rocket-game-ship-name">{{ shipType.name }}</h3>
                            <div class="s-rocket-game-ship-details">
                                <STag color="primary">Топливо: {{ shipType.fuelCapacity }} л</STag>
                                <STag color="primary">Скорость: {{ shipType.speed }} км/ч</STag>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Статистика -->
            <div class="s-rocket-game-stats">
                <div class="s-rocket-game-stat">
                    <SStat class="s-rocket-game-stat-label" title="Активных:">{{ activeShips }}</SStat>
                </div>
                <div class="s-rocket-game-stat">
                    <SStat class="s-rocket-game-stat-label" title="Разбитых:">{{ crashedShips }}</SStat>
                </div>
                <div class="s-rocket-game-stat">
                    <SStat class="s-rocket-game-stat-label" title="Всего:">{{ totalLaunched }}</SStat>
                </div>
                <div class="s-rocket-game-stat">
                    <SStat class="s-rocket-game-stat-label" title="В игре:">{{ shipsInGame }}</SStat>
                </div>
            </div>
        </div>

        <!-- Модальное окно запуска -->
        <div v-if="showLaunchModal" class="s-rocket-game-modal-overlay" @click.self="closeModal">
            <div class="s-rocket-game-modal">
                <h2 class="s-rocket-game-modal-title">Запуск {{ selectedShip?.name }}</h2>
                <div class="s-rocket-game-modal-ship-info">
                    <img :src="selectedShip?.image" :alt="selectedShip?.name" class="s-rocket-game-modal-ship-image" />
                    <div class="s-rocket-game-modal-ship-stats">
                        <SStat title="Топливо:">{{ selectedShip?.fuelCapacity }} л</SStat>
                        <SStat title="Скорость:">{{ selectedShip?.speed }} км/ч</SStat>
                        <SStat title="Расход:">{{ (selectedShip?.speed / 1000).toFixed(1) }} л/сек</SStat>
                        <SStat title="Время полета:">~{{ Math.round(selectedShip?.fuelCapacity /
                            (selectedShip?.speed / 1000)) }} сек</SStat>
                    </div>
                </div>

                <div class="s-rocket-game-modal-input">
                    <label for="ship-name" class="s-rocket-game-modal-label">Имя корабля:</label>
                    <SInput type="text" id="ship-name" v-model="shipName" placeholder="Введите имя корабля"
                        @keyup.enter="launchShip" />
                </div>

                <div class="s-rocket-game-modal-buttons">
                    <SButton @click="closeModal" outlined>Отмена</SButton>
                    <SButton @click="launchShip" :disabled="!shipName.trim()">Запустить</SButton>
                </div>
            </div>
        </div>

        <!-- Игровая зона -->
        <div class="s-rocket-game-container">
            <div class="s-rocket-game-area">
                <div class="s-rocket-game-boundary s-rocket-game-boundary_top"></div>
                <div class="s-rocket-game-boundary s-rocket-game-boundary_right"></div>
                <div class="s-rocket-game-boundary s-rocket-game-boundary_bottom"></div>
                <div class="s-rocket-game-boundary s-rocket-game-boundary_left"></div>

                <!-- Игровое поле с кораблями -->
                <div class="s-rocket-game-field">
                    <div v-for="ship in launchedShips" :key="ship.id" class="s-rocket-game-ship-container" :style="{
                        left: (ship.position.x - shipFullWidth / 2) + 'px',
                        top: (ship.position.y - shipFullHeight / 2) + 'px',
                        transform: `rotate(${ship.rotation}deg)`,
                        zIndex: ship.zIndex,
                        opacity: ship.isRemoving ? 0 : 1,
                        transition: ship.isRemoving ? 'opacity 0.5s ease' : 'none'
                    }" @click="refuelShip(ship)">
                        <!-- Корабль -->
                        <img :src="getShipImage(ship)" :alt="ship.name" class="s-rocket-game-flying-ship" :class="{
                            's-rocket-game-flying-ship_low-fuel': ship.fuel <= ship.fuelCapacity * 0.1 && ship.fuel > 0,
                            's-rocket-game-flying-ship_falling': ship.isCrashed
                        }" />

                        <!-- Имя корабля -->
                        <div class="s-rocket-game-ship-name-tag">{{ ship.name }}</div>

                        <!-- Индикатор топлива -->
                        <div class="s-rocket-game-fuel-indicator">
                            <div class="s-rocket-game-fuel-bar"
                                :style="{ width: (ship.fuel / ship.fuelCapacity) * 100 + '%' }" :class="{
                                    's-rocket-game-fuel-bar_low': ship.fuel <= ship.fuelCapacity * 0.1 && ship.fuel > 0,
                                    's-rocket-game-fuel-bar_critical': ship.fuel <= 0
                                }"></div>
                            <div class="s-rocket-game-fuel-text">
                                {{ Math.round(ship.fuel) }}/{{ ship.fuelCapacity }}
                            </div>
                        </div>

                        <div v-if="ship.fuel <= ship.fuelCapacity * 0.1 && ship.fuel > 0 && !ship.isRefueling"
                            class="s-rocket-game-refuel-indicator">
                            Кликни для заправки!
                        </div>

                        <div v-if="ship.isRefueling" class="s-rocket-game-refueling-animation">
                            <div class="s-rocket-game-fuel-drops">
                                <span v-for="n in 5" :key="n" class="s-rocket-game-fuel-drop"></span>
                            </div>
                            Заправка...
                        </div>

                        <div v-if="ship.isCrashed && !ship.isRemoving" class="s-rocket-game-crash-message">
                            Разбился!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { SButton, SInput, SStat, STag } from 'startup-ui';

// Типы кораблей
const shipTypes = [
    {
        id: 1,
        name: "Нырок",
        image: "/ship1.png",
        crashedImage: "/ship_crashed1.png",
        fuelCapacity: 300,
        speed: 2000
    },
    {
        id: 2,
        name: "Барби",
        image: "/ship2.png",
        crashedImage: "/ship_crashed2.png",
        fuelCapacity: 800,
        speed: 4000
    },
    {
        id: 3,
        name: "Аврора",
        image: "/ship3.png",
        crashedImage: "/ship_crashed3.png",
        fuelCapacity: 1500,
        speed: 6000
    },
    {
        id: 4,
        name: "Маршал",
        image: "/ship4.png",
        crashedImage: "/ship_crashed4.png",
        fuelCapacity: 2500,
        speed: 8000
    },
    {
        id: 5,
        name: "Бамбалби",
        image: "/ship5.png",
        crashedImage: "/ship_crashed5.png",
        fuelCapacity: 3500,
        speed: 10000
    }
]

// Запущенные корабли
const launchedShips = ref([])
const showLaunchModal = ref(false)
const selectedShip = ref(null)
const shipName = ref('')

// Статистика
const totalLaunched = ref(0)
const crashedShips = ref(0)

// Время последнего обновления
let lastTimestamp = 0

// Размеры игрового поля
const gameArea = ref({
    width: 0,
    height: 0,
    left: 0,
    top: 0
})

// Размеры корабля
const shipFullHeight = 120
const shipFullWidth = 100
const shipImageHeight = 70
const shipImageWidth = 70

// Выбор корабля
const selectShip = (ship) => {
    selectedShip.value = ship
    shipName.value = ''
    showLaunchModal.value = true
}

// Закрытие модального окна
const closeModal = () => {
    showLaunchModal.value = false
    selectedShip.value = null
    shipName.value = ''
}

// Запуск корабля
const launchShip = () => {
    if (!shipName.value.trim() || !selectedShip.value) return

    const ship = {
        id: Date.now() + Math.random(),
        name: shipName.value.trim(),
        typeId: selectedShip.value.id,
        typeName: selectedShip.value.name,
        image: selectedShip.value.image,
        crashedImage: selectedShip.value.crashedImage,
        fuelCapacity: selectedShip.value.fuelCapacity,
        speed: selectedShip.value.speed,
        fuel: selectedShip.value.fuelCapacity,
        position: {
            x: Math.random() * (gameArea.value.width - shipFullWidth) + shipFullWidth / 2,
            y: Math.random() * (gameArea.value.height - shipFullHeight) + shipFullHeight / 2
        },
        direction: {
            x: (Math.random() - 0.5) * 2,
            y: (Math.random() - 0.5) * 2
        },
        rotation: 0,
        isCrashed: false,
        isRemoving: false,
        isRefueling: false,
        zIndex: Math.floor(Math.random() * 1000),
        fuelConsumptionRate: selectedShip.value.speed / 1000,
        velocity: { x: 0, y: 0 },
        maxSpeed: 1.5,
        acceleration: 0.05,
        bounceFactor: 0.8,
        width: shipFullWidth,
        height: shipFullHeight
    }

    launchedShips.value.push(ship)
    totalLaunched.value++

    closeModal()
}

// Заправка корабля
const refuelShip = (ship) => {
    if (ship.fuel <= 0) return
    if (ship.isRefueling) return
    if (ship.isCrashed) return

    ship.isRefueling = true

    const refuelInterval = setInterval(() => {
        ship.fuel = Math.min(ship.fuelCapacity, ship.fuel + ship.fuelCapacity * 0.1)

        if (ship.fuel >= ship.fuelCapacity) {
            clearInterval(refuelInterval)
            setTimeout(() => {
                ship.isRefueling = false
                ship.direction.x = (Math.random() - 0.5) * 2
                ship.direction.y = (Math.random() - 0.5) * 2
                ship.velocity = { x: 0, y: 0 }
            }, 300)
        }
    }, 50)
}

// Получение изображения корабля в зависимости от состояния
const getShipImage = (ship) => {
    if (ship.isCrashed) {
        return ship.crashedImage
    }
    return ship.image
}

// Вычисляемая статистика
const activeShips = computed(() => {
    return launchedShips.value.filter(ship => ship.fuel > 0 && !ship.isRemoving).length
})

const shipsInGame = computed(() => {
    return launchedShips.value.filter(ship => !ship.isRemoving).length
})

// Обработка столкновения с границами игрового поля
const handleBoundaryCollision = (ship) => {
    const halfWidth = ship.width / 2
    const halfHeight = ship.height / 2

    const minX = halfWidth
    const maxX = gameArea.value.width - halfWidth
    const minY = halfHeight
    const maxY = gameArea.value.height - halfHeight

    if (ship.position.x < minX) {
        ship.position.x = minX
        ship.velocity.x = -ship.velocity.x * ship.bounceFactor
        ship.direction.x = -Math.abs(ship.direction.x)
    } else if (ship.position.x > maxX) {
        ship.position.x = maxX
        ship.velocity.x = -ship.velocity.x * ship.bounceFactor
        ship.direction.x = Math.abs(ship.direction.x)
    }

    if (ship.position.y < minY) {
        ship.position.y = minY
        ship.velocity.y = -ship.velocity.y * ship.bounceFactor
        ship.direction.y = -Math.abs(ship.direction.y)
    } else if (ship.position.y > maxY) {
        ship.position.y = maxY
        ship.velocity.y = -ship.velocity.y * ship.bounceFactor
        ship.direction.y = Math.abs(ship.direction.y)
    }
}

// Основной игровой цикл
let animationFrame = null

const updateShips = (timestamp) => {
    if (!lastTimestamp) lastTimestamp = timestamp

    const deltaTime = Math.min((timestamp - lastTimestamp) / 1000, 0.1)
    lastTimestamp = timestamp

    launchedShips.value.forEach((ship) => {
        if (ship.isRemoving) {
            return
        }

        if (ship.isCrashed) {
            ship.position.y += 150 * deltaTime
            ship.rotation += 200 * deltaTime

            if (ship.position.y > gameArea.value.height + 200) {
                ship.isRemoving = true
                setTimeout(() => {
                    const shipIndex = launchedShips.value.findIndex(s => s.id === ship.id)
                    if (shipIndex !== -1) {
                        launchedShips.value.splice(shipIndex, 1)
                    }
                }, 500)
            }
            return
        }

        if (ship.fuel <= 0) {
            ship.isCrashed = true
            crashedShips.value++
            ship.velocity = { x: 0, y: 50 }
            return
        }

        const fuelConsumed = ship.fuelConsumptionRate * deltaTime
        ship.fuel = Math.max(0, ship.fuel - fuelConsumed)

        ship.velocity.x += ship.direction.x * ship.acceleration * deltaTime
        ship.velocity.y += ship.direction.y * ship.acceleration * deltaTime

        const speed = Math.sqrt(ship.velocity.x * ship.velocity.x + ship.velocity.y * ship.velocity.y)
        if (speed > ship.maxSpeed) {
            ship.velocity.x = (ship.velocity.x / speed) * ship.maxSpeed
            ship.velocity.y = (ship.velocity.y / speed) * ship.maxSpeed
        }

        ship.position.x += ship.velocity.x * 60 * deltaTime
        ship.position.y += ship.velocity.y * 60 * deltaTime

        handleBoundaryCollision(ship)

        if (ship.fuel <= ship.fuelCapacity * 0.1) {
            ship.velocity.y += 5 * deltaTime
            ship.rotation += 8 * deltaTime
            ship.position.x += (Math.random() - 0.5) * 1 * deltaTime
        } else {
            ship.rotation = Math.sin(timestamp / 2000) * 2
        }

        ship.velocity.x *= 0.995
        ship.velocity.y *= 0.995
    })

    animationFrame = requestAnimationFrame(updateShips)
}

// Обновление размеров игрового поля
const updateGameArea = () => {
    const container = document.querySelector('.s-rocket-game-area')
    if (container) {
        const rect = container.getBoundingClientRect()
        gameArea.value = {
            width: rect.width,
            height: rect.height,
            left: rect.left,
            top: rect.top
        }

        launchedShips.value.forEach(ship => {
            const halfWidth = ship.width / 2
            const halfHeight = ship.height / 2

            ship.position.x = Math.max(halfWidth, Math.min(gameArea.value.width - halfWidth, ship.position.x))
            ship.position.y = Math.max(halfHeight, Math.min(gameArea.value.height - halfHeight, ship.position.y))
        })
    }
}

// Инициализация
onMounted(() => {
    window.addEventListener('resize', updateGameArea)

    setTimeout(() => {
        updateGameArea()

        if (gameArea.value.width > 0 && gameArea.value.height > 0) {
            const demoShip = {
                id: 'demo',
                name: 'Барби',
                typeId: 2,
                typeName: 'Барби',
                image: '/ship2.png',
                crashedImage: '/ship_crashed2.png',
                fuelCapacity: 800,
                speed: 4000,
                fuel: 800,
                position: {
                    x: gameArea.value.width / 2,
                    y: gameArea.value.height / 2
                },
                direction: {
                    x: 1.0,
                    y: -0.8
                },
                rotation: 0,
                isCrashed: false,
                isRemoving: false,
                isRefueling: false,
                zIndex: 500,
                fuelConsumptionRate: 4000 / 1000,
                velocity: { x: 0, y: 0 },
                maxSpeed: 1.5,
                acceleration: 0.05,
                bounceFactor: 0.8,
                width: shipFullWidth,
                height: shipFullHeight
            }
            launchedShips.value.push(demoShip)
            totalLaunched.value++
        }
    }, 100)

    animationFrame = requestAnimationFrame(updateShips)
})

onUnmounted(() => {
    if (animationFrame) {
        cancelAnimationFrame(animationFrame)
    }
    window.removeEventListener('resize', updateGameArea)
})
</script>

<style lang="scss">
.s-rocket-game {
    width: 100vw;
    height: 100vh;
    position: relative;
    font-family: var(--s-font-family);
    overflow: hidden;
    background: linear-gradient(135deg, #0c0c2e 0%, #1a1a3e 100%);
    padding: 15px;
    box-sizing: border-box;
    display: flex;
    gap: 15px;

    &-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('/bg.jpg') center/cover no-repeat;
        z-index: 1;
        opacity: 0.2;
    }

    // Левая панель с меню и статистикой
    &-left-panel {
        width: 320px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        flex-shrink: 0;
        position: relative;
        z-index: 10;

        @media (max-width: 1024px) {
            width: 280px;
        }

        @media (max-width: 768px) {
            width: 100%;
            flex-direction: row;
            flex-wrap: wrap;
            height: auto;
        }
    }

    // Меню выбора кораблей
    &-menu {
        flex: 1;
        background: rgba(0, 0, 0, 0.85);
        border: 2px solid var(--s-primary);
        border-radius: var(--s-border-radius);
        padding: 15px;
        color: var(--s-white);
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.8);
        display: flex;
        flex-direction: column;
        min-height: 0;

        @media (max-width: 768px) {
            width: 100%;
            order: 2;
        }
    }

    &-menu-title {
        color: var(--s-primary);
        text-align: center;
        margin-bottom: 15px;
        font-size: 1.3rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    &-ships-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        flex: 1;
        overflow-y: auto;
        padding-right: 5px;

        &::-webkit-scrollbar {
            width: 6px;
        }

        &::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        &::-webkit-scrollbar-thumb {
            background: var(--s-primary);
            border-radius: 3px;
        }
    }

    &-ship-card {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
        min-height: 70px;

        &:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(3px);
            border-color: var(--s-primary);
            box-shadow: 0 4px 12px rgba(var(--s-primary-rgb), 0.2);
        }

        &:active {
            transform: translateX(0);
        }
    }

    &-ship-image {
        width: 50px;
        height: 50px;
        object-fit: contain;
        flex-shrink: 0;
    }

    &-ship-info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 5px;
    }

    &-ship-name {
        color: var(--s-white);
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    &-ship-details {
        display: flex;
        flex-direction: column;
        gap: 3px;
        font-size: 0.85rem;
    }

    &-ship-detail {
        color: var(--s-text-light);
        white-space: nowrap;
    }

    &-stats {
        background: rgba(0, 0, 0, 0.85);
        border: 2px solid var(--s-primary);
        border-radius: var(--s-border-radius);
        padding: 15px;
        color: var(--s-white);
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.8);
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;

        @media (max-width: 768px) {
            width: 100%;
            order: 1;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        @media (max-width: 480px) {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    &-stat {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 1rem;

        &:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 0;
        }
    }

    &-stat-label {
        color: var(--s-primary-light);
        font-weight: 600;
    }

    &-stat-value {
        color: var(--s-white);
        font-weight: bold;
        font-size: 1.3rem;
        min-width: 40px;
        text-align: right;

        @media (max-width: 768px) {
            text-align: center;
        }
    }

    &-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        backdrop-filter: blur(10px);
    }

    &-modal {
        background: linear-gradient(135deg, rgba(20, 20, 40, 0.95) 0%, rgba(10, 10, 30, 0.95) 100%);
        border: 2px solid var(--s-primary);
        border-radius: var(--s-border-radius);
        padding: 25px;
        width: 90%;
        max-width: 450px;
        color: var(--s-white);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9);
        animation: s-rocket-game-modalAppear 0.3s ease-out;
    }

    @keyframes s-rocket-game-modalAppear {
        from {
            opacity: 0;
            transform: scale(0.8) translateY(-50px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    &-modal-title {
        color: var(--s-primary);
        text-align: center;
        margin-bottom: 15px;
        font-size: 1.5rem;
        font-weight: 600;
    }

    &-modal-ship-info {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        padding: 12px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: var(--s-border-radius);
    }

    &-modal-ship-image {
        width: 80px;
        height: 80px;
        object-fit: contain;
        flex-shrink: 0;
    }

    &-modal-ship-stats p {
        margin: 6px 0;
        font-size: 0.9rem;
    }

    &-modal-input {
        margin-bottom: 15px;
    }

    &-modal-label {
        display: block;
        margin-bottom: 6px;
        color: var(--s-white);
        font-weight: 600;
        font-size: 0.9rem;
    }

    &-modal-field {
        width: 100%;
        padding: 10px;
        border: 1px solid var(--s-border);
        border-radius: var(--s-border-radius);
        background: rgba(255, 255, 255, 0.1);
        color: var(--s-white);
        font-size: 0.95rem;
        transition: all 0.3s ease;

        &:focus {
            outline: none;
            border-color: var(--s-primary);
            box-shadow: 0 0 0 2px rgba(var(--s-primary-rgb), 0.3);
        }
    }

    &-modal-buttons {
        display: flex;
        justify-content: space-between;
        gap: 15px;
    }

    &-button {
        flex: 1;
        padding: 10px 20px;
        border: none;
        border-radius: var(--s-border-radius);
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;

        &_cancel {
            background: linear-gradient(135deg, var(--s-red) 0%, var(--s-red-dark) 100%);
            color: var(--s-white);

            &:hover {
                background: linear-gradient(135deg, var(--s-red-dark) 0%, var(--s-red) 100%);
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(var(--s-red-rgb), 0.3);
            }
        }

        &_launch {
            background: linear-gradient(135deg, var(--s-primary) 0%, var(--s-primary-dark) 100%);
            color: var(--s-white);

            &:hover:not(:disabled) {
                background: linear-gradient(135deg, var(--s-primary-dark) 0%, var(--s-primary) 100%);
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(var(--s-primary-rgb), 0.3);
            }

            &:disabled {
                opacity: 0.5;
                cursor: not-allowed;
                transform: none;
            }
        }
    }

    // Игровая зона
    &-container {
        flex: 1;
        position: relative;
        z-index: 50;
        min-width: 0;

        @media (max-width: 768px) {
            width: 100%;
            height: 60vh;
            order: 3;
        }
    }

    &-area {
        position: relative;
        width: 100%;
        height: 100%;
        border-radius: var(--s-border-radius);
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8);
        background: rgba(0, 0, 0, 0.3);
    }

    // Границы игрового поля
    &-boundary {
        position: absolute;
        background: rgba(var(--s-primary-rgb), 0.25);
        z-index: 5;
        pointer-events: none;

        &_top {
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: rgba(var(--s-primary-rgb), 0.4);
        }

        &_right {
            top: 0;
            right: 0;
            bottom: 0;
            width: 5px;
            background: rgba(var(--s-primary-rgb), 0.4);
        }

        &_bottom {
            bottom: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: rgba(var(--s-primary-rgb), 0.4);
        }

        &_left {
            top: 0;
            left: 0;
            bottom: 0;
            width: 5px;
            background: rgba(var(--s-primary-rgb), 0.4);
        }
    }

    &-field {
        position: relative;
        width: 100%;
        height: 100%;
        z-index: 10;
    }

    &-ship-container {
        position: absolute;
        width: 100px;
        height: 120px;
        transition: opacity 0.5s ease;
        cursor: pointer;
        z-index: 100 !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        pointer-events: all;
        padding-top: 5px;

        @media (max-width: 768px) {
            width: 90px;
            height: 110px;
        }

        @media (max-width: 480px) {
            width: 80px;
            height: 100px;
        }
    }

    &-flying-ship {
        width: 70px;
        height: 70px;
        object-fit: contain;
        transition: all 0.3s ease;
        filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.4));
        flex-shrink: 0;
        order: 1;

        &_low-fuel {
            animation: s-rocket-game-lowFuelPulse 1.5s infinite;
        }

        &_falling {
            animation: s-rocket-game-fallingAnimation 0.5s ease-out;
            filter: drop-shadow(0 0 10px var(--s-red));
        }
    }

    @keyframes s-rocket-game-lowFuelPulse {

        0%,
        100% {
            filter: drop-shadow(0 0 3px var(--s-yellow));
            transform: scale(1);
        }

        50% {
            filter: drop-shadow(0 0 8px var(--s-yellow));
            transform: scale(1.03);
        }
    }

    @keyframes s-rocket-game-fallingAnimation {
        to {
            transform: rotate(180deg) scale(0.8);
        }
    }

    &-ship-name-tag {
        background: rgba(0, 0, 0, 0.7);
        color: var(--s-white);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        white-space: nowrap;
        border: 1px solid var(--s-primary);
        z-index: 10;
        margin-bottom: 5px;
        flex-shrink: 0;
        max-width: 90%;
        overflow: hidden;
        text-overflow: ellipsis;
        order: 0;

        @media (max-width: 768px) {
            font-size: 10px;
            padding: 3px 6px;
        }
    }

    &-fuel-indicator {
        width: 80px;
        height: 16px;
        background: rgba(0, 0, 0, 0.7);
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--s-border);
        z-index: 10;
        margin-top: 8px;
        flex-shrink: 0;
        position: relative;
        order: 2;

        @media (max-width: 768px) {
            width: 70px;
            height: 14px;
            margin-top: 6px;
        }

        @media (max-width: 480px) {
            width: 60px;
            height: 12px;
            margin-top: 5px;
        }
    }

    &-fuel-bar {
        position: absolute;
        height: 100%;
        background: linear-gradient(90deg, var(--s-green), var(--s-green-dark));
        border-radius: 7px;
        transition: width 0.3s ease;
        z-index: 1;
        left: 0;
        top: 0;

        &_low {
            background: linear-gradient(90deg, var(--s-yellow), var(--s-yellow-dark));
        }

        &_critical {
            background: linear-gradient(90deg, var(--s-red), var(--s-red-dark));
        }
    }

    &-fuel-text {
        position: absolute;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--s-white);
        font-size: 9px;
        font-weight: bold;
        z-index: 2;
    }

    &-refuel-indicator {
        position: absolute;
        top: -40px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--s-yellow);
        color: var(--s-black);
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: bold;
        animation: s-rocket-game-refuelPulse 1s infinite;
        z-index: 20;
        border: 1px solid var(--s-yellow-dark);
        white-space: nowrap;
        font-size: 10px;
        order: 3;
    }

    @keyframes s-rocket-game-refuelPulse {

        0%,
        100% {
            transform: translateX(-50%) scale(1);
            opacity: 0.8;
        }

        50% {
            transform: translateX(-50%) scale(1.03);
            opacity: 1;
        }
    }

    &-refueling-animation {
        position: absolute;
        top: -55px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--s-green);
        color: var(--s-white);
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: bold;
        z-index: 20;
        border: 1px solid var(--s-green-dark);
        white-space: nowrap;
        font-size: 10px;
        order: 3;
    }

    &-fuel-drops {
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 3px;
    }

    &-fuel-drop {
        width: 3px;
        height: 3px;
        background: var(--s-green-light);
        border-radius: 50%;
        animation: s-rocket-game-fuelDrop 1s infinite;

        &:nth-child(2) {
            animation-delay: 0.1s;
        }

        &:nth-child(3) {
            animation-delay: 0.2s;
        }

        &:nth-child(4) {
            animation-delay: 0.3s;
        }

        &:nth-child(5) {
            animation-delay: 0.4s;
        }
    }

    @keyframes s-rocket-game-fuelDrop {
        0% {
            transform: translateY(0);
            opacity: 0;
        }

        50% {
            opacity: 1;
        }

        100% {
            transform: translateY(15px);
            opacity: 0;
        }
    }

    &-crash-message {
        position: absolute;
        top: -60px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--s-red);
        color: var(--s-white);
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: bold;
        z-index: 20;
        border: 1px solid var(--s-red-dark);
        white-space: nowrap;
        font-size: 10px;
        animation: s-rocket-game-crashMessage 1s ease-out;
        order: 3;
    }

    @keyframes s-rocket-game-crashMessage {
        0% {
            opacity: 0;
            transform: translateX(-50%) translateY(0);
        }

        50% {
            opacity: 1;
            transform: translateX(-50%) translateY(-10px);
        }

        100% {
            opacity: 0;
            transform: translateX(-50%) translateY(-20px);
        }
    }
}
</style>