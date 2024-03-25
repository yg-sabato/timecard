<script setup>
import { computed, defineProps, withDefaults, defineEmits } from "vue";

const props = defineProps({
  modelValue: String, // プロパティの型を指定
});


const emit = defineEmits({
  "update:modelValue": null // イベントの型を指定しない場合、nullを使用
});

const text = computed({
  get: () => props.modelValue,
  set: (value) => { // 値に変更があると呼ばれるsetter
    emit('update:modelValue', value);
  },
});
</script>

<template>
  <h2>業務記録を作成</h2>
  <VaForm ref="formRef">
    <VaInput
      label="業務内容を記入"
      placeholder="例: エンジニアLT会参加" 
      v-model="text"
      >
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