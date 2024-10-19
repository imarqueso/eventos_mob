<template>
  <span v-if="sucesso" class="sucesso">{{ sucesso }}</span>
  <div v-if="modalAberto" class="modal">
    <div class="modal-conteudo">
      <h2>Cadastrar Evento</h2>
      <form @submit.prevent="cadastrarEvento">
        <label>
          <span>Nome*:</span>
          <input v-model="novoEvento.nome" />
          <span v-if="nomeEventoErro" class="erro">{{ nomeEventoErro }}</span>
        </label>
        <label>
          <span>Data Inicio*:</span>
          <input v-model="novoEvento.datainicio" type="date"/>
          <span v-if="dataInicioEventoErro" class="erro">{{ dataInicioEventoErro }}</span>
        </label>
        <label>
          <span>Data Final*:</span>
          <input v-model="novoEvento.datafim" type="date"/>
          <span v-if="dataFimEventoErro" class="erro">{{ dataFimEventoErro }}</span>
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
      nomeEventoErro: '',
      dataInicioEventoErro: '',
      dataFimEventoErro: '',
      sucesso: '',
    };
  },
  methods: {
    async cadastrarEvento() {
      this.nomeEventoErro = '';
      this.dataInicioEventoErro = '';
      this.dataFimEventoErro = '';

      try {
        await axios.post('/api/eventos', this.novoEvento);
        this.onEventAdded(); 
        this.fecharModal();
        this.sucesso = 'Evento cadastrado com sucesso!';
        
        setTimeout(() => {
          this.sucesso = '';
        }, 4000);
      } catch (error) {
        this.onEventAdded();
        if (error.response && error.response.status === 422) {
            const errors = error.response.data.errors;
          
          if (errors.nome) {
            this.nomeEventoErro = errors.nome[0];
          }
          if (errors.datainicio) {
            this.dataInicioEventoErro = errors.datainicio[0];
          }
          if (errors.datafim) {
            this.dataFimEventoErro = errors.datafim[0];
          }
        } else {
          console.error('Erro ao cadastrar evento:', error);
          alert('Erro ao cadastrar evento. Tente novamente.');
        }
      }
    },
    fecharModal() {
      this.onClose();
      this.resetarNovoEvento();
      this.nomeEventoErro = '';
      this.dataInicioEventoErro = '';
      this.dataFimEventoErro = '';
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