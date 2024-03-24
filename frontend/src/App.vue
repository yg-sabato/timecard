<script setup>
  import AppHeader from './components/AppHeader.vue';
  import TodoList from './components/TodoList.vue';

  import { ref } from 'vue';

  const todos = ref([]);
  const newTodo = ref("");

  /**
 * TODOを追加する
 */
  const addTodo = () => {
    todos.value.push(newTodo.value);
    newTodo.value = "";
  };
  /**
   * TODOを削除する
   * @param {number} i 削除するTODOのインデックス
   */
  const removeTodo = (i) => {
      todos.value.splice(i, 1);
  };

</script>

<template>
  <AppHeader color="blue">TODOツール</AppHeader>
  <input type="text" size="30" v-model="newTodo">
  <button @click="addTodo()">追加</button>
  <TodoList :todos="todos" :newTodo="newTodo" :removeTodo="removeTodo" />
</template>