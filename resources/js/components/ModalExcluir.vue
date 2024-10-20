<template>
  <div class="modal">
    <div class="modal-conteudo">
      <h2>Realmente deseja remover este registro?</h2>
      <div class="btn-box">
        <button @click="excluirEvento">Sim</button>
        <button @click="fecharModal">Cancelar</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  emits: ['sucesso', 'fechar', 'registroExcluido'],
  props: {
    excluir: Number,
    rota: String,
  },
  data() {
    return {
    };
  },
  methods: {
    async excluirEvento() {
      if (this.excluir) {
        try {
          await axios.delete(this.rota);
          this.$emit('sucesso', 'Registro(s) excluído(s) com sucesso!');
          this.$emit('registroExcluido');
          this.$emit('fechar');
        } catch (error) {
          this.$emit('registroExcluido');
          if (error.response && error.response.status === 422) {
              const errors = error.response.data.errors;
            
            if (errors.data) {
              this.dataPresencaErro = errors.data[0];
            }
          } else {
            console.error('Erro ao excluir o evento:', error);
            alert('Erro ao excluir o evento. Tente novamente.');
          }
        }
      }
    },
    fecharModal() {
      this.$emit('fechar');
    },
  },
};
</script>