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
  emits: ['sucesso', 'fechar', 'eventoExcluido'],
  props: {
    excluir: Number,
  },
  data() {
    return {
    };
  },
  methods: {
    async excluirEvento() {
      if (this.excluir) {
        try {
          await axios.delete(`/api/eventos/${this.excluir}`);
          this.$emit('sucesso', 'Evento e dados relacionados excluídos com sucesso!');
          this.$emit('eventoExcluido');
          this.$emit('fechar');
        } catch (error) {
          console.error('Erro ao excluir o evento:', error);
        }
      }
    },
    fecharModal() {
      this.$emit('fechar');
    },
  },
};
</script>