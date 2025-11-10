// convidados/listar/listar-convidados.js

//#region ESTADO GLOBAL
const estadoConvidados = {
    pagina: 1,
    carregando: false,
    temMaisResultados: true,
    totalRegistros: 0,
    filtrosAplicados: false
};
//#endregion

//#region RENDERERIZAÇÃO

// Funções de renderização de status
function renderizarStatusMensagemEnviada(convidado) {
    const bl_mensagem_enviada = convidado.bl_mensagem_enviada;
    let status = '';
    let cor = '';

    if (bl_mensagem_enviada === '1') {
        status = 'Mensagem enviada';
        cor = 'success';
    } else {
        status = 'Mensagem não enviada';
        cor = 'warning';
    }

    return (
        `<span class='badge bg-${cor}'>
            <i class='bi bi-envelope'></i>
            ${status}
         </span>`
    );
}

function redenderizarStatusVisualizado(convidado) {
    const bl_visualizado = convidado.bl_visualizado;
    let status = '';
    let cor = '';

    if (bl_visualizado === '1') {
        status = 'Visualizado';
        cor = 'success';
    } else {
        status = 'Não visualizado';
        cor = 'info';
    }

    return (
        `<span class='badge bg-${cor}'>
            <i class='bi bi-eye-slash'></i>
            ${status}
         </span>`
    );
}

function renderizarStatusConfirmado(convidado) {
    const bl_mensagem_enviada = convidado.bl_mensagem_enviada;
    const bl_confirmacao = convidado.bl_confirmacao;
    let status = '';
    let cor = '';
    let icone = '';

    if (bl_mensagem_enviada === '0') {
        return '';
    }

    if (bl_confirmacao === null) {
        status = 'Não Respondido';
        cor = 'secondary';
        icone = 'clock';
    } else if (bl_confirmacao === '0') {
        status = 'Não confirmado';
        cor = 'danger';
        icone = 'x-circle';
    } else {
        status = 'Confirmado';
        cor = 'success';
        icone = 'check-circle';
    }

    return (`<span class='badge bg-${cor}'>
            <i class='bi bi-${icone} me-1'></i>
            ${status} 
            </span>`);
}

/**
 * Renderizar card individual de convidado
 */
function renderConvidadoCard(convidado) {
    const telefone = convidado.tx_telefone_convidado ? `
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-telephone text-muted me-2" style="width: 16px;"></i>
            <small class="text-muted">${escapeHtml(convidado.tx_telefone_convidado)}</small>
        </div>
    ` : '';

    const qtdPessoas = convidado.nu_qtd_pessoas || 1;
    const textoPessoas = qtdPessoas === 1 ? 'pessoa' : 'pessoas';
    
    const dataRegistro = formatarData(convidado.dt_registro);

    return `
    <div class="col-12 col-sm-6 col-lg-4 convidado-item">
        <div class="card convidado-card touch-feedback h-100">
            <div class="card-body">
                <!-- Header do Card -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="card-title mb-0 text-truncate pe-2">
                        ${escapeHtml(convidado.tx_nome_convidado)}
                    </h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary border-0" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item touch-feedback" 
                                   href="../registrar/registrar-convidados.html.php?cd=${convidado.codigo_convidado}">
                                    <i class="bi bi-pencil me-2"></i>Editar
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger touch-feedback" 
                                   href="../desativar/desativar-convidados.html.php?cd=${convidado.codigo_convidado}">
                                    <i class="bi bi-trash me-2"></i>Desativar
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Informações do Convidado -->
                <div class="mb-3">
                    ${telefone}
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-people text-muted me-2" style="width: 16px;"></i>
                        <small class="text-muted">
                            ${qtdPessoas} ${textoPessoas}
                        </small>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar text-muted me-2" style="width: 16px;"></i>
                        <small class="text-muted">${dataRegistro}</small>
                    </div>
                </div>

                <!-- Status e Badges -->
                <div>
                    ${renderizarStatusMensagemEnviada(convidado)}
                    <br />
                    ${redenderizarStatusVisualizado(convidado)}
                    <br />
                    ${renderizarStatusConfirmado(convidado)}
                </div>
            </div>
        </div>
    </div>
    `;
}

