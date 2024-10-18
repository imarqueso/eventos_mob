<template>
  <div v-if="modalAberto" class="modal">
    <div class="modal-conteudo">
      <h2>Cadastrar Novo Evento</h2>
      <form @submit.prevent="cadastrarEvento">
        <div>
          <label for="nome">Nome:</label>
          <input v-model="novoEvento.nome" />
        </div>
        <div>
          <label for="dataInicio">Data Início:</label>
          <input v-model="novoEvento.datainicio" type="date"/>
        </div>
        <div>
          <label for="dataFim">Data Fim:</label>
          <input v-model="novoEvento.datafim" type="date"/>
        </div>
        <button type="submit">Salvar</button>
        <button type="button" @click="fecharModal">Cancelar</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    modalAberto: {
      type: Boolean,
      required: true,
    },
    onClose: {
      type: Function,
      required: true,
    },
    onEventAdded: {
      type: Function,
      required: true,
    },
  },
  data() {
    return {
      novoEvento: {
        nome: '',
        datainicio: '',
        datafim: '',
      },
    };
  },
  methods: {
    async cadastrarEvento() {

      if (!this.novoEvento.nome || !this.novoEvento.datainicio || !this.novoEvento.datafim) {
        alert('Todos os campos são obrigatórios!');
        return;
      }

      try {
        await axios.post('/api/eventos', this.novoEvento);
        this.onEventAdded(); 
        this.fecharModal();
        alert('Evento cadastrado com sucesso!');
      } catch (error) {
        if (error.response && error.response.data && error.response.data.error) {
          console.error('Erro ao cadastrar evento:', error.response.data.error); 
          alert(error.response.data.error);
        } else {
          console.error('Erro ao cadastrar evento:', error);
          alert('Erro ao cadastrar evento. Tente novamente.'); 
        }
      }
    },
    fecharModal() {
      this.onClose();
      this.resetarNovoEvento();
    },
    resetarNovoEvento() {
      this.novoEvento = {
        nome: '',
        datainicio: '',
        datafim: '',
      };
    },
  },
};
</script>

<style>
.modal {
  display: flex;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  justify-content: center;
  align-items: center;
}

.modal-conteudo {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
}
</style>
