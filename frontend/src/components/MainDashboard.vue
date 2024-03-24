<script setup>
    import Timer from './Timer.vue';
    import TimestampsTable from './TimestampsTable.vue';
    import Stamps from './Stamps.vue';

    import { ref } from 'vue';

    const tmpDescription = ref('');
</script>

<template>
    <div id="main-dashboard">
        <Timer />
        <Stamps :tmpDescription="tmpDescription" />
        <TimestampsTable />
    </div>
</template>

<style lang="scss">
    #main-dashboard {
        padding: 16px;
        display: flex;
        flex-flow: column nowrap;
        gap: 16px;
        justify-content: flex-start;
        align-items: flex-start;
        height: 100%;
    }
</style>