/**
 * Renderizar grid de cards de convidados
 */
function renderConvidadosGrid(convidados) {
    if (!convidados || convidados.length === 0) {
        return '';
    }

    return convidados.map(convidado => renderConvidadoCard(convidado)).join('');
}

// Funções utilitárias de renderização
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatarData(dataString) {
    const data = new Date(dataString);
    return data.toLocaleDateString('pt-BR');
}
//#endregion

//#region CONTROLLER - INICIALIZAÇÃO E EVENTOS

// Inicialização
function initConvidadosController() {
    vincularEventosConvidados();
    carregarDadosIniciaisConvidados();
}

// Vinculação de eventos
function vincularEventosConvidados() {
    const filtrosForm = document.getElementById('filtrosForm');
    if (filtrosForm) {
        filtrosForm.addEventListener('submit', function(e) {
            e.preventDefault();
            aplicarFiltrosConvidados();
        });
    }

    const carregarMaisBtn = document.getElementById('carregar-mais-btn');
    if (carregarMaisBtn) {
        carregarMaisBtn.addEventListener('click', carregarMaisConvidados);
    }

    const clearSearchBtn = document.getElementById('clearSearchBtn');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', limparBuscaConvidados);
    }

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            toggleBotaoLimparBuscaConvidados(e.target.value);
        });
    }
}
//#endregion

//#region CONTROLLER - CARREGAMENTO DE DADOS

// Funções de carregamento de dados
async function carregarDadosIniciaisConvidados() {
    await carregarDadosConvidados(1, false);
}

async function aplicarFiltrosConvidados() {
    estadoConvidados.filtrosAplicados = true;
    verificarFiltrosAtivosConvidados();
    await carregarDadosConvidados(1, false);
}

async function carregarDadosConvidados(pagina, append) {
    if (estadoConvidados.carregando) return;
    
    estadoConvidados.carregando = true;
    estadoConvidados.pagina = pagina;

    mostrarLoadingConvidados(append);

    try {
        const params = obterParametrosFiltrosConvidados();
        params.append('page', pagina);
        params.append('limit', 12);
        
        const response = await fetch('/admin/convidados/api/obter_todos.php?' + params.toString());
        const data = await response.json();

        if (data.success) {
            processarRespostaConvidados(data, append);
        } else {
            throw new Error(data.error || 'Erro na resposta do servidor');
        }

    } catch (error) {
        tratarErroConvidados(error, append);
    } finally {
        estadoConvidados.carregando = false;
        esconderLoadingConvidados(append);
    }
}

// Funções auxiliares de dados
function obterParametrosFiltrosConvidados() {
    const formData = new FormData(document.getElementById('filtrosForm'));
    const params = new URLSearchParams();
    
    const mapeamentoCampos = {
        'msg_nao_enviada': 'msg_nao_enviada',
        'nao_confirmado': 'nao_confirmado', 
        'nao_respondido': 'nao_respondido',
        'nao_visualizado': 'nao_visualizado',
        'q': 'q'
    };
    
    for (let [key, value] of formData.entries()) {
        const paramName = mapeamentoCampos[key] || key;
        if (value) {
            params.append(paramName, value);
        }
    }
    
    return params;
}

function processarRespostaConvidados(data, append) {
    estadoConvidados.temMaisResultados = data.data.has_next_page;
    estadoConvidados.totalRegistros = data.data.nu_count;
    
    // Renderizar HTML no cliente usando as funções de renderização
    const html = renderConvidadosGrid(data.data.rows);
    
    if (!append) {
        mostrarConteudoPrincipalConvidados();
        
        if (data.data.rows.length > 0) {
            document.getElementById('convidados-container').classList.remove('d-none');
            document.getElementById('convidados-grid').innerHTML = html;
        } else {
            mostrarEstadoVazioConvidados(data.data.nu_count === 0);
        }
    } else {
        document.getElementById('convidados-grid').insertAdjacentHTML('beforeend', html);
    }

    atualizarBotaoCarregarMaisConvidados();
    atualizarTotalConvidadosConvidados();
    
    // Inicializar tooltips do Bootstrap
    if (typeof bootstrap !== 'undefined') {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
    }
}
//#endregion

