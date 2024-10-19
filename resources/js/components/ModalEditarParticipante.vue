<template>
  <div class="modal">
    <h2>Editar Participante</h2>
    <form @submit.prevent="salvarParticipante">
      <label for="nome">Nome:</label>
      <input
        type="text"
        id="nome"
        v-model="participante.nome"
        placeholder="Nome"
        required
      />

      <label for="email">Email:</label>
      <input
        type="email"
        id="email"
        v-model="participante.email"
        placeholder="Email"
        required
      />

      <label for="cpf">CPF:</label>
      <input
        type="text"
        id="cpf"
        v-model="participante.cpf"
        placeholder="CPF"
        required
      />

      <button type="submit">Salvar</button>
      <button type="button" @click="fecharModal">Cancelar</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    participante: Object,
  },
  methods: {
    async salvarParticipante() {
      try {
        await axios.put(`/api/participantes/${this.participante.id}`, this.participante);
        alert('Participante editado com sucesso!');
        this.$emit('participanteAtualizado'); 
        this.fecharModal();
      } catch (error) {
        alert('Erro ao editar participante.');
      }
    },
    fecharModal() {
      this.$emit('fechar');
    },
  },
};
</script>
