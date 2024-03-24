<script setup>
    import { defineProps } from 'vue';
    defineProps({
        color: String,
    });
</script>

<template>
    <header>
        <h1 :style="{'color': color}"><slot>No TITLE</slot></h1>
    </header>
</template>