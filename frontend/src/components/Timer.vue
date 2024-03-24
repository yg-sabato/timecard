<template>
    <div class="time-display">
        <VaIcon class="material-icons">
            schedule
        </VaIcon>
        <p>現在時刻: {{ currentTime }}</p>
    </div>
</template>

<style lang="scss">
  .time-display {
    display: flex;
    gap: 8px;
    align-items: center;
  }
</style>
  
<script>
  import { ref, onMounted, onUnmounted } from 'vue';
  
  export default {
    name: 'TimeDisplay',
    setup() {
      const currentTime = ref(new Date().toLocaleTimeString());
  
      function updateTime() {
        currentTime.value = new Date().toLocaleTimeString();
      }
  
      let intervalId;
  
      onMounted(() => {
        intervalId = setInterval(updateTime, 1000);
      });
  
      onUnmounted(() => {
        clearInterval(intervalId);
      });
  
      return { currentTime };
    },
  };
</script>
  