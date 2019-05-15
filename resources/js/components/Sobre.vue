<template>
  <div class="sobre">
    <h1 class="display-2 grey--text">Sobre</h1>

    <v-container class="my-5">
      <p
        class="left-align"
      >O UnificaProvas foi criado com o propósito de democratizar o conhecimento. Uma plataforma de alunos para alunos, dispondo de provas anteriores de diversas disciplinas e cursos para estudo. As provas aqui compartilhadas foram humildemente cedidas por alunos ou professores. Nenhum dado referente ao envio será gravado ou exibido.</p>
      <div class="divider"></div>
      <h4 class="left-align">FAQ</h4>

      <ul>
        <li>
          <i class="material-icons">filter_drama</i>
          <strong>Por que o envio de provas apenas no formato PDF?</strong>
        </li>
        <li>PDF é o melhor formato para exibição e estabelecendo um padrão, tornando mais fácil aos usuários e desenvolvedores.</li>

        <li>
          <strong>
            <i class="material-icons">monochrome_photos</i> Só tenho foto da prova! E agora?
          </strong>
        </li>
        <li>
          Existem diversas alternativas, você pode escanear e salvar em PDF. Você pode utilizar um
          <a
            href="https://play.google.com/store/apps/details?id=com.intsig.camscanner&hl=pt_BR"
          >aplicativo</a> para isso ou algum
          <a href="https://pt.pdf24.org/onlineConverter.html">conversor online</a>.
        </li>
      </ul>
    </v-container>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Sobre Component mounted.");
  }
};
</script>
