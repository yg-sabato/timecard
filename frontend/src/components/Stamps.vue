<script setup>
import { computed, defineProps, withDefaults, defineEmits, ref } from "vue";
const progressFlag = ref(false);

const props = defineProps({
  modelValue: String,
  githubData: Array,
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

const submitStamp = async(event) => {
  // ボタンをクリックした時にボタンを無効にする
  event.target.disabled = true;
  progressFlag.value = true;

  const description = text.value;

  // 取得(存在しないなら空文字を返す)
  const issuetitle = props.githubData[0]['title'] || '';
  const issuebody = props.githubData[0]['body'] || '';
  const issueurl = props.githubData[0]['url'] || '';
  const reponame = props.githubData[0]['repoName'] || '';
  const issuenumber = props.githubData[0]['number'] || '';

  // APIリクエストを送信
  const response = await fetch(`${import.meta.env.VITE_BACKEND_ENDPOINT}/api/create-stamp`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      description,
      issuetitle,
      issuebody,
      issueurl,
      reponame,
      issuenumber,
    }),
  });

  if (!response.ok) {
    throw new Error('Failed to create a stamp');
  }

  text.value = '';
  emit('update:modelValue', '');

  const data = await response.json();
  alert("業務記録を作成しました");

  event.target.disabled = false;
  progressFlag.value = false;
  // 画面を更新
  location.reload();
}
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
    <VaButton @click="submitStamp">記録する</VaButton>
    <VaProgressCircle indeterminate v-if="progreeFlag" />
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