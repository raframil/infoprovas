<template>
  <div class="sobre">
    <h1 class="display-2 grey--text">Sobre</h1>
    <v-container class="my-5">
      <p
        style="text-align:justify"
      >O UaiProva foi criado com o propósito de democratizar o conhecimento. Uma plataforma de alunos para alunos, dispondo de provas anteriores de diversas disciplinas e cursos para estudo. As provas aqui compartilhadas foram humildemente cedidas por alunos ou professores. Nenhum dado referente aos envios será exibido publicamente.</p>

      <div>
        <div class="text-xs-center mt-5">FAQ</div>
        <v-expansion-panel v-model="panel" expand>
          <v-expansion-panel-content>
            <template v-slot:header>
              <div>Por que o envio de provas apenas no formato PDF?</div>
            </template>
            <v-card>
              <v-card-text
                class="grey lighten-3"
              >PDF é o melhor formato para exibição e estabelecendo um padrão, tornamos as coisas mais fáceis e acessíveis aos usuários e desenvolvedores.</v-card-text>
            </v-card>
          </v-expansion-panel-content>
          <v-expansion-panel-content>
            <template v-slot:header>
              <div>Só tenho a prova fisicamente, e agora?</div>
            </template>
            <v-card>
              <v-card-text class="grey lighten-3">
                Existem diversas alternativas, você pode escanear e salvar em PDF. Você pode utilizar um
                <a
                  href="https://play.google.com/store/apps/details?id=com.intsig.camscanner&hl=pt_BR"
                >aplicativo</a> para isso ou algum
                <a
                  href="https://pt.pdf24.org/onlineConverter.html"
                >conversor online</a>.
              </v-card-text>
            </v-card>
          </v-expansion-panel-content>
          <v-expansion-panel-content>
            <template v-slot:header>
              <div>Por que preciso fazer login para enviar uma prova?</div>
            </template>
            <v-card>
              <v-card-text class="grey lighten-3">Para evitarmos spam e pessoas mal-intencionadas.</v-card-text>
            </v-card>
          </v-expansion-panel-content>
          <v-expansion-panel-content>
            <template v-slot:header>
              <div>Meus dados serão exibidos se eu enviar uma prova?</div>
            </template>
            <v-card>
              <v-card-text
                class="grey lighten-3"
              >Nenhum dado será exibido, garantindo anonimato dos contribuintes.</v-card-text>
            </v-card>
          </v-expansion-panel-content>
          <v-expansion-panel-content>
            <template v-slot:header>
              <div>Como posso entrar em contato?</div>
            </template>
            <v-card>
              <v-card-text class="grey lighten-3">
                Você pode enviar e-mail para
                <a>uaiprova@gmail.com</a>.
              </v-card-text>
            </v-card>
          </v-expansion-panel-content>
          <v-expansion-panel-content>
            <template v-slot:header>
              <div>Quem fez o sistema?</div>
            </template>
            <v-card>
              <v-card-text class="grey lighten-3">
                O sistema foi desenvolvido pelo aluno de SIN
                <a
                  href="https://linkedin.com/in/rafael-framil-747583ba/"
                >Rafael Framil</a>.
              </v-card-text>
            </v-card>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </div>
    </v-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      panel: [false, false]
    };
  }
};
</script>
