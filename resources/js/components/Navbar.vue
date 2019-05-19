<template>
  <nav>
    <!-- toolbar --->
    <v-toolbar color="indigo" dark fixed app>
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title>UaiProva</v-toolbar-title>
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
    </v-navigation-drawer>
  </nav>
</template>


<script>
export default {
  data() {
    return {
      drawer: null,
      items: [
        { title: "Home", icon: "dashboard", path: "/home" },
        { title: "Cursos", icon: "school", path: "/cursos" },
        { title: "Sobre", icon: "question_answer", path: "/sobre" },
        {
          title: "CRUD Disciplinas",
          icon: "question_answer",
          path: "/admin/disciplinas"
        }
      ],
      right: null
    };
  }
};
</script>