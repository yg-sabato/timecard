<script setup>
  import { defineProps } from 'vue';
  defineProps(['tmpDescription']);
</script>

<template>
  <h2>業務記録を作成</h2>
  <VaForm ref="formRef">
    <VaInput
      label="業務内容を記入"  
      :value="tmpDescription" 
      @input="$emit('update:tmpDescription', $event.target.value)">
    </VaInput>
    <VaButton type="submit">記録する</VaButton>
  </VaForm>
</template>

<style lang="scss">
  form{
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;

    .va-input{
      width: 100%;
    }

    .va-button{
      max-width: 300px;
    }
  }
</style>