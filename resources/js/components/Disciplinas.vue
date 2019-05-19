<template>
  <div class="disciplinas">
    <v-container grid-list-md>
      <v-layout row wrap>
        <v-flex lg1>
          <v-btn small round @click="voltarPagina">
            <v-icon>keyboard_arrow_left</v-icon>
          </v-btn>
        </v-flex>
        <v-flex lg11>
          <h1 class="display-1 grey--text" lg10>Disciplinas de {{ curso.nome }}</h1>
        </v-flex>
      </v-layout>
    </v-container>

    <v-container v-if="disciplinas && disciplinas.length" grid-list-md text-xs-center>
      <v-data-table :headers="headers" :items="disciplinas" class="elevation-1">
        <template v-slot:items="disciplinas">
          <td class="text-xs-center">{{ disciplinas.item.periodo }}</td>
          <td class="text-xs-left">{{ disciplinas.item.nome }}</td>
          <td class="text-xs-left">{{ disciplinas.item.codigo }}</td>
          <td class="text-xs-center">
            <v-btn
              class="info"
              :to="{name: 'provas', params: {disciplina_id: disciplinas.item.id, nome: disciplinas.item.nome}}"
            >Visualizar</v-btn>
          </td>
        </template>
      </v-data-table>
    </v-container>
    <v-container text-xs-center v-else-if="disciplinas && disciplinas.length == 0">
      <h2 class="red--text headline">Não há disciplinas registradas para este curso</h2>
      <p>Entre em contato com seu centro/diretório acadêmico para registrar as disciplinas no sistema</p>
    </v-container>
    <pagina-carregando v-else></pagina-carregando>
  </div>
</template>



<script>
export default {
  props: ["curso_id"],
  data() {
    return {
      curso: {
        id: "",
        nome: "",
        campus: ""
      },
      disciplinas: null,
      disciplina: {
        id: "",
        nome: "",
        codigo: "",
        curso_id: ""
      },

      //tabela
      headers: [
        {
          text: "Período",
          align: "center",
          value: "periodo"
        },
        {
          text: "Nome",
          align: "left",
          value: "nome"
        },
        { text: "Codigo", value: "codigo" },
        { text: "Ação", value: "action", align: "center" }
      ]
    };
  },

  created() {
    this.getCurso();
    this.getDisciplinasFromCurso();
  },
  methods: {
    changeLocale() {
      this.$vuetify.lang.current = "pt";
    },
    getCurso() {
      fetch(`api/curso/${this.curso_id}`)
        .then(res => res.json())
        .then(res => {
          this.curso = res.data;
        });
    },
    getDisciplinasFromCurso() {
      fetch(`api/disciplinas/${this.curso_id}`)
        .then(res => res.json())
        .then(res => {
          this.disciplinas = res.data;
        });
    },
    voltarPagina() {
      this.$router.go(-1);
    }
  }
};
</script>