<template>
  <div class="modal">
    <div class="modal-conteudo">
      <h2>Registrar Presença</h2>
      <p><strong>Nome:</strong> {{ participante.nome }}</p>
      <p><strong>CPF:</strong> {{ participante.cpf }}</p>
      <form @submit.prevent="registrarPresenca">
        <label>
          <span>Data*:</span>
          <input v-model="presenca.data" id="data" type="date" required />
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