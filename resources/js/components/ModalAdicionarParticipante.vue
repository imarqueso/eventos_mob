<template>
  <div class="modal">
    <h2>Adicionar Participante</h2>
    <form @submit.prevent="salvarParticipante">
      <label for="nome">Nome</label>
      <input v-model="participante.nome" id="nome" type="text" required />

      <label for="cpf">CPF</label>
      <input v-model="participante.cpf" id="cpf" type="text" required />

      <label for="email">E-mail</label>
      <input v-model="participante.email" id="email" type="email" required />

      <button type="submit">Salvar</button>
      <button type="button" @click="$emit('fechar')">Cancelar</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    eventoId: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      participante: {
        nome: '',
        cpf: '',
        email: '',
        evento_id: this.eventoId,
      },
    };
  },
  methods: {
    async salvarParticipante() {
      try {
        await axios.post('/api/participantes', this.participante);
        alert('Participante adicionado com sucesso!');
        this.$emit('participanteAdicionado'); 
        this.$emit('fechar');
      } catch (error) {
        alert('Erro ao adicionar participante.');
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
