<script setup>
import { RouterView, RouterLink} from 'vue-router';
</script>

<template>
  <div id="app">
    <ul>
      <li>
        <router-link :to="{name:'home'}">Home</router-link>
      </li>
      <li>
        <router-link :to="{name:'login'}">Login</router-link>
      </li>
      <li>
        <router-link :to="{name:'feed'}">feed</router-link>
      </li>
  </ul>
  <router-view />
  </div>
</template>

<style scoped>
#app {
  font-family: Arial, sans-serif;
  text-align: center;
}
</style>
