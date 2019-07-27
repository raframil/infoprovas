<template>
  <div class="admin_disciplinas">
    <v-container grid-list-md>
      <v-layout row wrap>
        <v-flex lg1>
          <v-btn small round @click="voltarPagina">
            <v-icon>keyboard_arrow_left</v-icon>
          </v-btn>
        </v-flex>
        <v-flex lg11>
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
        <v-btn color="primary" dark class="text-xs-right" @click="create_dialog=true">Novo Registro</v-btn>
      </v-toolbar>
      <v-data-table :headers="headers" :items="disciplinas" hide-actions class="elevation-1">
        <template v-slot:items="disciplinas">
          <td class="text-xs-center">{{ disciplinas.item.periodo }}</td>
          <td class="text-xs-left">{{ disciplinas.item.nome }}</td>
          <td class="text-xs-left">{{ disciplinas.item.codigo }}</td>
          <td class="text-xs">
            <div>
              <v-btn color="warning" fab small dark @click="editDisciplina(disciplinas.item)">
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn
                color="error"
                fab
                small
                dark
                @click="deleteDialog(disciplinas.item.id, disciplinas.item.nome)"
              >
                <v-icon>delete</v-icon>
              </v-btn>
            </div>
          </td>
        </template>
      </v-data-table>
      <div class="text-xs-center pt-2">
        <v-pagination
          v-model="pagination.current_page"
          :length="pagination.last_page"
          @input="onPageChange"
        ></v-pagination>
      </div>
    </v-container>
    <v-container text-xs-center v-else-if="disciplinas && disciplinas.length == 0">
      <h2 class="red--text headline">Não há disciplinas registradas para este curso</h2>
      <v-btn color="primary" dark class="mb-2" @click="create_dialog=true">Registrar Disciplina</v-btn>
    </v-container>

    <!-- edit dialog -->
    <v-dialog v-model="edit_dialog" max-width="500px" persistent>
      <v-card>
        <v-card-title>
          <span class="headline">Editar Disciplina</span>
        </v-card-title>

        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12 lg12>
                <v-text-field
                  v-model="editedDisciplina.nome"
                  label="Nome da Disciplina"
                  :rules="nomeRules"
                ></v-text-field>
              </v-flex>
              <v-flex xs12 lg12>
                <v-text-field
                  v-model="editedDisciplina.codigo"
                  label="Código da Disciplina"
                  :rules="codigoRules"
                ></v-text-field>
              </v-flex>
              <v-flex xs12 lg12>
                <v-text-field
                  v-model="editedDisciplina.periodo"
                  label="Período (apenas números)"
                  :rules="periodoRules"
                ></v-text-field>
              </v-flex>
              <v-flex xs12 lg12 class="d-none">
                <v-text-field disabled v-model="editedDisciplina.id"></v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" flat @click="edit_dialog=false">Cancelar</v-btn>
          <v-btn color="teal" flat @click="save(editedDisciplina.id)">Salvar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- create dialog -->
    <v-dialog v-model="create_dialog" max-width="500px">
      <v-form ref="create_disc_form">
        <v-card>
          <v-card-title>
            <span class="headline">Criar Disciplina</span>
          </v-card-title>

          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 lg12>
                  <v-text-field
                    label="Nome da Disciplina"
                    v-model="createDisciplina.nome"
                    :rules="nomeRules"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 lg12>
                  <v-text-field
                    label="Código da Disciplina"
                    v-model="createDisciplina.codigo"
                    :rules="codigoRules"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 lg12>
                  <v-text-field
                    label="Período (apenas números)"
                    v-model="createDisciplina.periodo"
                    :rules="periodoRules"
                  ></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="grey" flat @click="cancel_create()">Cancelar</v-btn>
            <v-btn color="teal" flat @click="submit_create()">Salvar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>

    <!-- dialog confirmação delete -->
    <v-dialog v-model="delete_dialog" max-width="350">
      <v-card>
        <v-card-title class="headline red accent-3 white--text">Confirmar Exclusão</v-card-title>

        <v-card-text>
          Você tem
          <strong>certeza</strong>
          que deseja excluir a disciplina
          <span
            class="indigo--text"
          >{{deletedDisciplina.nome}}?</span>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" flat="flat" @click="delete_dialog = false">Cancelar</v-btn>
          <v-btn color="red accent-3" flat="flat" @click="confirmarRemocao()">Confirmar</v-btn>
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
      create_dialog: false,
      delete_dialog: false,
      edit_dialog: false,
      isLoading: false,
      confirmar_remocao: false,
      cursos: [
        {
          id: "",
          nome: "",
          campus: ""
        }
      ],
      model: null,
      search: null,
      disciplinas: null,
      createDisciplina: {
        nome: "",
        codigo: "",
        periodo: 0
      },
      editedDisciplina: {
        nome: "",
        codigo: "",
        periodo: 0,
        id: 0
      },
      deletedDisciplina: {
        nome: "",
        id: 0
      },
      pagination: {},
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
      ],
      // validações do formulário
      nomeRules: [
        v => !!v || "O campo nome deve ser preenchido",
        v =>
          (v && v.length <= 100) ||
          "O campo nome não deve exceder 100 caracteres"
      ],
      codigoRules: [
        v => !!v || "O campo código deve ser preenchido",
        v =>
          (v && v.length <= 10) || "O campo nome não deve exceder 10 caracteres"
      ],
      periodoRules: [v => !!v || "O campo período deve ser preenchido"]
    };
  },
  created() {
    this.fetchCursos();
  },
  watch: {
    create_dialog() {
      this.$refs.create_disc_form.resetValidation();
    },
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
          this.makePagination(res.meta, res.links);
        })
        .catch(err => {
          console.log(err);
        })
        .finally(() => (this.isLoading = false));
    }
  },
  methods: {
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
    deleteDialog(id, nome) {
      this.delete_dialog = true;
      this.deletedDisciplina.nome = nome;
      this.deletedDisciplina.id = id;
    },
    confirmarRemocao() {
      this.deleteDisciplina(this.deletedDisciplina.id);
    },
    deleteDisciplina(id) {
      fetch(`api/disciplinas/${id}`, {
        method: "delete",
        headers: new Headers({
          Authorization: "Bearer " + localStorage.getItem("access_token")
        })
      })
        .then(res => res.json())
        .then(res => {
          this.getDisciplinasFromCurso();
          this.snackbar_color = "success";
          this.snackbar_message = "Disciplina excluída com sucesso!";
          this.snackbar = true;
          this.confirmar_remocao = false;
          this.delete_dialog = false;
        })
        .catch(err => console.log(err));
    },
    submit_create() {
      if (this.$refs.create_disc_form.validate()) {
        //adiciona o curso_id ao objeto json
        this.createDisciplina.curso_id = this.curso_id;
        fetch("api/disciplinas", {
          method: "POST",
          body: JSON.stringify(this.createDisciplina),
          headers: {
            Authorization: "Bearer " + localStorage.getItem("access_token"),
            "Content-Type": "application/json"
          }
        })
          .then(res => res.json())
          .then(res => {
            if (res.error) {
              this.getDisciplinasFromCurso();
              this.snackbar_color = "error";
              this.snackbar_message = "Um erro ocorreu";
              this.snackbar = true;
              this.create_dialog = false;
              console.log(res.error);
            }
            this.$refs.create_disc_form.reset();
            this.getDisciplinasFromCurso();
            this.snackbar_color = "success";
            this.snackbar_message = "Disciplina criada com sucesso!";
            this.snackbar = true;
            this.create_dialog = false;
          })
          .catch(err => {
            this.getDisciplinasFromCurso();
            this.snackbar_color = "error";
            this.snackbar_message = "Um erro ocorreu";
            this.snackbar = true;
            this.create_dialog = false;
            console.log(err);
          });
      }
    },
    cancel_create() {
      this.create_dialog = false;
      this.$refs.create_disc_form.reset();
    },
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
    onPageChange() {
      this.getDisciplinasFromCurso();
    },
    getDisciplinasFromCurso() {
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
    },
    editDisciplina(disciplina) {
      this.edit_dialog = true;
      this.editedDisciplina = Object.assign({}, disciplina);
    },
    //update
    save(id) {
      fetch("api/disciplinas/" + id, {
        method: "PUT",
        body: JSON.stringify(this.editedDisciplina),
        headers: {
          "Content-Type": "application/json",
          Authorization: "Bearer " + localStorage.getItem("access_token")
        }
      })
        .then(res => res.json())
        .then(res => {
          this.getDisciplinasFromCurso();
          this.snackbar_color = "success";
          this.snackbar_message = "Disciplina editada com sucesso!";
          this.snackbar = true;
          this.edit_dialog = false;
        })
        .catch(err => {
          this.getDisciplinasFromCurso();
          this.snackbar_color = "error";
          this.snackbar_message = "Um erro ocorreu";
          this.snackbar = true;
          this.edit_dialog = false;
          console.log(err);
        });
    }
  }
};
</script>