<template>
  <div class="admin_disciplinas">
    <v-container grid-list-md>
      <v-layout row wrap>
        <v-flex lg2>
          <v-btn small round @click="voltarPagina">
            <v-icon>keyboard_arrow_left</v-icon>
          </v-btn>
        </v-flex>
        <v-flex lg10>
          <h1 class="display-1 grey--text" lg10>Administrar Disciplinas</h1>
        </v-flex>
      </v-layout>
    </v-container>
    <v-autocomplete
      v-model="model"
      :items="cursos"
      label="Selecione um curso para listar as disciplinas"
      :item-text="curso_campus"
      prepend-icon="school"
      :loading="isLoading"
      :search-input.sync="search"
      return-object
    >
      <template v-slot:append-outer>
        <v-slide-x-reverse-transition mode="out-in"></v-slide-x-reverse-transition>
      </template>
    </v-autocomplete>
    <v-container v-if="disciplinas && disciplinas.length" grid-list-md text-xs-center>
      <v-toolbar flat color="white">
        <v-toolbar-title>Disciplinas</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn color="primary" dark class="text-xs-right" v-on="on">Novo Registro</v-btn>
      </v-toolbar>
      <v-data-table :headers="headers" :items="disciplinas" class="elevation-1">
        <template v-slot:items="disciplinas">
          <td class="text-xs-center">{{ disciplinas.item.periodo }}</td>
          <td class="text-xs-left">{{ disciplinas.item.nome }}</td>
          <td class="text-xs-left">{{ disciplinas.item.codigo }}</td>
          <td class="text-xs">
            <div>
              <v-btn color="warning" fab small dark @click="editDisciplina(disciplinas.item)">
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn color="error" fab small dark>
                <v-icon>delete</v-icon>
              </v-btn>
            </div>
          </td>
        </template>
      </v-data-table>
    </v-container>
    <v-container text-xs-center v-else-if="disciplinas && disciplinas.length == 0">
      <h2 class="red--text headline">Não há disciplinas registradas para este curso</h2>
      <v-btn color="primary" dark class="mb-2" v-on="on">Registrar Disciplina</v-btn>
    </v-container>

    <v-dialog v-model="dialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Editar Disciplina</span>
        </v-card-title>

        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12 lg12>
                <v-text-field v-model="editedDisciplina.nome" label="Nome da Disciplina"></v-text-field>
              </v-flex>
              <v-flex xs12 lg12>
                <v-text-field v-model="editedDisciplina.codigo" label="Código da Disciplina"></v-text-field>
              </v-flex>
              <v-flex xs12 lg12>
                <v-text-field v-model="editedDisciplina.periodo" label="Período (apenas números)"></v-text-field>
              </v-flex>
              <v-flex xs12 lg12>
                <v-text-field disabled v-model="editedDisciplina.id" label="Disciplina   ID"></v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click="dialog=false">Cancelar</v-btn>
          <v-btn color="blue darken-1" flat @click="save(editedDisciplina.id)">Salvar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- alert sucesso -->
    <v-snackbar v-model="snackbar" :timeout="4000" top :color="snackbar_color">
      <span>{{snackbar_message}}</span>
      <v-btn flat color="white" @click="snackbar = false">Fechar</v-btn>
    </v-snackbar>
  </div>
</template> 

<script>
import EditForm from "./EditDisciplinaModal";
export default {
  components: { EditForm },
  data() {
    return {
      snackbar: false,
      snackbar_message: "",
      snackbar_color: "",
      alert: false,
      curso_id: null,
      dialog: false,
      isLoading: false,
      cursos: {
        id: "",
        nome: "",
        campus: ""
      },
      model: null,
      search: null,
      disciplinas: null,
      editedDisciplina: {
        nome: "",
        codigo: "",
        periodo: 0,
        id: 0
      },
      //tabela disciplinas
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
    this.fetchCursos();
  },
  watch: {
    search(val) {
      this.isLoading = true;
      // seleciona o id do curso
      if (this.model != null) {
        this.curso_id = this.model.id;
      }

      fetch(`api/disciplinas/${this.curso_id}`)
        .then(res => res.json())
        .then(res => {
          this.disciplinas = res.data;
        })
        .catch(err => {
          console.log(err);
        })
        .finally(() => (this.isLoading = false));
    }
  },
  methods: {
    voltarPagina() {
      this.$router.go(-1);
    },
    curso_campus: cursos => {
      return cursos.nome + " — " + cursos.campus;
    },
    changeLocale() {
      this.$vuetify.lang.current = "pt";
    },
    fetchCursos() {
      var page_url = "api/cursos";
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.cursos = res.data;
        });
    },
    getDisciplinasFromCurso() {
      fetch(`api/disciplinas/${this.curso_id}`)
        .then(res => res.json())
        .then(res => {
          this.disciplinas = res.data;
        });
    },
    editDisciplina(disciplina) {
      this.dialog = true;
      this.editedDisciplina = Object.assign({}, disciplina);
    },
    save(id) {
      fetch("api/disciplinas/" + id, {
        method: "PUT",
        body: JSON.stringify(this.editedDisciplina),
        headers: {
          "Content-Type": "application/json"
        }
      })
        .then(res => res.json())
        .then(res => {
          this.getDisciplinasFromCurso();
          this.snackbar_color = "success";
          this.snackbar_message = "Disciplina editada com sucesso!";
          this.snackbar = true;
          this.dialog = false;
        })
        .catch(err => {
          this.getDisciplinasFromCurso();
          this.snackbar_color = "error";
          this.snackbar_message = "Um erro ocorreu";
          this.snackbar = true;
          this.dialog = false;
          console.log(err);
        });
    }
  }
};
</script>