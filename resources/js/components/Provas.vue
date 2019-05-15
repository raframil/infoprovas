<template>
  <div class="disciplinas">
    <h1 class="display-1 grey--text">Provas de {{ disciplina.nome }}</h1>
    <v-container v-if="provas && provas.length" grid-list-md text-xs-center>
      <v-data-table :headers="headers" :items="provas" class="elevation-1">
        <template v-slot:items="provas">
          <td class="text-xs-center">{{ provas.item.periodo }}</td>
          <td class="text-xs-left">{{ provas.item.professores.nome }}</td>
          <td class="text-xs-left">{{ provas.item.tipo_prova.descricao }}</td>
          <td class="text-xs-left">{{ provas.item.ano }}</td>
          <td class="text-xs-center">
            <v-btn small color="blue-grey" class="white--text">
              Visualizar
              <v-icon right dark>remove_red_eye</v-icon>
            </v-btn>
            <v-btn small class="warning">
              Download
              <v-icon right dark>cloud_download</v-icon>
            </v-btn>
          </td>
        </template>
      </v-data-table>
    </v-container>
    <v-container text-xs-center v-else-if="provas && provas.length == 0">
      <h2 class="red--text headline">Não há provas registradas para esta disciplina</h2>
    </v-container>
    <pagina-carregando v-else></pagina-carregando>
  </div>
</template>


<script>
export default {
  props: ["disciplina_id"],
  data() {
    return {
      provas: null,
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
          text: "Professor",
          align: "left",
          value: "professor"
        },
        { text: "Tipo de Prova", value: "tipo_prova" },
        { text: "Ano", value: "ano" },
        { text: "Ação", value: "action", align: "center" }
      ]
    };
  },

  created() {
    this.getProvasFromDisciplina();
    this.getDisciplina();
  },
  methods: {
    getProvasFromDisciplina() {
      fetch(`api/provas/${this.disciplina_id}`)
        .then(res => res.json())
        .then(res => {
          console.log(res.data);
          this.provas = res.data;
        });
    },
    getDisciplina() {
      fetch(`api/disciplina/${this.disciplina_id}`)
        .then(res => res.json())
        .then(res => {
          this.disciplina = res.data;
        });
    }
  }
};
</script>