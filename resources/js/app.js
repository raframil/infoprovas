import Vue from "vue";
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import axios from "axios";
import VueAxios from "vue-axios";
// Translation provided by Vuetify (typescript)
import pt from "vuetify/es5/locale/pt";

import { store } from "./store.js";

Vue.use(VueRouter);
Vue.use(Vuetify, {
    lang: {
        locales: { pt },
        current: "pt"
    }
});
Vue.use(VueAxios, axios);

// Rotas da aplicacao
const router = new VueRouter({
    //mode: "history",
    routes: [
        {
            path: "*",
            redirect: "home"
        },
        {
            path: "/sobre",
            component: require("./components/Sobre.vue").default
        },
        {
            path: "/home",
            name: "home",
            component: require("./components/Home.vue").default
        },
        {
            path: "/cursos",
            component: require("./components/Cursos.vue").default
        },
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
        },
        {
            path: "/admin/disciplinas",
            name: "admin_disciplinas",
            props: true,
            meta: {
                //requiresAuth: true,
                is_admin: true
            },
            component: require("./components/AdminDisciplinas.vue").default
        },
        {
            path: "/admin/professores",
            name: "admin_professores",
            meta: {
                //requiresAuth: true,
                is_admin: true
            },
            component: require("./components/AdminProfessores.vue").default
        },
        {
            path: "/enviar_prova",
            name: "enviar_prova",
            meta: {
                requiresAuth: true
            },
            component: require("./components/EnviarProva.vue").default
        },
        {
            path: "/registrar",
            name: "registrar",
            component: require("./components/Registro.vue").default
        },
        {
            path: "/login",
            name: "login",
            component: require("./components/Login.vue").default
        },
        {
            path: "/logout",
            name: "logout",
            component: require("./components/Logout.vue").default
        }
    ]
});

Vue.router = router;
Vue.use(require("@websanova/vue-auth"), {
    auth: require("@websanova/vue-auth/drivers/auth/bearer.js"),
    http: require("@websanova/vue-auth/drivers/http/axios.1.x.js"),
    router: require("@websanova/vue-auth/drivers/router/vue-router.2.x.js")
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.getters.loggedIn) {
            next({
                name: "login",
                params: { nextUrl: to.fullPath }
            });
        } else {
            if (to.matched.some(record => record.meta.is_admin)) {
                if (user === "admin") {
                    next();
                } else {
                    next({ name: "home" });
                }
            } else {
                next();
            }
        }
    } else if (to.matched.some(record => record.meta.is_admin)) {
        /* verifica se o usuario esta acessando um rota adm
        se estiver, primeiro verifica se existe um token
        se houver, verificar se o token e valido tentando pegar os dados do usuario
        se não for válido, manda o usuário para logout
        se for válido, verifica se o usuário possui a role = admin */
        if (!store.getters.loggedIn) {
            next({
                name: "login",
                params: { nextUrl: to.fullPath }
            });
        } else {
            let user = store.getters.userRole;
            store.dispatch("getUserData").then(response => {
                if (response.data.error) {
                    if (response.data.error == "TOKEN_INVALID") {
                        router.push({ name: "logout" });
                    } else if (response.data.error == "TOKEN_EXPIRED") {
                        router.push({ name: "logout" });
                    }
                }
            });
            if (user === "admin") {
                next();
            } else {
                next({ name: "home" });
            }
        }
    } else {
        next();
    }
});

// Declaração dos componentes
Vue.component("main-app", require("./components/App.vue").default);
Vue.component("navbar", require("./components/Navbar.vue").default);
Vue.component(
    "footer-component",
    require("./components/FooterComponent.vue").default
);
//Vue.component("buscar-cursos", require("./components/BuscarCurso.vue").default);
Vue.component(
    "pagina-carregando",
    require("./components/PaginaCarregando.vue").default
);

// Inicialização
new Vue({
    el: "#app",
    store: store,
    router: router
});
