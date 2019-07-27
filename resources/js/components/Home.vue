<template>
  <div class="home">
    <h1 class="display-2 grey--text">Bem-vindo!</h1>

    <v-container class="my-5 text-xs-center">
      <h4 class="mt-5 display-1">Use o menu lateral ou clique no botão abaixo para começar</h4>
      <div class="mt-5">
        <v-btn color="error" fab outline large dark to="/cursos">
          <v-icon>play_arrow</v-icon>
        </v-btn>
      </div>
    </v-container>
  </div>
</template>

<script>
export default {
  created() {
    this.getUser();
  },
  methods: {
    getUser() {
      this.$store.dispatch("getUserData").then(response => {});
    }
  }
};
</script>