//#region CONTROLLER - UI/ESTADO

function mostrarLoadingConvidados(append) {
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

function esconderLoadingConvidados(append) {
    if (!append) {
        hideLoading();
    } else {
        document.getElementById('carregando-mais').style.display = 'none';
        if (estadoConvidados.temMaisResultados) {
            document.getElementById('carregar-mais-btn').style.display = 'inline-block';
        }
    }
}

function mostrarConteudoPrincipalConvidados() {
    document.getElementById('loading-inicial').classList.add('d-none');
    document.getElementById('erro-container').classList.add('d-none');
}

function mostrarEstadoVazioConvidados(semConvidados) {
    document.getElementById('convidados-container').classList.add('d-none');
    document.getElementById('estado-vazio').classList.remove('d-none');
    document.getElementById('mensagem-vazio').textContent =
        semConvidados ?
            'Comece adicionando seu primeiro convidado.' :
            'Nenhum convidado encontrado com os filtros aplicados.';
}

function tratarErroConvidados(error, append) {
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

function carregarMaisConvidados() {
    if (!estadoConvidados.carregando && estadoConvidados.temMaisResultados) {
        carregarDadosConvidados(estadoConvidados.pagina + 1, true);
    }
}

function atualizarBotaoCarregarMaisConvidados() {
    const container = document.getElementById('carregar-mais-container');
    const botao = document.getElementById('carregar-mais-btn');

    if (container && botao) {
        if (estadoConvidados.temMaisResultados) {
            container.classList.remove('d-none');
            botao.style.display = 'inline-block';
        } else {
            container.classList.add('d-none');
        }
    }
}

function atualizarTotalConvidadosConvidados() {
    const elemento = document.getElementById('total-convidados');
    if (elemento) {
        elemento.textContent = `${estadoConvidados.totalRegistros} ${estadoConvidados.totalRegistros === 1 ? 'convidado' : 'convidados'}`;
    }
}

function verificarFiltrosAtivosConvidados() {
    const params = obterParametrosFiltrosConvidados();
    const temFiltros = params.toString() !== '';

    const filtrosAtivo = document.getElementById('filtros-ativo');
    if (filtrosAtivo) {
        filtrosAtivo.classList.toggle('d-none', !temFiltros);
    }
}

function toggleBotaoLimparBuscaConvidados(valor) {
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    if (clearSearchBtn) {
        clearSearchBtn.classList.toggle('d-none', !valor);
    }
}

function limparBuscaConvidados() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.value = '';
        toggleBotaoLimparBuscaConvidados(false);
    }
}

function limparFiltrosConvidados() {
    const filtrosForm = document.getElementById('filtrosForm');
    if (filtrosForm) {
        filtrosForm.reset();
    }

    limparBuscaConvidados();

    const filtrosAtivo = document.getElementById('filtros-ativo');
    if (filtrosAtivo) {
        filtrosAtivo.classList.add('d-none');
    }

    estadoConvidados.filtrosAplicados = false;
    carregarDadosConvidados(1, false);
}
//#endregion

//#region FUNÇÕES GLOBAIS E INICIALIZAÇÃO

// Funções globais para uso em botões HTML
function limparFiltrosGlobal() {
    limparFiltrosConvidados();
}

function clearSearchGlobal() {
    limparBuscaConvidados();
}

// Inicializar quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    initConvidadosController();

    // Expor funções globais
    window.limparFiltros = limparFiltrosGlobal;
    window.clearSearch = clearSearchGlobal;
});

// Funções auxiliares globais (se não existirem)
if (typeof showLoading === 'undefined') {
    function showLoading(mensagem) {
        console.log('Loading:', mensagem);
        const loading = document.createElement('div');
        loading.className = 'loading-overlay';
        loading.innerHTML = `<div class="spinner-border text-primary" role="status">
                               <span class="visually-hidden">${mensagem}</span>
                             </div>`;
        document.body.appendChild(loading);
    }
}

if (typeof hideLoading === 'undefined') {
    function hideLoading() {
        const loading = document.querySelector('.loading-overlay');
        if (loading) {
            loading.remove();
        }
    }
}
//#endregion