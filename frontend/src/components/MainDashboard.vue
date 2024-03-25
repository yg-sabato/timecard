<script setup>
    import Timer from './Timer.vue';
    import TimestampsTable from './TimestampsTable.vue';
    import Stamps from './Stamps.vue';
    import GithubCards from './GithubCards.vue';

    import { ref } from 'vue';

    const tmpDescription = ref('');

    const updateTmpDescription = (value) => {
        tmpDescription.value = value;
    };
</script>

<template>
    <div id="main-dashboard">
        <Timer />
        <Stamps v-model:modelValue="tmpDescription" />
        <GithubCards :updateTmpDescription="updateTmpDescription" />
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
        max-width: 1000px;
        margin: 0 auto;
    }
</style>