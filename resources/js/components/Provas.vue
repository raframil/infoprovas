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
          <h1 class="display-1 grey--text" lg10>Provas</h1>
        </v-flex>
      </v-layout>
    </v-container>
    <v-container v-if="provas && provas.length" grid-list-md text-xs-center>
      <v-toolbar flat color="white">
        <v-toolbar-title>
          <span class="primary--text">{{ disciplina.nome }} ({{disciplina.codigo}})</span>
          <v-spacer></v-spacer>
        </v-toolbar-title>
      </v-toolbar>
      <v-data-table
        :headers="headers"
        :items="provas"
        class="elevation-1"
        :loading="isLoading"
        hide-actions
      >
        <template v-slot:items="provas">
          <td class="text-xs-left">{{ provas.item.professores.nome }}</td>
          <td class="text-xs-left">{{ provas.item.tipo_prova.descricao }}</td>
          <td class="text-xs-left">{{ provas.item.ano }}</td>
          <td class="text-xs-center">{{ provas.item.periodo }}</td>
          <td class="text-xs-center">
            <v-btn
              small
              color="blue-grey"
              class="white--text"
              @click="visualizarProva(provas.item.id)"
            >
              Visualizar
              <v-icon right dark>remove_red_eye</v-icon>
            </v-btn>
            <v-btn small class="warning" @click="downloadProva(provas.item.id)">
              Download
              <v-icon right dark>cloud_download</v-icon>
            </v-btn>
          </td>
        </template>
      </v-data-table>
      <v-pagination
        v-model="pagination.current_page"
        :length="pagination.last_page"
        @input="onPageChange"
      ></v-pagination>
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
      pagination: {},
      isLoading: false,
      disciplina: {
        id: "",
        nome: "",
        codigo: "",
        curso_id: ""
      },

      //tabela
      headers: [
        {
          text: "Professor",
          align: "left",
          value: "professor"
        },
        { text: "Tipo de Prova", value: "tipo_prova" },
        { text: "Ano", value: "ano" },
        {
          text: "Período",
          align: "center",
          value: "periodo"
        },
        { text: "Ação", value: "action", align: "center" }
      ]
    };
  },

  created() {
    this.getProvasFromDisciplina();
    this.getDisciplina();
  },
  methods: {
    onPageChange() {
      this.getProvasFromDisciplina();
    },
    makePagination(meta, links) {
      let pagination = {
        total: meta.total,
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };
      this.pagination = pagination;
    },
    changeLocale() {
      this.$vuetify.lang.current = "pt";
    },
    getProvasFromDisciplina() {
      this.isLoading = true;
      fetch(
        `api/provas/${this.disciplina_id}?page=${this.pagination.current_page}`
      )
        .then(res => res.json())
        .then(res => {
          this.provas = res.data;
          this.makePagination(res.meta, res.links);
          this.isLoading = false;
        });
    },
    getDisciplina() {
      fetch(`api/disciplina/${this.disciplina_id}`)
        .then(res => res.json())
        .then(res => {
          this.disciplina = res.data;
        });
    },
    visualizarProva(prova_id) {
      fetch("api/ver_prova/" + prova_id).then(res => {
        var newWindow = window.open(res.url, "my window");
      });
    },
    downloadProva(prova_id) {
      fetch("api/baixar_prova/" + prova_id).then(res => {
        var newWindow = window.open(res.url, "my window");
      });
    },
    voltarPagina() {
      this.$router.go(-1);
    }
  }
};
</script>