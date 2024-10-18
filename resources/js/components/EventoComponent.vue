<template>
  <div>
    <h1>Eventos</h1>
    <button @click="abrirModal">Cadastrar Novo</button>
    <div>
      <input v-model="filterNome" placeholder="Nome:" />
      <input v-model="filterDataInicio" type="date" placeholder="Data Inícial:" />
      <input v-model="filterDataFim" type="date" placeholder="Data Final:" />
      <button @click="pesquisar">Pesquisar</button>
      <button @click="carregarEventos">Limpar Filtro</button>
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
            <button @click="confirmarExclusao(evento.id)">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>
    <ModalCadastrarEvento
      :modalAberto="modalAberto"
      :onClose="fecharModal"
      :onEventAdded="carregarEventos"
    />
  </div>
</template>

<script>
import axios from 'axios';
import ModalCadastrarEvento from './ModalCadastrarEvento.vue';

export default {
  components: {
    ModalCadastrarEvento,
  },
  data() {
    return {
      eventos: [],
      filterNome: '',
      filterDataInicio: '',
      filterDataFim: '',
      modalAberto: false,
    };
  },
  methods: {
    async carregarEventos() {
      const response = await axios.get('/api/eventos');
      this.eventos = response.data;
    },
    async pesquisar() {

      if (!this.filterNome || !this.filterDataInicio || !this.filterDataFim) {
        alert('Todos os campos do filtro são obrigatórios.');
        return;
      }

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
    calcularDuracao(dataInicio, dataFim) {
      const dtInicio = new Date(dataInicio);
      const dtFim = new Date(dataFim);
      const diffTime = Math.abs(dtFim - dtInicio);
      return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    },
    async editarEvento(id) {
      // lógica para editar evento
    },
    async confirmarExclusao(id) {
      if (confirm('Realmente deseja remover este registro?')) {
        await axios.delete(`/api/eventos/${id}`);
        this.pesquisar();
      }
    },
      abrirModal() {
      this.modalAberto = true;
    },
    fecharModal() {
      this.modalAberto = false;
    },
  },
  async mounted() {
    this.carregarEventos();
  },
};
</script>
