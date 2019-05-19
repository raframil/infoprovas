<template>
  <v-dialog v-model="show" max-width="500px">
    <v-card>
      <h2>Disciplina - {{ }}</h2>
      <v-card-actions>
        <v-btn color="primary" flat @click.stop="show=false">Fechar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: {
    value: Boolean,
    editedDiscId: null
  },
  computed: {
    show: {
      get() {
        console.log(this.value);
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      }
    }
  }
};
</script>