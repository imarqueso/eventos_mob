<template>
  <div class="modal">
    <div class="modal-conteudo">
      <h2>Adicionar Participante</h2>
      <form @submit.prevent="salvarParticipante">
        <label>
          <span>Nome*:</span>
          <input v-model="participante.nome" id="nome" type="text" required />
        </label>
        <label>
          <span>CPF*:</span>
          <input v-model="participante.cpf" id="cpf" type="text" required />
        </label>
        <label>
          <span>E-mail*:</span>
          <input v-model="participante.email" id="email" type="email" required />
        </label>
        <div class="btn-box">
          <button type="submit">Salvar</button>
          <button type="button" @click="$emit('fechar')">Cancelar</button>
        </div>
      </form>
    </div>
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