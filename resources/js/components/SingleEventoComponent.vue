<template>
  <div :class="{'main': classAtivo}">
    <div :class="{'top': classAtivo}">
      <h1>Detalhes do Evento</h1>
    </div>
    <div :class="{'abasBox': classAtivo}">
       <button @click="trocarAba('dados')">Dados do Evento</button>
       <button @click="trocarAba('participantes')">Participantes</button>
    </div>

    <div v-if="abaAtiva === 'dados'" :class="{'abaDados': classAtivo}">
      <h2>Editar Evento</h2>
      <form @submit.prevent="salvarEvento">
        <label>
          <span>Nome*:</span>
          <input v-model="evento.nome" id="nome" type="text" required />
        </label>
        <label>
          <span>Data Inicial*:</span>
          <input v-model="evento.datainicio" id="datainicio" type="date" required />
        </label>
        <label>
          <span>Data Final*:</span>
          <input v-model="evento.datafim" id="datafim" type="date" required />
        </label>
        <button type="submit">Salvar</button>
      </form>
    </div>

    <div v-if="abaAtiva === 'participantes'" :class="{'abaParticipantes': classAtivo}">
      <h2>Participantes</h2>
      <button @click="abrirModalAdicionarParticipante">Adicionar Participante</button>

      <table>
        <thead>
          <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>E-mail</th>
            <th>% Participação</th>
            <th>Editar</th>
            <th>Excluir</th>
            <th>Adc. Presença</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="participante in participantes" :key="participante.id">
            <td>{{ participante.nome }}</td>
            <td>{{ participante.cpf }}</td>
            <td>{{ participante.email }}</td>
            <td>{{ participante.porcentagem_presenca.toFixed(2) }}%</td>
            <td>
              <button @click="editarParticipante(participante.id)">Editar</button>
            </td>
            <td>
              <button @click="confirmarExclusaoParticipante(participante.id)">Excluir</button>
            </td>
            <td>
              <button @click="abrirModalPresenca(participante)">Adc. Presença</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <ModalAdicionarParticipante
      v-if="modalAdicionarAberto"
      :eventoId="evento.id"
      @fechar="fecharModalAdicionarParticipante"
      @participanteAdicionado="buscarEvento"
    />

    <ModalEditarParticipante
      v-if="modalEditarAberto"
      :participante="participanteParaEditar"
      @fechar="fecharModalEditarParticipante"
      @participanteAtualizado="buscarEvento"
    />

    <ModalPresenca
      v-if="modalPresencaAberto"
      :participante="participanteParaPresenca"
      @fechar="fecharModalPresenca"
      @presencaRegistrada="buscarEvento"
    />
  </div>
</template>

<script>
import axios from 'axios';
import ModalAdicionarParticipante from './ModalAdicionarParticipante.vue';
import ModalEditarParticipante from './ModalEditarParticipante.vue';
import ModalPresenca from './ModalPresenca.vue';

export default {
  components: {
    ModalAdicionarParticipante,
    ModalEditarParticipante,
    ModalPresenca,
  },
  props: ['id'],
  data() {
    return {
      abaAtiva: 'dados',
      evento: {
        nome: '',
        datainicio: '',
        datafim: '',
      },
      classAtivo: true,
      participantes: [],
      modalAdicionarAberto: false,
      modalEditarAberto: false,
      modalPresencaAberto: false,
      participanteParaEditar: null,
      participanteParaPresenca: null,
    };
  },
  methods: {
    trocarAba(aba) {
      this.abaAtiva = aba;
      this.modalAdicionarAberto = false;
      this.modalEditarAberto = false;
      this.modalPresencaAberto = false;
    },
    async buscarEvento() {
      const response = await axios.get(`/api/eventos/${this.$route.params.id}/mostrar`);
      this.evento = response.data;
      this.participantes = response.data.participantes;
    },
    async salvarEvento() {
      await axios.put(`/api/eventos/${this.$route.params.id}`, this.evento);
      alert('Evento atualizado com sucesso!');
    },
    abrirModalAdicionarParticipante() {
      this.modalAdicionarAberto = true;
    },
    fecharModalAdicionarParticipante() {
      this.modalAdicionarAberto = false;
    },
    editarParticipante(id) {
      this.modalEditarAberto = true;
      this.participanteParaEditar = this.participantes.find(p => p.id === id);
    },
    fecharModalEditarParticipante() {
      this.modalEditarAberto = false;
    },
    confirmarExclusaoParticipante(id) {
      if (confirm('Realmente deseja remover este participante?')) {
        axios.delete(`/api/participantes/${id}`);
        this.buscarEvento(); // Atualizar participantes após exclusão
      }
    },
    abrirModalPresenca(participante) {
      this.modalPresencaAberto = true;
      this.participanteParaPresenca = participante;
    },
    fecharModalPresenca() {
      this.modalPresencaAberto = false;
    },
  },
  mounted() {
    this.buscarEvento();
  },
};
</script>
