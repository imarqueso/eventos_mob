<template>
  <div class="modal">
    <div class="modal-conteudo">
      <h2>Adicionar Participante</h2>
      <form @submit.prevent="salvarParticipante">
        <label>
          <span>Nome*:</span>
          <input v-model="participante.nome" id="nome" type="text"/>
          <span v-if="nomeParticipanteErro" class="erro">{{ nomeParticipanteErro }}</span>
        </label>
        <label>
          <span>CPF*:</span>
          <input v-model="participante.cpf" id="cpf" type="text"/>
          <span v-if="cpfParticipanteErro" class="erro">{{ cpfParticipanteErro }}</span>
        </label>
        <label>
          <span>E-mail*:</span>
          <input v-model="participante.email" id="email" type="email"/>
          <span v-if="emailParticipanteErro" class="erro">{{ emailParticipanteErro }}</span>
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
  emits: ['fechar', 'sucesso'],
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
      nomeParticipanteErro: '',
      cpfParticipanteErro: '',
      emailParticipanteErro: '',
      sucesso: '',
    };
  },
  methods: {
    async salvarParticipante() {
      this.nomeParticipanteErro = '';
      this.cpfParticipanteErro = '';
      this.emailParticipanteErro = '';

      try {      
        await axios.post('/api/participantes', this.participante);
        this.$emit('sucesso', 'Participante adicionado com sucesso!'); 
        this.fecharModal();  // Chame fecharModal diretamente
        this.participante = { nome: '', cpf: '', email: '', evento_id: this.eventoId }; // Reseta o participante
      } catch (error) {
        if (error.response && error.response.status === 422) {
          const errors = error.response.data.errors;
          
          if (errors.nome) {
            this.nomeParticipanteErro = errors.nome[0];
          }
          if (errors.cpf) {
            this.cpfParticipanteErro = errors.cpf[0];
          }
          if (errors.email) {
            this.emailParticipanteErro = errors.email[0];
          }
        } else {
          alert('Erro ao adicionar participante. Tente novamente.');
        }
      }
    },
    fecharModal() {
      this.$emit('fechar');
    },
  },
};
</script>
