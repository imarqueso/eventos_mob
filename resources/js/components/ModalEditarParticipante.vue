<template>
  <div class="modal">
    <div class="modal-conteudo">
      <h2>Editar Participante</h2>
      <form @submit.prevent="salvarParticipante">
         <label>
          <span>Nome*:</span>
          <input
          type="text"
          id="nome"
          v-model="participante.nome"/>
          <span v-if="nomeParticipanteEditErro" class="erro">{{ nomeParticipanteEditErro }}</span>
        </label>
        <label>
          <span>CPF*:</span>
          <input
          type="text"
          id="cpf"
          v-model="participante.cpf"/>
          <span v-if="cpfParticipanteEditErro" class="erro">{{ cpfParticipanteEditErro }}</span>
        </label>
        <label>
          <span>E-mail*:</span>
          <input
          type="email"
          id="email"
          v-model="participante.email"/>
          <span v-if="emailParticipanteEditErro" class="erro">{{ emailParticipanteEditErro }}</span>
        </label>
        <div class="btn-box">
          <button type="submit">Salvar</button>
          <button type="button" @click="fecharModal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  emits: ['sucesso'],
  props: {
    participante: Object,
  },
  data() {
    return {
      nomeParticipanteEditErro: '',
      cpfParticipanteEditErro: '',
      emailParticipanteEditErro: '',
      sucesso: '',
    };
  },
  methods: {
    async salvarParticipante() {
      this.nomeParticipanteEditErro = '';
      this.cpfParticipanteEditErro = '';
      this.emailParticipanteEditErro = '';

      try {
        await axios.put(`/api/participantes/${this.participante.id}`, 
        this.participante);
        this.$emit('sucesso', 'Participante editado com sucesso!');
        this.$emit('participanteAtualizado'); 
        this.fecharModal();
        this.nomeParticipanteEditErro = '';
        this.cpfParticipanteEditErro = '';
        this.emailParticipanteEditErro = '';
      } catch (error) {
        this.$emit('participanteAtualizado');
        if (error.response && error.response.status === 422) {
          const errors = error.response.data.errors;
          
          if (errors.nome) {
            this.nomeParticipanteEditErro = errors.nome[0];
          }
          if (errors.cpf) {
            this.cpfParticipanteEditErro = errors.cpf[0];
          }
          if (errors.email) {
            this.emailParticipanteEditErro = errors.email[0];
          }
        } else {
          alert('Erro ao editar participante. Tente novamente.');
        }
      }
    },
    fecharModal() {
      this.$emit('fechar');
    },
  },
};
</script>
