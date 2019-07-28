import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

Vue.use(Vuex);
axios.defaults.baseURL = "18.218.220.89/api";

export const store = new Vuex.Store({
    state: {
        role: "",
        token: localStorage.getItem("access_token") || null,
        filter: "all"
    },
    getters: {
        loggedIn(state) {
            return state.token !== null;
        },
        userRole(state) {
            return state.role;
        }
    },
    mutations: {
        updateFilter(state, filter) {
            state.filter = filter;
        },
        retrieveToken(state, token) {
            state.token = token;
        },
        destroyToken(state) {
            state.token = null;
        },
        setRole(state, role) {
            state.role = role;
        }
    },
    actions: {
        getUserData(context) {
            axios.defaults.headers.common["Authorization"] =
                "Bearer " + context.state.token;

            if (context.getters.loggedIn) {
                return new Promise((resolve, reject) => {
                    axios
                        .get("/auth/user")
                        .then(response => {
                            if (response.data.error) {
                                resolve(response);
                            } else {
                                const role = response.data.data.role;
                                context.commit("setRole", role);
                                resolve(response);
                            }
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error);
                        });
                });
            }
        },
        register(context, data) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/auth/register", {
                        name: data.name,
                        email: data.email,
                        password: data.password
                    })
                    .then(response => {
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        destroyToken(context) {
            axios.defaults.headers.common["Authorization"] =
                "Bearer " + context.state.token;

            if (context.getters.loggedIn) {
                return new Promise((resolve, reject) => {
                    axios
                        .post("/auth/logout")
                        .then(response => {
                            localStorage.removeItem("access_token");
                            localStorage.removeItem("role");
                            context.commit("destroyToken");
                            resolve(response);
                            // console.log(response);
                        })
                        .catch(error => {
                            localStorage.removeItem("access_token");
                            localStorage.removeItem("role");
                            context.commit("destroyToken");
                            reject(error);
                        });
                });
            }
        },
        // login
        retrieveToken(context, credentials) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/auth/login", {
                        email: credentials.email,
                        password: credentials.password
                    })
                    .then(response => {
                        const token = response.data.token;
                        localStorage.setItem("access_token", token);
                        context.commit("retrieveToken", token);
                        resolve(response);
                    })
                    .catch(error => {
                        console.log(error);
                        reject(error);
                    });
            });
        },
        checkToken(context) {},
        updateFilter(context, filter) {
            context.commit("updateFilter", filter);
        }
    }
});
