<template>
  <div class="enviar_prova">
    <h1 class="display-2 grey--text">Criar Conta</h1>
    <v-container>
      <v-layout row>
        <v-flex xs12 sm6 offset-sm3>
          <v-card>
            <v-card-title primary-title>
              <div>
                <h3 class="headline mb-0">Cadastre-se preenchendo todo os dados</h3>
              </div>
            </v-card-title>
            <v-card-text>
              <v-container>
                <v-alert v-if="statusRegistro == true" :value="true" type="success">
                  Usuário registrado com sucesso! Redirecionando...
                  <v-progress-circular indeterminate color="white"></v-progress-circular>
                </v-alert>
                <v-form ref="form" v-model="valid" lazy-validation>
                  <v-layout row>
                    <v-flex xs12>
                      <v-text-field
                        name="name"
                        label="Nome "
                        id="name"
                        v-model="usuario.name"
                        type="text"
                        :rules="nameRules"
                        required
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
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
                        hint="Pelo menos 6 caracteres"
                        @click:append="show = !show"
                        required
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-layout row>
                    <v-flex xs12>
                      <v-text-field
                        :append-icon="show ? 'visibility' : 'visibility_off'"
                        name="confirmPassword"
                        label="Confirmar Senha"
                        id="confirmPassword"
                        v-model="usuario.confirmPassword"
                        :type="show ? 'text' : 'password'"
                        :rules="[passwordRules.min, passwordRules.required, passwordConfirmationRule]"
                        @click:append="show = !show"
                        required
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-layout row>
                    <v-flex xs12 text-xs-center>
                      <vue-recaptcha
                        ref="recaptcha"
                        class="g-recaptcha"
                        @verify="onVerify"
                        @expired="onExpired"
                        :sitekey="sitekey"
                      ></vue-recaptcha>
                    </v-flex>
                  </v-layout>
                  <v-layout row>
                    <v-flex xs12 text-xs-center>
                      <v-btn
                        color="primary"
                        @click="register()"
                        :loading="registerLoading"
                      >Registrar</v-btn>
                    </v-flex>
                  </v-layout>
                </v-form>
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

<style>
.g-recaptcha {
  display: inline-block;
}
</style>

<script>
import VueRecaptcha from "vue-recaptcha";
export default {
  components: {
    "vue-recaptcha": VueRecaptcha
  },
  data() {
    return {
      captchaResponse: null,
      sitekey: "6Ldwq7AUAAAAAK3_fxLEGjEBKoYQ86kWSdAqOmFT",
      registerLoading: false,
      statusRegistro: false,
      usuario: {
        name: "",
        email: "",
        password: "",
        confirmPassword: ""
      },
      show: false,
      snackbar: false,
      snackbar_message: "",
      snackbar_color: "",
      // Validações de formulário
      valid: true,
      nameRules: [v => !!v || "Nome deve ser preenchido"],
      emailRules: [
        v => !!v || "Email deve ser preenchido",
        v =>
          /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "O email digitado não é válido"
      ],
      passwordRules: {
        required: v => !!v || "A senha deve ser preenchida",
        min: v =>
          (v && v.length >= 6) || "A senha deve possuir pelo menos 6 caracteres"
      }
    };
  },
  computed: {
    passwordConfirmationRule() {
      return () =>
        this.usuario.password === this.usuario.confirmPassword ||
        "Senha e confirmar senha devem ser iguais";
    }
  },
  methods: {
    // G Recaptcha
    onVerify: function(response) {
      this.captchaResponse = response;
    },
    onExpired: function() {
      this.snackbar_message = "Captcha Expirado";
      this.snackbar_color = "red";
      this.snackbar = true;
    },
    resetRecaptcha() {
      this.$refs.recaptcha.reset(); // Direct call reset method
    },
    // Fim G Recaptcha
    reset() {
      this.$refs.form.reset();
    },
    resetValidation() {
      this.$refs.form.resetValidation();
    },
    register() {
      if (this.$refs.form.validate()) {
        if (this.captchaResponse === null) {
          this.snackbar_message = "Captcha Inválido";
          this.snackbar_color = "red";
          this.snackbar = true;
          return Promise.reject("Captcha inválido");
        }
        this.registerLoading = true;
        this.$store
          .dispatch("register", {
            name: this.usuario.name,
            email: this.usuario.email,
            password: this.usuario.password,
            token: this.captchaResponse
          })
          .then(response => {
            if (response.data.email) {
              this.snackbar_message = "O email já está sendo utilizado";
              this.snackbar_color = "red";
              this.snackbar = true;
            } else if (response.data.password) {
              this.snackbar_message =
                "A senha deve possuir pelo menos 6 caracteres";
              this.snackbar_color = "red";
              this.snackbar = true;
            } else if (response.data.token) {
              this.registerLoading = false;
              this.statusRegistro = true;
              setTimeout(() => this.$router.push({ name: "login" }), 3000);
            }
          })
          .catch(error => {
            this.registerLoading = false;
            console.log(error);
            this.snackbar_message = error;
            this.snackbar_color = "red";
            this.snackbar = true;
          });
      }
    }
  }
};
</script>