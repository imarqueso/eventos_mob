<template>
  <div class="modal">
    <div class="modal-conteudo">
      <h2>Registrar Presença</h2>
      <p><strong>Nome:</strong> {{ participante.nome }}</p>
      <p><strong>CPF:</strong> {{ participante.cpf }}</p>
      <form @submit.prevent="registrarPresenca">
        <label>
          <span>Data*:</span>
          <input v-model="presenca.data" id="data" type="date"/>
          <span v-if="dataPresencaErro" class="erro">{{ dataPresencaErro }}</span>
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
  emits: ['sucesso', 'presencaRegistrada', 'fechar'],
  props: {
    participante: Object,
  },
  data() {
    return {
      presenca: {
        data: '',
        participante_id: this.participante ? this.participante.id : null,
      },
      dataPresencaErro: '',
    };
  },
  methods: {
    async registrarPresenca() {
      this.dataPresencaErro = '';

      try {
        await axios.post('/api/presencas', this.presenca);
        this.$emit('sucesso', 'Presença registrada com sucesso!');
        this.$emit('presencaRegistrada');
        this.$emit('fechar');
        this.dataPresencaErro = '';
      } catch (error) {
        this.$emit('presencaRegistrada');
        if (error.response && error.response.status === 422) {
            const errors = error.response.data.errors;
          
          if (errors.data) {
            this.dataPresencaErro = errors.data[0];
          }
        } else {
          console.error('Erro ao cadastrar evento:', error);
          alert('Erro ao registrar presença. Tente novamente.');
        }
      }
    },
    fecharModal() {
      this.$emit('fechar');
    },
  },
};
</script>