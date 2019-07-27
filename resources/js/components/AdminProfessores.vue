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
              <v-btn color="warning" fab small dark>
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn color="error" fab small dark>
                <v-icon>delete</v-icon>
              </v-btn>
            </div>
          </td>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
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