<template>
  <div class="enviar_prova" v-if="user_id">
    <h1 class="display-2 grey--text">Enviar Prova</h1>

    <v-form ref="form" v-model="valid" lazy-validation>
      <v-card class="my-5">
        <v-card-title primary-title>
          <div>
            <h3 class="headline mb-0">Envio de Prova</h3>
            <div>
              Para enviar sua prova, preencha
              <span class="red--text">todos</span> os campos corretamente e submeta um
              <b>único</b> arquivo PDF.
            </div>
          </div>
        </v-card-title>
        <v-container>
          <v-alert v-if="errorMessage" :value="true" type="error">{{ errorMessage }}</v-alert>
          <v-layout row wrap>
            <v-flex lg12 xs12>
              <v-autocomplete
                lg4
                v-model="model_curso"
                :items="cursos"
                label="Selecione um curso para listar suas disciplinas"
                :item-text="curso_campus"
                prepend-icon="school"
                :search-input.sync="search"
                :rules="cursoRules"
                return-object
                required
              ></v-autocomplete>
            </v-flex>

            <v-flex lg12 xs12>
              <v-autocomplete
                label="Selecione uma disciplina pelo nome/código"
                :items="disciplinas"
                :item-text="disciplina_codigo"
                prepend-icon="folder_open"
                return-object
                v-model="createProva.disciplina"
                :rules="discRules"
                required
              ></v-autocomplete>
            </v-flex>

            <v-flex lg12 xs12>
              <v-autocomplete
                label="Professor"
                prepend-icon="person"
                :items="professores"
                :loading="isLoadingProfessores"
                item-text="nome"
                return-object
                v-model="createProva.professor"
                :rules="professorRules"
                required
              ></v-autocomplete>
            </v-flex>

            <v-flex lg12 xs12 class="mb-4 mt-3">
              <p>Não encontrou? Você pode adicionar um novo professor no botão abaixo</p>
              <v-btn small color="green" class="white--text" @click="create_dialog=true">
                <v-icon>add</v-icon>Adicionar Professor
              </v-btn>
            </v-flex>

            <v-flex lg4 md4 xs12 class="pr-2 pl-2">
              <v-select
                :items="prova_tipos"
                item-text="descricao"
                item-value="id"
                v-model="createProva.tipo_prova"
                :rules="tipoProvaRules"
                label="Tipo de Prova"
              ></v-select>
            </v-flex>
            <v-flex lg4 md4 xs12 class="pr-2 pl-2">
              <v-text-field
                type="number"
                min="0"
                value=" "
                :counter="4"
                label="Ano de aplicação da Prova"
                v-model="createProva.ano"
                :rules="anoAplicacaoRules"
                required
              ></v-text-field>
            </v-flex>
            <v-flex lg4 md4 xs12 class="pr-2 pl-2">
              <v-text-field
                type="number"
                min="1"
                value="1"
                :readonly="false"
                label="Período"
                v-model="createProva.periodo"
                :rules="periodoRules"
                required
              ></v-text-field>
            </v-flex>

            <v-flex xs12 text-xs-center class="mt-3">
              <v-btn color="red" dark class="white--text my-10" @click.native="openFileDialog">
                Carregar Prova
                <v-icon right dark>cloud_upload</v-icon>
              </v-btn>
              <input type="file" id="file-upload" style="display:none" @change="onFileChange" />
            </v-flex>
            <v-flex text-xs-center v-if="selectedFile.name != ''">
              Arquivo:
              <span class="green--text">{{selectedFile.name}}</span>
            </v-flex>
            <v-flex text-xs-center v-if="selectedFile.name == ''">
              Arquivo:
              <span class="red--text">Você ainda não selecionou nada</span>
            </v-flex>

            <v-flex xs12 text-xs-right class="mt-5">
              <v-btn color="primary" dark @click="uploadFile" :loading="loadingBotaoEnviar">
                Enviar
                <v-icon right dark>send</v-icon>
              </v-btn>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card>
    </v-form>

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

    <!-- snackbar -->
    <v-snackbar v-model="snackbar" :timeout="4000" top :color="snackbar_color">
      <span>{{snackbar_message}}</span>
      <v-btn flat color="white" @click="snackbar = false">Fechar</v-btn>
    </v-snackbar>
  </div>
  <v-alert :value="true" type="error" v-else-if="alertaSessaoInvalida == true">
    {{ errorMessage }}
    <v-progress-circular indeterminate color="white"></v-progress-circular>
  </v-alert>
  <pagina-carregando v-else></pagina-carregando>
</template>

