<template>
  <div class="enviar_prova">
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
                item-text="nome"
                return-object
                v-model="createProva.professor"
                :rules="professorRules"
                required
              ></v-autocomplete>
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
              <input type="file" id="file-upload" style="display:none" @change="onFileChange">
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
              <v-btn color="primary" dark @click="uploadFile">
                Enviar
                <v-icon right dark>send</v-icon>
              </v-btn>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card>
    </v-form>

    <!-- snackbar -->
    <v-snackbar v-model="snackbar" :timeout="4000" top :color="snackbar_color">
      <span>{{snackbar_message}}</span>
      <v-btn flat color="white" @click="snackbar = false">Fechar</v-btn>
    </v-snackbar>
  </div>
</template>

<script>
export default {
  data: () => ({
    // Snackbar
    snackbar: false,
    snackbar_message: "",
    snackbar_color: "",
    // Variáveis
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
    cursoRules: [v => !!v || "Curso deve ser preenchido"],
    discRules: [v => !!v || "Disciplina deve ser preenchida"],
    professorRules: [v => !!v || "Professor deve ser preenchido"],
    tipoProvaRules: [v => !!v || "Tipo de prova deve ser selecionado"],
    anoAplicacaoRules: [
      v => !!v || "Ano de aplicação da prova deve ser preenchido",
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
      fetch("api/professores")
        .then(res => res.json())
        .then(res => {
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
      if (file.type != "application/pdf") {
        this.snackbar_color = "error";
        this.snackbar_message =
          "O arquivo não é um PDF. Por favor, envie outro";
        this.snackbar = true;
      } else {
        this.snackbar_color = "success";
        this.snackbar_message = "Arquivo válido!";
        this.snackbar = true;
        // Seleciona o arquivo
        this.selectedFile = file;
      }
    },
    uploadFile() {
      if (this.$refs.form.validate()) {
        if (this.selectedFile.name == "") {
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
          body: formData
        })
          .then(res => res.json())
          .then(res => {
            this.snackbar_color = "success";
            this.snackbar_message = "Prova enviada com sucesso";
            this.snackbar = true;

            console.log(res);
          })
          .catch(error => {
            this.snackbar_color = "error";
            this.snackbar_message = "Um erro ocorreu";
            this.snackbar = true;
            console.log(res.error);
          });
      }
    }
  }
};
</script>