<template>
  <span v-if="sucesso" class="sucesso">{{ sucesso }}</span>
  <div class="main">
    <div class="top">
      <h1>Eventos</h1>
      <button @click="abrirModal">Cadastrar Novo</button>
    </div>
    <div class="filtroBox">
      <label>
        <span>Nome*:</span>
        <input v-model="filterNome" placeholder="Nome:" />
        <span v-if="nomeErro" class="erro">{{ nomeErro }}</span>
      </label>
      <label>
        <span>Data Inícial*:</span>
        <input v-model="filterDataInicio" type="date" placeholder="Data Inícial:" />
        <span v-if="dataInicioErro" class="erro">{{ dataInicioErro }}</span>
      </label>
      <label>
        <span>Data Final*:</span>
        <input v-model="filterDataFim" type="date" placeholder="Data Final:" />
        <span v-if="dataFimErro" class="erro">{{ dataFimErro }}</span>
      </label>
      <button @click="pesquisar">Pesquisar</button>
      <button @click="limparFiltro">Limpar Filtro</button>
    </div>

    <table>
      <thead>
        <tr>
          <th>Nome</th>
          <th>Data Início</th>
          <th>Data Fim</th>
          <th>Duração</th>
          <th>Editar</th>
          <th>Excluir</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="evento in eventos" :key="evento.id">
          <td>{{ evento.nome }}</td>
          <td>{{ evento.datainicio }}</td>
          <td>{{ evento.datafim }}</td>
          <td>{{ calcularDuracao(evento.datainicio, evento.datafim) }} dias</td>
          <td>
            <button @click="editarEvento(evento.id)">Editar</button>
          </td>
          <td>
            <button @click="abrirModalExcluir(evento.id)">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>

    <ModalCadastrarEvento
      :modalAberto="modalAberto"
      :onClose="fecharModal"
      :onEventAdded="carregarEventos"
    />

    <ModalExcluir
      v-if="modalExcluirAberto"
      :excluir="eventoIdParaExcluir"
      @fechar="fecharModalExcluir"
      @sucesso="exibirMensagemSucesso"
      @eventoExcluido="carregarEventos"
    />
  </div>
</template>

<script>
import axios from 'axios';
import ModalCadastrarEvento from './ModalCadastrarEvento.vue';
import ModalExcluir from './ModalExcluir.vue';

export default {
  components: {
    ModalCadastrarEvento,
    ModalExcluir,
  },
  data() {
    return {
      eventos: [],
      filterNome: '',
      filterDataInicio: '',
      filterDataFim: '',
      modalAberto: false,
      modalExcluirAberto: false,
      eventoIdParaExcluir: null,
      nomeErro: '',      
      dataInicioErro: '', 
      dataFimErro: '',  
      sucesso: '', 
    };
  },
  methods: {
    exibirMensagemSucesso(mensagem) {
      this.sucesso = mensagem;
      setTimeout(() => {
        this.sucesso = "";
      }, 4000);
    },
    async carregarEventos() {
      const response = await axios.get('/api/eventos');
      this.eventos = response.data;
    },
    async pesquisar() {
      this.nomeErro = '';
      this.dataInicioErro = '';
      this.dataFimErro = '';

      let valid = true;

      if (!this.filterNome) {
        this.nomeErro = 'O campo Nome é obrigatório.';
        valid = false;
      }
      if (!this.filterDataInicio) {
        this.dataInicioErro = 'O campo Data Inícial é obrigatório.';
        valid = false;
      }
      if (!this.filterDataFim) {
        this.dataFimErro = 'O campo Data Final é obrigatório.';
        valid = false;
      }

      if (!valid) return;

      try {
        const response = await axios.get('/api/eventos/pesquisar', {
          params: {
            nome: this.filterNome,
            datainicio: this.filterDataInicio,
            datafim: this.filterDataFim,
          },
        });
        this.eventos = response.data;
      } catch (error) {
        if (error.response && error.response.data && error.response.data.error) {
          console.error('Erro ao pesquisar eventos:', error.response.data.error);
        } else {
          console.error('Erro ao pesquisar eventos:', error);
        }
      }
    },
    limparFiltro() {
      this.filterNome = '';
      this.filterDataInicio = '';
      this.filterDataFim = '';
      this.carregarEventos();
      this.nomeErro = '';
      this.dataInicioErro = '';
      this.dataFimErro = '';
    },
    calcularDuracao(dataInicio, dataFim) {
      const dtInicio = new Date(dataInicio);
      const dtFim = new Date(dataFim);
      const diffTime = Math.abs(dtFim - dtInicio);
      return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    },
    async editarEvento(eventoId) {
      this.$router.push({ name: 'eventoSingle', params: { id: eventoId } });
    },
    abrirModal() {
      this.modalAberto = true;
    },
    fecharModal() {
      this.modalAberto = false;
    },
     fecharModalExcluir() {
      this.modalExcluirAberto = false;
      this.eventoIdParaExcluir = null;
    },
    abrirModalExcluir(id) {
      this.eventoIdParaExcluir = id;
      this.modalExcluirAberto = true;
    },
  },
  async mounted() {
    this.carregarEventos();
  },
};
</script>