<script>
export default {
  data: () => ({
    create_dialog: false,
    createProfessor: {
      nome: "",
      email: ""
    },
    isLoadingProfessores: false,
    alertaSessaoInvalida: false,
    errorMessage: null,
    loadingBotaoEnviar: false,
    // Snackbar
    snackbar: false,
    snackbar_message: "",
    snackbar_color: "",
    // Variáveis
    user_id: 0,
    curso_id: 0,
    model_curso: null,
    cursos: null,
    search: null,
    createProva: {
      upload: null,
      ano: "",
      periodo: "",
      disciplina: null,
      professor: null,
      tipo_prova: null
    },
    provaUpload: {
      upload: null,
      ano: 0,
      periodo: 0,
      disciplina: 0,
      professor: 0,
      tipo_prova: 0
    },
    professores: [
      {
        id: 0,
        nome: ""
      }
    ],
    prova_tipos: [
      {
        id: 0,
        descricao: ""
      }
    ],
    disciplinas: [
      {
        id: 0,
        nome: "",
        codigo: ""
      }
    ],
    selectedFile: {
      name: ""
    },
    // Validações de formulário
    valid: true,
    emailRules: [
      v =>
        /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
        "O email digitado não é válido"
    ],
    nomeProfessorRules: [v => !!v || "Nome do professor deve ser preenchido"],
    cursoRules: [v => !!v || "Curso deve ser preenchido"],
    discRules: [v => !!v || "Disciplina deve ser preenchida"],
    professorRules: [v => !!v || "Professor deve ser preenchido"],
    tipoProvaRules: [v => !!v || "Tipo de prova deve ser selecionado"],
    anoAplicacaoRules: [
      v => !!v || "Ano de aplicação da prova deve ser preenchido",
      v => (v && v.length > 3) || "O ano deve possuir 4 caracters (AAAA)",
      v => (v && v.length <= 4) || "O ano não deve exceder 4 caracteres"
    ],
    periodoRules: [
      v => !!v || "Período deve ser preenchido",
      v => (v && v.length <= 2) || "O período não deve exceder 2 caracteres"
    ]
  }),
  created() {
    this.getTipoProvas();
    this.fetchCursos();
    this.getProfessores();
    this.getUser();
  },
  watch: {
    search(val) {
      // seleciona o id do curso
      if (this.model_curso != null) {
        this.curso_id = this.model_curso.id;
      }
      fetch(`api/disciplinas/all/${this.curso_id}`)
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
    fetchCursos() {
      fetch("api/cursos")
        .then(res => res.json())
        .then(res => {
          this.cursos = res.data;
        });
    },
    getTipoProvas() {
      fetch("api/prova_tipos")
        .then(res => res.json())
        .then(res => {
          this.prova_tipos = res.data;
        });
    },
    getProfessores() {
      this.isLoadingProfessores = true;
      fetch("api/professores")
        .then(res => res.json())
        .then(res => {
          this.isLoadingProfessores = false;
          this.professores = res.data;
        });
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
    curso_campus: cursos => {
      return cursos.nome + " — " + cursos.campus;
    },
    disciplina_codigo: disciplinas => {
      return disciplinas.nome + " — " + disciplinas.codigo;
    },

    validate() {
      if (this.$refs.form.validate()) {
        this.snackbar = true;
      }
    },
    reset() {
      this.$refs.form.reset();
    },
    resetValidation() {
      this.$refs.form.resetValidation();
    },

    // Métodos de Upload
    openFileDialog() {
      document.getElementById("file-upload").click();
    },
    onFileChange(e) {
      var self = this;
      var file = e.target.files[0];
      var FileSize = file.size / 1024 / 1024; // in MB
      if (file.type != "application/pdf") {
        this.snackbar_color = "error";
        this.snackbar_message =
          "O arquivo não é um PDF. Por favor, envie outro";
        this.snackbar = true;
      } else if (FileSize > 10) {
        this.snackbar_color = "error";
        this.snackbar_message = "O arquivo carregado excedeu o limite de 10MB";
        this.snackbar = true;
      } else {
        this.snackbar_color = "success";
        this.snackbar_message = "Arquivo carregado com sucesso";
        this.snackbar = true;
        // Seleciona o arquivo
        this.selectedFile = file;
      }
    },
    uploadFile() {
      if (this.$refs.form.validate()) {
        this.loadingBotaoEnviar = true;
        if (this.selectedFile.name == "") {
          this.loadingBotaoEnviar = false;
          this.snackbar_color = "error";
          this.snackbar_message = "Por favor, selecione um arquivo";
          this.snackbar = true;
          throw new Error("Arquivo não selecionado");
        }
        const params = {
          upload: this.selectedFile,
          ano: this.createProva.ano,
          periodo: this.createProva.periodo,
          disciplina_id: this.createProva.disciplina.id,
          professor_id: this.createProva.professor.id,
          tipo_prova_id: this.createProva.tipo_prova
        };

        const formData = new FormData();
        Object.keys(params).forEach(k => {
          formData.append(k, params[k]);
        });

        fetch("api/provas/upload", {
          method: "POST",
          body: formData,
          headers: new Headers({
            Authorization: "Bearer " + localStorage.getItem("access_token")
          })
        })
          .then(res => res.json())
          .then(res => {
            if (res.error) {
              this.loadingBotaoEnviar = false;
              this.snackbar_color = "error";
              this.snackbar_message = res.error;
              this.snackbar = true;
            } else {
              this.loadingBotaoEnviar = false;
              this.snackbar_color = "success";
              this.snackbar_message = "Prova enviada com sucesso";
              this.snackbar = true;
              this.reset();
              this.resetValidation();
            }
          })
          .catch(error => {
            this.loadingBotaoEnviar = false;
            this.snackbar_color = "error";
            this.snackbar_message = "Um erro ocorreu";
            this.snackbar = true;
            console.log(res.error);
          });
      }
    },
    getUser() {
      this.$store.dispatch("getUserData").then(response => {
        if (response.data.error) {
          if (response.data.error == "TOKEN_INVALID") {
            this.alertaSessaoInvalida = true;
            this.errorMessage =
              "Token Inválido. Por favor, realize login novamente  ";
            setTimeout(() => this.$router.push({ name: "logout" }), 3000);
          } else if (response.data.error == "TOKEN_EXPIRED") {
            this.alertaSessaoInvalida = true;
            this.errorMessage =
              "Sua sessão expirou. Por favor, realize login novamente  ";
            setTimeout(() => this.$router.push({ name: "logout" }), 3000);
          } else {
            setTimeout(() => this.$router.push({ name: "logout" }), 3000);
          }
        }
        if (response.data.status == "success" && response.data.data) {
          this.user_id = response.data.data.id;
        }
      });
    }
  }
};
</script>