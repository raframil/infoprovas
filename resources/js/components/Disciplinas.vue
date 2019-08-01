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
          <h1 class="display-1 grey--text" lg10>Disciplinas</h1>
        </v-flex>
      </v-layout>
    </v-container>

    <v-card v-if="disciplinas && disciplinas.length" grid-list-md text-xs-center>
      <v-card-title>
        Lista de Disciplinas
        <v-divider class="mx-3" inset vertical></v-divider>
        <span class="primary--text">{{ curso.nome }}</span>
        <v-spacer></v-spacer>
        <v-text-field v-model="search" append-icon="search" label="Buscar" single-line></v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="disciplinas"
        :loading="isLoading"
        :search="search"
        class="elevation-1"
      >
        <template v-slot:items="disciplinas">
          <td class="text-xs-center">{{ disciplinas.item.periodo }}</td>
          <td class="text-xs-left">{{ disciplinas.item.nome }}</td>
          <td class="text-xs-left">{{ disciplinas.item.codigo }}</td>
          <td class="text-xs-center">{{ disciplinas.item.num_provas }}</td>
          <td class="text-xs-center">
            <v-btn
              class="info"
              :to="{name: 'provas', params: {disciplina_id: disciplinas.item.id, nome: disciplinas.item.nome}}"
            >Visualizar</v-btn>
          </td>
        </template>
      </v-data-table>
    </v-card>
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
      search: "",
      curso: {
        id: "",
        nome: "",
        campus: ""
      },
      disciplinas: {
        id: "",
        nome: "",
        codigo: "",
        curso_id: "",
        num_provas: 0
      },

      //disciplinas: null,
      pagination: {},
      isLoading: false,

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
        { text: "N° de Provas", align: "center", value: "count_provas" },
        { text: "Ação", value: "action", align: "center" }
      ]
    };
  },

  created() {
    this.getCurso();
    this.getDisciplinasFromCurso();
    //this.Paginate();
  },
  methods: {
    onPageChange() {
      //this.Paginate();
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
    getCurso() {
      fetch(`api/curso/${this.curso_id}`)
        .then(res => res.json())
        .then(res => {
          this.curso = res.data;
        });
    },
    getDisciplinasFromCurso() {
      this.isLoading = true;
      fetch(`api/disciplinas/${this.curso_id}`)
        .then(res => res.json())
        .then(res => {
          console.log(res);
          this.disciplinas = res.data;
          this.isLoading = false;
        });
    },
    /*getDisciplinasFromCursoPaginate() {
      this.isLoading = true;
      fetch(
        `api/disciplinas/${this.curso_id}?page=${this.pagination.current_page}`
      )
        .then(res => res.json())
        .then(res => {
          this.disciplinas = res.data;
          this.makePagination(res.meta, res.links);
          this.isLoading = false;
        });
    },*/
    voltarPagina() {
      this.$router.go(-1);
    }
  }
};
</script>