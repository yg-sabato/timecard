<script setup lang="ts">

  import { ref } from 'vue';
  import { useBreakpoint } from 'vuestic-ui';
  import MainDashboard from './components/MainDashboard.vue';

  const showSidebar = ref(false)

  const breakpoints = useBreakpoint()
</script>

<template>
  <VaLayout 
    style="height: 100vh; width: 100vw;"
    :left="{ absolute: breakpoints.smDown }"
  >
    <template #top>
      <VaNavbar color="primary" class="py-2">
        <!-- <template #left>
          <VaButton :icon="showSidebar ? 'menu_open' : 'menu'" @click="showSidebar = !showSidebar" />
        </template> -->
        <template #center>
          <VaNavbarItem class="font-bold text-lg">
            業務計測ツール
          </VaNavbarItem>
        </template>
      </VaNavbar>
    </template>

    <template #left>
      <VaSidebar v-model="showSidebar">
        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="home" /> 
            <VaSidebarItemTitle>
              Home
            </VaSidebarItemTitle>
          </VaSidebarItemContent>
        </VaSidebarItem>
        <VaSidebarItem>
          <VaSidebarItemContent>
            <VaIcon name="phone" />
            <VaSidebarItemTitle>
              About
            </VaSidebarItemTitle>
          </VaSidebarItemContent>
        </VaSidebarItem>
      </VaSidebar>
    </template>

    <template #content>
      <main>
        <MainDashboard />
      </main>
    </template>
  </VaLayout>
</template>

<style lang="scss">
  body{
    font-size: 16px;
    font-family: "BIZ UDGothic", sans-serif;
    font-weight: 400;
    font-style: normal;
  }
  main{
    max-height: 100%;
    overflow: scroll;
  }
  h2{
    font-size: 1.5rem;
    margin-top: 1rem;
  }
</style>