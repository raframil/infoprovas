<template>
  <div class="home">
    <h1 class="display-2 grey--text">Bem-vindo!</h1>

    <v-container class="my-5">
      <h3 class="mt-5">Clique no botão abaixo para começar</h3>
      <div>
        <v-btn color="error" fab large dark>
          <v-icon>play_arrow</v-icon>
        </v-btn>
      </div>
    </v-container>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Home Component mounted.");
  }
};
</script>
