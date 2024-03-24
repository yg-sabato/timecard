<script setup>
    import { defineProps } from 'vue';
    
    defineProps({
        todos: Array,
        newTodo: String,
        removeTodo: Function,
    });
    
 
</script>

<template>
    <ul v-if="todos.length > 0">
      <li v-for="(todo, i) in todos" :key="i"><button @click="removeTodo(i)">x</button> {{ todo }}</li>
    </ul>
    <p v-else>TODOを追加してください</p>
</template>