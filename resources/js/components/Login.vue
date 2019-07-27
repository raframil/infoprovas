<template>
  <div class="enviar_prova">
    <h1 class="display-2 grey--text">Entrar</h1>
    <v-container>
      <v-layout row>
        <v-flex xs12 sm6 offset-sm3>
          <v-card>
            <v-card-title primary-title>
              <div>
                <h3 class="headline mb-0">
                  Faça seu Login
                  <span class="body-2">Para conseguir enviar provas :)</span>
                </h3>
              </div>
            </v-card-title>
            <v-card-text>
              <v-container>
                <v-alert v-if="serverError" :value="true" type="error">{{ serverError }}</v-alert>

                <v-form ref="form" v-model="valid" lazy-validation>
                  <v-layout row>
                    <v-flex xs12>
                      <v-text-field
                        name="email"
                        label="Email"
                        id="email"
                        v-model="usuario.email"
                        type="email"
                        :rules="emailRules"
                        required
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-layout row>
                    <v-flex xs12>
                      <v-text-field
                        :append-icon="show ? 'visibility' : 'visibility_off'"
                        name="password"
                        label="Senha"
                        id="password"
                        v-model="usuario.password"
                        :type="show ? 'text' : 'password'"
                        :rules="[passwordRules.min, passwordRules.required]"
                        @click:append="show = !show"
                        required
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-layout row>
                    <v-flex xs12 text-xs-center>
                      <v-btn color="primary" @click="login()" :loading="loginLoading">Entrar</v-btn>
                    </v-flex>
                  </v-layout>
                </v-form>
                <v-layout row>
                  <v-flex xs12 text-xs-center mt-5>
                    <h3>Não possui uma conta?</h3>
                    <v-btn flat :to="{name: 'registrar'}" color="purple">Criar Conta</v-btn>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
      <!-- alert sucesso -->
      <v-snackbar v-model="snackbar" :timeout="4000" top :color="snackbar_color">
        <span>{{snackbar_message}}</span>
        <v-btn flat color="white" @click="snackbar = false">Fechar</v-btn>
      </v-snackbar>
    </v-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loginLoading: false,
      serverError: "",
      usuario: {
        email: "",
        password: ""
      },
      show: false,
      snackbar: false,
      snackbar_message: "",
      snackbar_color: "",
      // Validações de formulário
      valid: true,
      emailRules: [
        v => !!v || "Email deve ser preenchido",
        v =>
          /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "O email digitado não é válido"
      ],
      passwordRules: {
        required: v => !!v || "Senha deve ser preenchida",
        min: v => (v && v.length >= 6) || "Senha inválida"
      }
    };
  },
  methods: {
    reset() {
      this.$refs.form.reset();
    },
    resetValidation() {
      this.$refs.form.resetValidation();
    },
    login() {
      this.loginLoading = true;
      this.$store
        .dispatch("retrieveToken", {
          email: this.usuario.email,
          password: this.usuario.password
        })
        .then(response => {
          this.loginLoading = false;
          this.$router.push({ name: "enviar_prova" });
        })
        .catch(error => {
          this.loginLoading = false;
          this.serverError = error.response.data.error;
        });
    }
  }
};
</script>