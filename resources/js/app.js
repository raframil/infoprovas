import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";

Vue.use(VueRouter);
Vue.use(Vuetify);

// Rotas
const routes = [
    { path: "/sobre", component: require("./components/Sobre.vue").default },
    { path: "/home", component: require("./components/Home.vue").default },
    { path: "/cursos", component: require("./components/Cursos.vue").default },
    {
        path: "/disciplinas/:curso_id",
        name: "disciplinas",
        props: true,
        component: require("./components/Disciplinas.vue").default
    },
    {
        path: "/provas/:disciplina_id",
        name: "provas",
        props: true,
        component: require("./components/Provas.vue").default
    }
];

const router = new VueRouter({
    routes
});

// Declaração dos componentes
Vue.component("navbar", require("./components/Navbar.vue").default);
Vue.component(
    "footer-component",
    require("./components/FooterComponent.vue").default
);
Vue.component("buscar-cursos", require("./components/BuscarCurso.vue").default);
Vue.component(
    "pagina-carregando",
    require("./components/PaginaCarregando.vue").default
);

// Inicialização
const app = new Vue({
    el: "#app",
    router
});
