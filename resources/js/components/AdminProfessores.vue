<template>
  <div class="admin_profs">
    <v-container grid-list-md>
      <v-layout row wrap>
        <v-flex lg1>
          <v-btn small round @click="voltarPagina">
            <v-icon>keyboard_arrow_left</v-icon>
          </v-btn>
        </v-flex>
        <v-flex lg11>
          <h1 class="display-1 grey--text" lg10>Administrar Professores</h1>
        </v-flex>
      </v-layout>
    </v-container>

    <v-card>
      <v-card-title class="headline">
        Professores
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Procurar"
          single-line
          hide-details
        ></v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="professores"
        :search="search"
        :loading="loadingDataTable"
        loading-text="Carregando... Aguarde um pouco"
      >
        <template v-slot:items="professores">
          <td class="text-xs-left">{{ professores.item.nome }}</td>
          <td class="text-xs-left">{{ professores.item.email }}</td>
          <td class="text-xs-center">
            <div>
              <v-btn color="warning" fab small dark @click="editProfessor(professores.item)">
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn
                color="error"
                fab
                small
                dark
                @click="deleteDialog(professores.item.id, professores.item.nome)"
              >
                <v-icon>delete</v-icon>
              </v-btn>
            </div>
          </td>
        </template>
      </v-data-table>
    </v-card>

    <!-- create dialog -->
    <v-dialog v-model="create_dialog" max-width="500px">
      <v-form ref="create_professor_form">
        <v-card>
          <v-card-title>
            <span class="headline">Adicionar Professor</span>
            <p>Preencha com os dados corretos. E-mail é opcional.</p>
          </v-card-title>

          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 lg12>
                  <v-text-field
                    label="Nome do Professor"
                    v-model="createProfessor.nome"
                    :rules="nomeProfessorRules"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 lg12>
                  <v-text-field label="E-mail do Professor" v-model="createProfessor.email"></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="grey" flat @click="cancel_create_professor()">Cancelar</v-btn>
            <v-btn color="teal" flat @click="submit_create_professor()">Salvar</v-btn>
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
          que deseja excluir o professor
          <span
            class="indigo--text"
          >{{deletedProfessor.nome}}?</span>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" flat="flat" @click="delete_dialog = false">Cancelar</v-btn>
          <v-btn color="red accent-3" flat="flat" @click="confirmarRemocao()">Confirmar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- edit dialog -->
    <v-dialog v-model="edit_dialog" max-width="500px" persistent>
      <v-card>
        <v-card-title>
          <span class="headline">Editar Professor</span>
        </v-card-title>

        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12 lg12>
                <v-text-field
                  v-model="editedProfessor.nome"
                  label="Nome do Professor"
                  :rules="nomeProfessorRules"
                ></v-text-field>
              </v-flex>
              <v-flex xs12 lg12>
                <v-text-field v-model="editedProfessor.email" label="E-mail do Professor"></v-text-field>
              </v-flex>
              <v-flex xs12 lg12 class="d-none">
                <v-text-field disabled v-model="editedProfessor.id"></v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" flat @click="edit_dialog=false">Cancelar</v-btn>
          <v-btn color="teal" flat @click="updateProf(editedProfessor.id)">Salvar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- snackbar -->
    <v-snackbar v-model="snackbar" :timeout="4000" top :color="snackbar_color">
      <span>{{snackbar_message}}</span>
      <v-btn flat color="white" @click="snackbar = false">Fechar</v-btn>
    </v-snackbar>
  </div>
</template>

