<template>
    <div class="timerheader">
        <h1>{{ title }}</h1>
    </div>
</template>
<script setup>
const props = defineProps({
    title: String
});
</script>
<style scoped lang="scss">
.timerheader {
    margin-bottom: 20px;
}
</style>
