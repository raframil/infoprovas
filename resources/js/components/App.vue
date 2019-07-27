<template>
  <div class="mainapp" v-if="$auth.ready()">
    <navbar></navbar>

    <v-content class="mx-4 my-4">
      <transition mode="out-in">
        <router-view></router-view>
      </transition>
    </v-content>

    <footer-component></footer-component>
    <div v-if="!$auth.ready()">Carregando ...</div>
  </div>
</template>

<script>
export default {
  computed: {
    loggedIn() {
      return this.$store.getters.loggedIn;
    }
  }
};
</script>