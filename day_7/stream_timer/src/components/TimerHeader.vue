<template>
    <div class="s-timerheader">
        <h1 class="s-timerheader-title">
            {{ title }}
        </h1>
    </div>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        required: true
    }
})
</script>

<style lang="scss">
.s-timerheader {
    margin-bottom: var(--s-base-margin);
    text-align: center;

    &-title {
        font-size: var(--s-h2-font-size);
        color: var(--s-text);
        font-weight: 500;
        margin: 0;
    }
}
</style>