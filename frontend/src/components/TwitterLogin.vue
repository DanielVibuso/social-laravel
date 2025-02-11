<template>
  <div>
    <button @click="redirectToTwitter">Login with Twitter</button>
  </div>
</template>

<script setup>
const redirectToTwitter = () => {
  window.location.href = "http://localhost:80/auth/twitter/redirect";
};
</script>

<style scoped>
/* Adicione estilos aqui se necessário */
</style>
