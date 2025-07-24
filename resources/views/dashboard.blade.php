<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-dark">{{ __('Gestão de Funcionários') }}</h2>
  </x-slot>

  <div class="container py-4"
    x-data="{
      mostrarFormulario: false,
      funcionarios: [],
      novoFuncionario: { nome: '', email: '', cpf: '', cargo: '', dataAdmissao: '', salario: '' },
      modalVer: false,
      modalEditar: false,
      carregando: false,
      erros: {},
      funcSelecionado: { id: '', nome: '', email: '', cpf: '', cargo: '', dataAdmissao: '', salario: '' },

      alerta: { show: false, message: '', type: 'success' },
      mostrarAlerta(mensagem, tipo = 'success') {
        this.alerta.message = mensagem;
        this.alerta.type = tipo;
        this.alerta.show = true;
        setTimeout(() => { this.alerta.show = false }, 4000);
      },

      filtroNome: '',
      paginaAtual: 1,
      itensPorPagina: 10,

      ordenarFuncionarios() {
        this.funcionarios.sort((a, b) => a.nome.localeCompare(b.nome, 'pt-BR', { sensitivity: 'base' }));
      },

      get funcionariosFiltrados() {
        if (!this.filtroNome) return this.funcionarios;
        return this.funcionarios.filter(f => f.nome.toLowerCase().includes(this.filtroNome.toLowerCase()));
      },

      get totalPaginas() {
        return Math.ceil(this.funcionariosFiltrados.length / this.itensPorPagina) || 1;
      },

      get funcionariosPaginados() {
        const start = (this.paginaAtual - 1) * this.itensPorPagina;
        return this.funcionariosFiltrados.slice(start, start + this.itensPorPagina);
      },

      irParaPagina(n) {
        if (n < 1) n = 1;
        if (n > this.totalPaginas) n = this.totalPaginas;
        this.paginaAtual = n;
      },

      async init() {
        const res = await fetch('/api/funcionarios');
        this.funcionarios = await res.json();
        this.ordenarFuncionarios();
      },

      async cadastrarFuncionario() {
        this.carregando = true;
        this.erros = {};
        try {
          const response = await fetch('/api/funcionarios', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(this.novoFuncionario)
          });

          if (!response.ok) {
            const data = await response.json();
            this.erros = data.errors || {};
            this.mostrarAlerta('Erro ao cadastrar funcionário.', 'danger');
            throw new Error('Erro ao cadastrar');
          }

          const res = await fetch('/api/funcionarios');
          this.funcionarios = await res.json();
          this.ordenarFuncionarios();

          this.novoFuncionario = { nome: '', email: '', cpf: '', cargo: '', dataAdmissao: '', salario: '' };
          this.mostrarFormulario = false;
          this.mostrarAlerta('Funcionário cadastrado com sucesso!', 'success');

          this.irParaPagina(1);
        } catch (e) {
          console.error(e);
        } finally {
          this.carregando = false;
        }
      },

      async deletarFuncionario(func) {
        if (!confirm(`Tem certeza que deseja deletar o funcionário ${func.nome}?`)) {
          return;
        }

        try {
          const response = await fetch(`/api/funcionarios/${func.id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
          });

          if (!response.ok) {
            this.mostrarAlerta('Erro ao deletar funcionário.', 'danger');
            throw new Error('Erro ao deletar');
          }

          const res = await fetch('/api/funcionarios');
          this.funcionarios = await res.json();
          this.ordenarFuncionarios();

          this.mostrarAlerta('Funcionário deletado com sucesso!', 'success');

          if (this.funcionariosPaginados.length === 0 && this.paginaAtual > 1) {
            this.irParaPagina(this.paginaAtual - 1);
          }
        } catch (e) {
          console.error(e);
        }
      },

      verFuncionario(func) {
        this.funcSelecionado = { ...func };
        this.modalVer = true;
        document.body.classList.add('modal-open');
      },

      editarFuncionario(func) {
        this.funcSelecionado = { ...func };
        this.modalEditar = true;
        this.erros = {};
        document.body.classList.add('modal-open');
      },

      fecharModalVer() {
        this.modalVer = false;
        document.body.classList.remove('modal-open');
      },

      fecharModalEditar() {
        this.modalEditar = false;
        this.erros = {};
        document.body.classList.remove('modal-open');
      },

      fecharModalCadastrar() {
        this.mostrarFormulario = false;
        this.novoFuncionario = { nome: '', email: '', cpf: '', cargo: '', dataAdmissao: '', salario: '' };
        this.erros = {};
        document.body.classList.remove('modal-open');
      },

      async salvarEdicao() {
        this.carregando = true;
        this.erros = {};
        try {
          const response = await fetch(`/api/funcionarios/${this.funcSelecionado.id}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(this.funcSelecionado)
          });

          if (!response.ok) {
            const data = await response.json();
            this.erros = data.errors || {};
            this.mostrarAlerta('Erro ao editar funcionário.', 'danger');
            throw new Error('Erro ao editar');
          }

          const res = await fetch('/api/funcionarios');
          this.funcionarios = await res.json();
          this.ordenarFuncionarios();

          this.fecharModalEditar();
          this.mostrarAlerta('Funcionário editado com sucesso!', 'success');
        } catch (e) {
          console.error(e);
        } finally {
          this.carregando = false;
        }
      }
    }"
    x-init="init()">

    <!-- Alerta -->
    <div
      x-show="alerta.show"
      x-transition
      class="alert"
      :class="alerta.type === 'success' ? 'alert-success' : 'alert-danger'"
      role="alert"
      style="position: fixed; top: 20px; right: 20px; z-index: 1055; min-width: 300px; cursor: pointer;"
      x-text="alerta.message"
      @click="alerta.show = false"></div>

    <!-- Botão Novo Funcionário alinhado à direita -->
    <div class="d-flex justify-content-end mb-3">
      <button class="btn btn-primary" @click="mostrarFormulario = true; document.body.classList.add('modal-open')">
        Novo Funcionário
      </button>
    </div>

    <!-- Input busca -->
    <div class="mb-3">
      <input
        type="text"
        class="form-control"
        placeholder="Buscar por nome..."
        x-model="filtroNome"
        @input="paginaAtual = 1" />
    </div>

    <!-- Lista Funcionários -->
    <div class="card">
      <div class="card-header">Funcionários Cadastrados</div>
      <ul class="list-group list-group-flush">
        <template x-for="func in funcionariosPaginados" :key="func.id">
          <li class="list-group-item">
            <div class="row align-items-center">
              <div class="col-md-3 fw-bold" x-text="func.nome"></div>
              <div class="col-md-4" x-text="func.email"></div>
              <div class="col-md-3" x-text="func.cpf"></div>
              <div class="col-md-2 text-end">
                <button class="btn btn-sm btn-info me-1" @click="verFuncionario(func)">Ver</button>
                <button class="btn btn-sm btn-warning me-1" @click="editarFuncionario(func)">Editar</button>
                <button class="btn btn-sm btn-danger" @click="deletarFuncionario(func)">Deletar</button>
              </div>
            </div>
          </li>
        </template>
        <template x-if="funcionariosFiltrados.length === 0">
          <li class="list-group-item text-center text-muted">Nenhum funcionário encontrado.</li>
        </template>
      </ul>
      <div class="card-footer d-flex justify-content-between align-items-center">
        <button class="btn btn-outline-primary"
          :disabled="paginaAtual === 1"
          @click="irParaPagina(paginaAtual - 1)">
          &laquo; Anterior
        </button>

        <span>Página <strong x-text="paginaAtual"></strong> de <strong x-text="totalPaginas"></strong></span>

        <button class="btn btn-outline-primary"
          :disabled="paginaAtual === totalPaginas"
          @click="irParaPagina(paginaAtual + 1)">
          Próximo &raquo;
        </button>
      </div>
    </div>

    <!-- Modal Cadastro -->
    <div
      class="modal fade"
      tabindex="-1"
      style="display: none;"
      :class="{'show d-block': mostrarFormulario}"
      aria-modal="true" role="dialog"
      x-cloak
      @keydown.escape.window="fecharModalCadastrar()"
      @click.self="fecharModalCadastrar()">
      <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Novo Funcionário</h5>
            <button type="button" class="btn-close" @click="fecharModalCadastrar()"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="cadastrarFuncionario">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Nome</label>
                  <input type="text" class="form-control" x-model="novoFuncionario.nome" required>
                  <template x-if="erros.nome">
                    <div class="text-danger mt-1" x-text="erros.nome[0]"></div>
                  </template>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">E-mail</label>
                  <input type="email" class="form-control" x-model="novoFuncionario.email" required>
                  <template x-if="erros.email">
                    <div class="text-danger mt-1" x-text="erros.email[0]"></div>
                  </template>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">CPF</label>
                  <input type="text" class="form-control" x-model="novoFuncionario.cpf" maxlength="11" minlength="11" required>
                  <template x-if="erros.cpf">
                    <div class="text-danger mt-1" x-text="erros.cpf[0]"></div>
                  </template>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Cargo</label>
                  <input type="text" class="form-control" x-model="novoFuncionario.cargo">
                  <template x-if="erros.cargo">
                    <div class="text-danger mt-1" x-text="erros.cargo[0]"></div>
                  </template>
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label">Data de Admissão</label>
                  <input type="date" class="form-control" x-model="novoFuncionario.dataAdmissao">
                  <template x-if="erros.dataAdmissao">
                    <div class="text-danger mt-1" x-text="erros.dataAdmissao[0]"></div>
                  </template>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Salário (R$)</label>
                  <input type="number" class="form-control" x-model="novoFuncionario.salario" step="0.01" min="0">
                  <template x-if="erros.salario">
                    <div class="text-danger mt-1" x-text="erros.salario[0]"></div>
                  </template>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="fecharModalCadastrar()">Fechar</button>
                <button type="submit" class="btn btn-success" :disabled="carregando">
                  <template x-if="carregando">
                    <span class="spinner-border spinner-border-sm me-1"></span>
                  </template>
                  Salvar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Ver -->
    <div
      class="modal fade"
      tabindex="-1"
      style="display: none;"
      :class="{'show d-block': modalVer}"
      aria-modal="true" role="dialog"
      x-cloak
      @keydown.escape.window="fecharModalVer()"
      @click.self="fecharModalVer()">
      <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detalhes do Funcionário</h5>
            <button type="button" class="btn-close" @click="fecharModalVer()"></button>
          </div>
          <div class="modal-body">
            <p><strong>Nome:</strong> <span x-text="funcSelecionado.nome"></span></p>
            <p><strong>Email:</strong> <span x-text="funcSelecionado.email"></span></p>
            <p><strong>CPF:</strong> <span x-text="funcSelecionado.cpf"></span></p>
            <p><strong>Cargo:</strong> <span x-text="funcSelecionado.cargo"></span></p>
            <p><strong>Data de Admissão:</strong>
              <span x-text="new Date(funcSelecionado.dataAdmissao).toLocaleDateString('pt-BR')"></span>
            </p>
            <p><strong>Salário:</strong> R$ <span x-text="funcSelecionado.salario"></span></p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="fecharModalVer()">Fechar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Editar -->
    <div
      class="modal fade"
      tabindex="-1"
      style="display: none;"
      :class="{'show d-block': modalEditar}"
      aria-modal="true" role="dialog"
      x-cloak
      @keydown.escape.window="fecharModalEditar()"
      @click.self="fecharModalEditar()">
      <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar Funcionário</h5>
            <button type="button" class="btn-close" @click="fecharModalEditar()"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="salvarEdicao">
              <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" class="form-control" x-model="funcSelecionado.nome" required>
                <template x-if="erros.nome">
                  <div class="text-danger mt-1" x-text="erros.nome[0]"></div>
                </template>
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" x-model="funcSelecionado.email" required>
                <template x-if="erros.email">
                  <div class="text-danger mt-1" x-text="erros.email[0]"></div>
                </template>
              </div>
              <div class="mb-3">
                <label class="form-label">CPF</label>
                <input type="text" class="form-control" x-model="funcSelecionado.cpf" maxlength="11" minlength="11" required>
                <template x-if="erros.cpf">
                  <div class="text-danger mt-1" x-text="erros.cpf[0]"></div>
                </template>
              </div>
              <div class="mb-3">
                <label class="form-label">Cargo</label>
                <input type="text" class="form-control" x-model="funcSelecionado.cargo">
                <template x-if="erros.cargo">
                  <div class="text-danger mt-1" x-text="erros.cargo[0]"></div>
                </template>
              </div>
              <div class="mb-3">
                <label class="form-label">Data de Admissão</label>
                <input type="date" class="form-control" x-model="funcSelecionado.dataAdmissao">
                <template x-if="erros.dataAdmissao">
                  <div class="text-danger mt-1" x-text="erros.dataAdmissao[0]"></div>
                </template>
              </div>
              <div class="mb-3">
                <label class="form-label">Salário</label>
                <input type="number" class="form-control" x-model="funcSelecionado.salario" step="0.01" min="0">
                <template x-if="erros.salario">
                  <div class="text-danger mt-1" x-text="erros.salario[0]"></div>
                </template>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="fecharModalEditar()">Cancelar</button>
                <button type="submit" class="btn btn-primary" :disabled="carregando">
                  <template x-if="carregando">
                    <span class="spinner-border spinner-border-sm me-1"></span>
                  </template>
                  Salvar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</x-app-layout>
