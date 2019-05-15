<template>
  <v-form>
    <v-container>
      <v-layout>
        <v-flex xs12 md4>
          <v-text-field v-model="busca"></v-text-field>
          <input type="submit" id="lupa" value="Buscar" @click.prevent="buscarCursos">
        </v-flex>
      </v-layout>
    </v-container>
  </v-form>
</template>


<script>
export default {
  data() {
    return {
      busca: ""
    };
  },
  methods: {
    buscarCursos() {
      this.$router.push({ query: { busca: "notebook" } });
    }
  }
};
</script>