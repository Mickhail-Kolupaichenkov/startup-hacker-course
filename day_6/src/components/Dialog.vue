<template>
    <Transition name="fade-slide" appear>
        <div v-if="show" :key="show" class="form-overlay" @click.self="$emit('close')">
            <div class="form">
                <div class="form-title">
                    <div>
                        <slot name="title">
                            <h3>Диалоговое окно</h3>
                        </slot>
                    </div>
                    <div><button class="close-btn" @click="$emit('close')">Х</button></div>
                </div>
                <slot></slot>
            </div>
        </div>
    </Transition>
</template>

<script setup>
defineProps({
    show: {
        type: Boolean,
        default: true
    }
})
defineEmits(['close'])

</script>

<style scoped>
.form-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100;
}

.form {
    background: white;
    padding: 20px;
    border-radius: 8px;
    min-width: 450px;
    transform: translateY(0);
}

.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #657786;
}

.form-title {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

/* Анимация для всего overlay */
.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
}

.fade-slide-enter-from .form,
.fade-slide-leave-to .form {
    transform: translateY(50px);
    opacity: 0;
}

.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: opacity 0.4s ease;
}

.fade-slide-enter-active .form,
.fade-slide-leave-active .form {
    transition: all 0.4s ease;
}

.fade-slide-enter-to,
.fade-slide-leave-from {
    opacity: 1;
}

.fade-slide-enter-to .form,
.fade-slide-leave-from .form {
    transform: translateY(0);
    opacity: 1;
}
</style>