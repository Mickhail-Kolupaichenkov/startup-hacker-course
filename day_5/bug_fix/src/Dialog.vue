<template>
    <!-- Подложка -->
    <div class="dialog-overlay" @click.self="$emit('close')">
        <!-- Окно -->
        <div class="dialog-wrapper">
            <button class="dialog-close-button" @click="$emit('close')">
                <FontAwesomeIcon icon="xmark" />
            </button>

            <h3 class="dialog-title" v-if="title">{{ title }}</h3>

            <div class="dialog-inner">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    title: { type: String, default: null },
})

defineEmits(['close'])
</script>

<style scoped lang="scss">
.dialog-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6); // затемнение
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.dialog-wrapper {
    position: relative; // ВАЖНО
    background: #fff;
    border-radius: 8px;
    padding: 24px;
    min-width: 320px;
    max-width: 90%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
}

.dialog-title {
    margin: 0 0 16px;
    font-size: 18px;
}

.dialog-inner {
    position: relative;
}

/* Кнопка закрытия */
.dialog-close-button {
    position: absolute;
    top: 12px;
    right: 12px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
}
</style>

