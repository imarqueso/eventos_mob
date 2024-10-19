<template>
  <div class="modal">
    <h2>Registrar Presença</h2>
    <p><strong>Nome:</strong> {{ participante.nome }}</p>
    <p><strong>CPF:</strong> {{ participante.cpf }}</p>

    <form @submit.prevent="registrarPresenca">
      <label for="data">Data</label>
      <input v-model="presenca.data" id="data" type="date" required />

      <button type="submit">Salvar</button>
      <button type="button" @click="$emit('fechar')">Cancelar</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    participante: Object,
  },
  data() {
    return {
      presenca: {
        data: '',
        participante_id: this.participante ? this.participante.id : null,
      },
    };
  },
  methods: {
    async registrarPresenca() {
      try {
        await axios.post('/api/presencas', this.presenca);
        alert('Presença registrada com sucesso!');
        this.$emit('presencaRegistrada');
        this.$emit('fechar');
      } catch (error) {
        alert('Erro ao registrar presença.');
      }
    },
  },
};
</script>

<style scoped>
.modal {
  background: white;
  padding: 20px;
  border-radius: 10px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1000;
}
</style>
