// convidados/listar/listar-convidados.js

/**
 * Controller para listagem de convidados
 * Gerencia estado e interações do frontend
 */

class ConvidadosListController {
    constructor() {
        this.estado = {
            pagina: 1,
            carregando: false,
            temMaisResultados: true,
            totalRegistros: 0,
            filtrosAplicados: false
        };
        
        this.init();
    }
    
    init() {
        this.vincularEventos();
        this.carregarDadosIniciais();
    }
    
    vincularEventos() {
        // Form de filtros - só envia quando clicar em aplicar
        document.getElementById('filtrosForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.aplicarFiltros();
        });
        
        // Botão carregar mais
        document.getElementById('carregar-mais-btn').addEventListener('click', () => {
            this.carregarMais();
        });
        
        // Botão limpar busca
        document.getElementById('clearSearchBtn').addEventListener('click', () => {
            this.limparBusca();
        });
        
        // Input de busca - mostrar/ocultar botão limpar
        document.getElementById('searchInput').addEventListener('input', (e) => {
            this.toggleBotaoLimparBusca(e.target.value);
        });
    
    }
    
    async carregarDadosIniciais() {
        await this.carregarDados(1, false);
    }
    
    async aplicarFiltros() {
        this.estado.filtrosAplicados = true;
        this.verificarFiltrosAtivos();
        await this.carregarDados(1, false);
    }
    
    async carregarDados(pagina = 1, append = false) {
        if (this.estado.carregando) return;
        
        this.estado.carregando = true;
        this.estado.pagina = pagina;

        this.mostrarLoading(append);

        try {
            const params = this.obterParametrosFiltros();
            params.append('page', pagina);
            
            const response = await fetch('api-convidados.obter_todos.php?' + params.toString());
            const data = await response.json();

            if (data.success) {
                this.processarResposta(data, append);
            } else {
                throw new Error(data.error || 'Erro na resposta do servidor');
            }

        } catch (error) {
            this.tratarErro(error, append);
        } finally {
            this.estado.carregando = false;
            this.esconderLoading(append);
        }
    }
    
    obterParametrosFiltros() {
        const formData = new FormData(document.getElementById('filtrosForm'));
        const params = new URLSearchParams();
        
        for (let [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }
        
        return params;
    }
    
    processarResposta(data, append) {
        this.estado.temMaisResultados = data.data.has_next_page;
        this.estado.totalRegistros = data.data.nu_count;
        
        if (!append) {
            this.mostrarConteudoPrincipal();
            
            if (data.data.rows.length > 0) {
                document.getElementById('convidados-container').classList.remove('d-none');
                document.getElementById('convidados-grid').innerHTML = data.html;
            } else {
                this.mostrarEstadoVazio(data.data.nu_count === 0);
            }
        } else {
            document.getElementById('convidados-grid').insertAdjacentHTML('beforeend', data.html);
        }

        this.atualizarBotaoCarregarMais();
        this.atualizarTotalConvidados();
    }
    
    mostrarLoading(append) {
        if (!append) {
            showLoading('Carregando convidados...');
            document.getElementById('loading-inicial').classList.remove('d-none');
            document.getElementById('convidados-container').classList.add('d-none');
            document.getElementById('estado-vazio').classList.add('d-none');
            document.getElementById('erro-container').classList.add('d-none');
        } else {
            document.getElementById('carregar-mais-btn').style.display = 'none';
            document.getElementById('carregando-mais').style.display = 'block';
        }
    }
    
    esconderLoading(append) {
        if (!append) {
            hideLoading();
        } else {
            document.getElementById('carregando-mais').style.display = 'none';
            if (this.estado.temMaisResultados) {
                document.getElementById('carregar-mais-btn').style.display = 'inline-block';
            }
        }
    }
    
    mostrarConteudoPrincipal() {
        document.getElementById('loading-inicial').classList.add('d-none');
        document.getElementById('erro-container').classList.add('d-none');
    }
    
    mostrarEstadoVazio(semConvidados) {
        document.getElementById('convidados-container').classList.add('d-none');
        document.getElementById('estado-vazio').classList.remove('d-none');
        document.getElementById('mensagem-vazio').textContent = 
            semConvidados ? 
            'Comece adicionando seu primeiro convidado.' : 
            'Nenhum convidado encontrado com os filtros aplicados.';
    }
    
    tratarErro(error, append) {
        console.error('Erro ao carregar convidados:', error);
        
        if (!append) {
            document.getElementById('loading-inicial').classList.add('d-none');
            document.getElementById('erro-container').classList.remove('d-none');
            document.getElementById('mensagem-erro').textContent = 
                'Erro ao carregar convidados. Tente recarregar a página.';
        } else {
            alert('Erro ao carregar mais convidados. Tente novamente.');
        }
    }
    
    carregarMais() {
        if (!this.estado.carregando && this.estado.temMaisResultados) {
            this.carregarDados(this.estado.pagina + 1, true);
        }
    }
    
    atualizarBotaoCarregarMais() {
        const container = document.getElementById('carregar-mais-container');
        const botao = document.getElementById('carregar-mais-btn');
        
        if (this.estado.temMaisResultados) {
            container.classList.remove('d-none');
            botao.style.display = 'inline-block';
        } else {
            container.classList.add('d-none');
        }
    }
    
    atualizarTotalConvidados() {
        const elemento = document.getElementById('total-convidados');
        elemento.textContent = `${this.estado.totalRegistros} ${this.estado.totalRegistros === 1 ? 'convidado' : 'convidados'}`;
    }
    
    verificarFiltrosAtivos() {
        const params = this.obterParametrosFiltros();
        const temFiltros = params.toString() !== '';
        
        document.getElementById('filtros-ativo').classList.toggle('d-none', !temFiltros);
    }
    
    toggleBotaoLimparBusca(valor) {
        document.getElementById('clearSearchBtn').classList.toggle('d-none', !valor);
    }
    
    limparBusca() {
        document.getElementById('searchInput').value = '';
        this.toggleBotaoLimparBusca(false);
    }
    
    limparFiltros() {
        document.getElementById('filtrosForm').reset();
        this.limparBusca();
        document.getElementById('filtros-ativo').classList.add('d-none');
        
        this.estado.filtrosAplicados = false;
        this.carregarDados(1, false);
    }
}

// Inicializar controller quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    window.convidadosController = new ConvidadosListController();
    
    // Expor funções globais para os botões
    window.limparFiltros = () => window.convidadosController.limparFiltros();
    window.clearSearch = () => window.convidadosController.limparBusca();
});