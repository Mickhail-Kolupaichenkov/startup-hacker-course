<template>
    <div class="app-wrapper">
        <div class="timerbox">
            <TimerHeader title="Будем в эфире через"/>
            <div class="timerbox-display">
                <TimerSegment label="дней" number="0" />
                <TimerSegment label="часов" number="0" />
                <TimerSegment label="минут" number="0" />
                <TimerSegment label="секунд" number="0" />
            </div>
        </div>
    </div>
</template>
<script setup>
import TimerHeader from "@/components/TimerHeader.vue";
import TimerSegment from "@/components/TimerSegment.vue";
</script>
<style scoped lang="scss">
.app-wrapper {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.timerbox {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 20px;
    border: 1px solid rgb(58, 58, 58);
    border-radius: 6px;
    box-shadow: 3px 3px 3px black;

    &-display {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
}
</style>
