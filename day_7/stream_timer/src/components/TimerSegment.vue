<template>
<div class="timesegment">
    <div class="timesegment-datawrapper">
        <div class="timesegment-number-wrapper">
            <span class="number">0</span>
        </div>
        <span class="label">{{ label }}</span>
    </div>
</div>
</template>
<script setup>
defineProps({
    label: String,
});
</script>
<style scoped lang="scss">
.timesegment {

    &-datawrapper {
        display: flex;
        flex-direction: column;
        gap: 5px;
        align-items: center;
    }

    &-number-wrapper {
        background-color: aliceblue;
        padding: 5px;
        border-radius: 6px;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 70px;
    }

    .number {
        font-size: 32px;
    }
    .label {
        font-size: 16px;
    }
}
</style>
