<template>
  <nav>
    <!-- toolbar --->
    <v-toolbar color="indigo" dark fixed app>
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title>UaiProva</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-toolbar-items offset-y v-if="!loggedIn" class="hidden-sm-and-down">
        <v-btn
          small
          flat
          color="white"
          v-for="item in items_toolbar"
          :key="item.icon"
          :to="item.path"
        >
          <v-icon left>{{item.icon}}</v-icon>
          <span>{{item.title}}</span>
        </v-btn>
      </v-toolbar-items>
      <v-menu class="hidden-md-and-up" v-if="!loggedIn">
        <v-toolbar-side-icon slot="activator">
          <v-icon>more_vert</v-icon>
        </v-toolbar-side-icon>
        <v-list>
          <v-list-tile v-for="item in items_toolbar" :key="item.icon" :to="item.path">
            <v-list-tile-content>
              <v-list-tile-title>{{ item.title }}</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-menu>
      <v-toolbar-items offset-y v-if="loggedIn">
        <v-btn small flat slot="activator" color="white" :to="{name: 'logout'}">
          <v-icon left>exit_to_app</v-icon>
          <span>Sair</span>
        </v-btn>
      </v-toolbar-items>
    </v-toolbar>
    <!-- drawer -->
    <v-navigation-drawer fixed v-model="drawer" app>
      <v-divider></v-divider>
      <v-list>
        <v-list-tile v-for="item in items" :key="item.title" :to="item.path">
          <v-list-tile-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-tile-action>

          <v-list-tile-content>
            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>

      <v-list v-if="getRole == 'admin'">
        <v-subheader>
          <v-icon class="mr-3">widgets</v-icon>Administrador
        </v-subheader>
        <v-list-tile v-for="item in items_adm" :key="item.title" :to="item.path">
          <v-list-tile-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-tile-action>

          <v-list-tile-content>
            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>
  </nav>
</template>


<script>
export default {
  data() {
    return {
      role: null,
      token: null,
      drawer: null,
      items: [
        { title: "Home", icon: "home", path: "/home" },
        { title: "Cursos", icon: "school", path: "/cursos" },
        { title: "Sobre", icon: "question_answer", path: "/sobre" },
        {
          title: "Enviar Prova",
          icon: "cloud_upload",
          path: "/enviar_prova"
        }
      ],
      items_adm: [
        {
          title: "Administrar Disciplinas",
          icon: "folder_open",
          path: "/admin/disciplinas"
        },
        {
          title: "Administrar Professores",
          icon: "people",
          path: "/admin/professores"
        }
      ],
      items_toolbar: [
        { title: "Login", icon: "near_me", path: "/login" },
        { title: "Registrar", icon: "assignment_ind", path: "/registrar" }
      ],
      right: null
    };
  },
  mounted() {},
  methods: {},
  computed: {
    loggedIn() {
      return this.$store.getters.loggedIn;
    },
    getRole() {
      return this.$store.getters.userRole;
    }
  }
};
</script>