<script>
export default {
  data() {
    return {
      // Criar Professor
      create_dialog: false,
      createProfessor: {
        nome: "",
        email: ""
      },
      nomeProfessorRules: [v => !!v || "Nome do professor deve ser preenchido"],
      // Fim Criar Professor

      //Deletar Professor
      delete_dialog: false,
      deletedProfessor: {
        nome: "",
        id: 0
      },
      // Fim Deletar Professor

      // Editar Professor
      edit_dialog: false,
      editedProfessor: {
        nome: "",
        email: "",
        id: 0
      },

      // Snackbar
      snackbar: false,
      snackbar_message: "",
      snackbar_color: "",
      loadingDataTable: false,
      search: "",
      headers: [
        { text: "Nome", value: "nome" },
        { text: "E-mail", value: "email" },
        { text: "Ação", value: "action", align: "center" }
      ],
      professores: [
        {
          id: 0,
          nome: "",
          email: ""
        }
      ]
    };
  },
  created() {
    this.getProfessores();
  },
  methods: {
    editProfessor(professor) {
      this.edit_dialog = true;
      this.editedProfessor = Object.assign({}, professor);
    },
    updateProf(id) {
      fetch("api/professores/" + id, {
        method: "PUT",
        body: JSON.stringify(this.editedProfessor),
        headers: {
          "Content-Type": "application/json",
          Authorization: "Bearer " + localStorage.getItem("access_token")
        }
      })
        .then(res => res.json())
        .then(res => {
          this.getProfessores();
          this.snackbar_color = "success";
          this.snackbar_message = "Professor editado com sucesso!";
          this.snackbar = true;
          this.edit_dialog = false;
        })
        .catch(err => {
          this.getProfessores();
          this.snackbar_color = "error";
          this.snackbar_message = "Um erro ocorreu " + err;
          this.snackbar = true;
          this.edit_dialog = false;
        });
    },
    confirmarRemocao() {
      this.deleteProfessor(this.deletedProfessor.id);
    },
    deleteDialog(id, nome) {
      this.delete_dialog = true;
      this.deletedProfessor.nome = nome;
      this.deletedProfessor.id = id;
    },
    deleteProfessor(id) {
      fetch(`api/professores/${id}`, {
        method: "delete",
        headers: new Headers({
          Authorization: "Bearer " + localStorage.getItem("access_token")
        })
      })
        .then(res => res.json())
        .then(res => {
          if (res.success == "false") {
            this.snackbar_color = "error";
            this.snackbar_message = res.message;
            this.snackbar = true;
            this.delete_dialog = false;
          } else {
            this.getProfessores();
            this.snackbar_color = "success";
            this.snackbar_message = "Professor excluído com sucesso!";
            this.snackbar = true;
            this.delete_dialog = false;
          }
        })
        .catch(err => console.log(err));
    },
    submit_create_professor() {
      if (this.$refs.create_professor_form.validate()) {
        fetch("api/professores", {
          method: "POST",
          body: JSON.stringify(this.createProfessor),
          headers: {
            Authorization: "Bearer " + localStorage.getItem("access_token"),
            "Content-Type": "application/json"
          }
        })
          .then(res => res.json())
          .then(res => {
            if (res.error) {
              this.snackbar_color = "error";
              this.snackbar_message =
                "Um erro ocorreu ao adicionar o professor " + res.error;
              this.snackbar = true;
              this.create_dialog = false;
            }
            this.$refs.create_professor_form.reset();
            this.getProfessores();
            this.snackbar_color = "success";
            this.snackbar_message = "Professor adicionado com sucesso!";
            this.snackbar = true;
            this.create_dialog = false;
          })
          .catch(err => {
            this.snackbar_color = "error";
            this.snackbar_message =
              "Um erro ocorreu ao adicionar o professor " + err;
            this.snackbar = true;
            this.create_dialog = false;
            console.log(err);
          });
      }
    },
    cancel_create_professor() {
      this.create_dialog = false;
      this.$refs.create_professor_form.reset();
    },
    voltarPagina() {
      this.$router.go(-1);
    },
    getProfessores() {
      this.loadingDataTable = true;
      var page_url = "api/professores";
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.loadingDataTable = false;
          this.professores = res.data;
        });
    }
  }
};
</script>