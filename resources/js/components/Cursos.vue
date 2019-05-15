<template>
  <div class="cursos">
    <h1 class="display-2 grey--text">Cursos</h1>

    <v-container v-if="cursos && cursos.length" grid-list-md text-xs-center>
      <h2>Itajubá</h2>
      <v-layout row wrap>
        <v-flex v-for="curso in cursos" v-if="curso.campus === 'Itajubá'" :key="curso.id" lg4>
          <v-btn
            :to="{name: 'disciplinas', params: {curso_id: curso.id}}"
            flat
            color="primary"
          >{{ curso.nome }}</v-btn>
        </v-flex>
      </v-layout>
      <h2>Itabira</h2>
      <v-layout row wrap>
        <v-flex v-for="curso in cursos" v-if="curso.campus === 'Itabira'" :key="curso.id" lg4>
          <v-btn
            :to="{name: 'disciplinas', params: {curso_id: curso.id}}"
            flat
            color="red"
          >{{ curso.nome }}</v-btn>
        </v-flex>
      </v-layout>
    </v-container>

    <pagina-carregando v-else>></pagina-carregando>
  </div>
</template>

<script>
export default {
  data() {
    return {
      cursos: [],
      curso: {
        id: "",
        nome: "",
        campus: ""
      },
      cursos_id: ""
    };
  },

  computed: {},

  created() {
    this.fetchCursos();
  },

  methods: {
    fetchCursos(page_url) {
      let vm = this;
      page_url = page_url || "api/cursos";
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.cursos = res.data;
        });
    }
  }
};
</script>
