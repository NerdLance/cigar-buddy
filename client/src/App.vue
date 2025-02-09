<script setup>
  import { RouterView } from 'vue-router'
  import HeaderSection from './components/layout/HeaderSection.vue'
</script>

<template>
  <HeaderSection />

  <div class="max-w-7xl mx-auto p-4">
    <RouterView />
  </div>
</template>
