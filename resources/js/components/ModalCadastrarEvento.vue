<template>
  <div v-if="modalAberto" class="modal">
    <div class="modal-conteudo">
      <h2>Cadastrar Novo Evento</h2>
      <form @submit.prevent="cadastrarEvento">
        <label>
          <span>Nome*:</span>
          <input v-model="novoEvento.nome" />
        </label>
        <label>
          <span>Data Inicio*:</span>
          <input v-model="novoEvento.datainicio" type="date"/>
        </label>
        <label>
          <span>Data Final*:</span>
          <input v-model="novoEvento.datafim" type="date"/>
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