<template>
  <span v-if="sucesso" class="sucesso">{{ sucesso }}</span>
  <div class="main">
    <div class="top">
      <h1>Detalhes do Evento</h1>
    </div>
    <div class="abasBox">
       <button @click="trocarAba('dados')" :class="{'abaAtiva': abaAtiva === 'dados'}">Dados do Evento</button>
       <button @click="trocarAba('participantes')" :class="{'abaAtiva': abaAtiva === 'participantes'}">Participantes</button>
    </div>

    <div v-if="abaAtiva === 'dados'" class="abaDados">
      <h2>Editar Evento</h2>
      <form @submit.prevent="salvarEvento">
        <label>
          <span>Nome*:</span>
          <input v-model="evento.nome" id="nome" type="text"/>
          <span v-if="nomeEventoEditErro" class="erro">{{ nomeEventoEditErro }}</span>
        </label>
        <label>
          <span>Data Inicial*:</span>
          <input v-model="evento.datainicio" id="datainicio" type="date"/>
          <span v-if="dataInicioEventoEditErro" class="erro">{{ dataInicioEventoEditErro }}</span>
        </label>
        <label>
          <span>Data Final*:</span>
          <input v-model="evento.datafim" id="datafim" type="date"/>
          <span v-if="dataFimEventoEditErro" class="erro">{{ dataFimEventoEditErro }}</span>
        </label>
        <button type="submit">Salvar</button>
      </form>
    </div>

    <div v-if="abaAtiva === 'participantes'" class="abaParticipantes">
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
              <button @click="abrirModalExcluir(participante.id)">Excluir</button>
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
      @sucesso="exibirMensagemSucesso"
    />

    <ModalEditarParticipante
      v-if="modalEditarAberto"
      :participante="participanteParaEditar"
      @fechar="fecharModalEditarParticipante"
      @participanteAtualizado="buscarEvento"
      @sucesso="exibirMensagemSucesso"
    />

    <ModalPresenca
      v-if="modalPresencaAberto"
      :participante="participanteParaPresenca"
      @fechar="fecharModalPresenca"
      @presencaRegistrada="buscarEvento"
      @sucesso="exibirMensagemSucesso"
    />

     <ModalExcluir
      v-if="modalExcluirAberto"
      :excluir="participanteIdParaExcluir"
      :rota="rotaExclusao"
      @fechar="fecharModalExcluir"
      @sucesso="exibirMensagemSucesso"
      @registroExcluido="buscarEvento"
    />
  </div>
</template>

<script>
import axios from 'axios';
import { useRouter, useRoute } from 'vue-router';
import ModalAdicionarParticipante from './ModalAdicionarParticipante.vue';
import ModalEditarParticipante from './ModalEditarParticipante.vue';
import ModalPresenca from './ModalPresenca.vue';
import ModalExcluir from './ModalExcluir.vue';

export default {
  components: {
    ModalAdicionarParticipante,
    ModalEditarParticipante,
    ModalPresenca,
    ModalExcluir,
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
      nomeEventoEditErro: '',
      dataInicioEventoEditErro: '',
      dataFimEventoEditErro: '',
      sucesso: '',
      modalExcluirAberto: false,
      participanteIdParaExcluir: null,
      rotaExclusao: null,
    };
  },
  methods: {
    exibirMensagemSucesso(mensagem) {
      this.sucesso = mensagem;
      setTimeout(() => {
        this.sucesso = "";
      }, 4000);
    },
    trocarAba(aba) {
      this.abaAtiva = aba;
      this.modalAdicionarAberto = false;
      this.modalEditarAberto = false;
      this.modalPresencaAberto = false;
    },
    async buscarEvento() {
      const router = useRouter();
      const route = useRoute();

      try {
        const response = await axios.get(`/api/eventos/${route.params.id}/mostrar`);
        if (response.data) {
          this.evento = response.data;
        } else {
          router.replace({ name: 'NotFound' });
        }
      } catch (error) {
         if (error.response && error.response.status === 404) {
          router.replace({ name: 'NotFound' });
        } else {
          console.error('Erro ao carregar evento:', error);
        }
      }
    },
    async salvarEvento() {
      this.nomeEventoEditErro = '';
      this.dataInicioEventoEditErro = '';
      this.dataFimEventoEditErro = '';

      try {
        await axios.put(`/api/eventos/${this.$route.params.id}`, this.evento);
        this.nomeEventoEditErro = '';
        this.dataInicioEventoEditErro = '';
        this.dataFimEventoEditErro = '';
        this.sucesso = 'Evento atualizado com sucesso!';
        
        setTimeout(() => {
          this.sucesso = '';
        }, 4000);
      } catch (error) {
        if (error.response && error.response.status === 422) {
            const errors = error.response.data.errors;
          
          if (errors.nome) {
            this.nomeEventoEditErro = errors.nome[0];
          }
          if (errors.datainicio) {
            this.dataInicioEventoEditErro = errors.datainicio[0];
          }
          if (errors.datafim) {
            this.dataFimEventoEditErro = errors.datafim[0];
          }
        } else {
          alert('Erro ao editar evento. Tente novamente.');
        }
      }
    },
    abrirModalAdicionarParticipante() {
      this.modalAdicionarAberto = true;
    },
    fecharModalAdicionarParticipante() {
      this.modalAdicionarAberto = false;
      this.buscarEvento();
    },
    editarParticipante(id) {
      this.modalEditarAberto = true;
      this.participanteParaEditar = this.participantes.find(p => p.id === id);
    },
    fecharModalEditarParticipante() {
      this.modalEditarAberto = false;
      this.buscarEvento();
    },
    confirmarExclusaoParticipante(id) {
      if (confirm('Realmente deseja remover este participante?')) {
        axios.delete(`/api/participantes/${id}`);
        this.buscarEvento(); 
      }
    },
    abrirModalPresenca(participante) {
      this.modalPresencaAberto = true;
      this.participanteParaPresenca = participante;
    },
    fecharModalPresenca() {
      this.modalPresencaAberto = false;
      this.buscarEvento();
    },
    abrirModalExcluir(id) {
      this.participanteIdParaExcluir = id;
      this.rotaExclusao = `/api/participantes/${id}`;
      this.modalExcluirAberto = true;
    },
    fecharModalExcluir() {
      this.modalExcluirAberto = false;
      this.participanteIdParaExcluir = null;
    },
  },
  mounted() {
    this.buscarEvento();
  },
};
</script>
