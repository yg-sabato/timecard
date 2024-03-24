<script setup>
  import { defineProps } from 'vue';
  defineProps(['tmpDescription']);
</script>

<template>
  <h2>業務記録を作成</h2>
  <form method="post" action="">
    <div class="form-group">
      <label for="description">業務内容</label>
      <input id="description" 
          name="description" 
          class="form-control"  
          :value="tmpDescription" 
          @input="$emit('update:tmpDescription', $event.target.value)">
      </input>

    </div>
    <button type="submit" class="btn btn-primary">記録する</button>
  </form>
</template>