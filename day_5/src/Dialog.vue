<template>
    <div v-if="show" class="form-overlay" @click.self="$emit('close')">
        <div class="form">
            <div class="form-title">
                <div>
                    <slot name="title">
                        <h3>{{ title }}</h3>
                    </slot>
                </div>
                <div><button class="close-btn" @click="$emit('close')">Х</button></div>
            </div>
            <slot></slot>
        </div>
    </div>
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
</style>