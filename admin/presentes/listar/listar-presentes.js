//#region ESTADO GLOBAL
const estadoPresentes = {
    pagina: 1,
    carregando: false,
    temMaisResultados: true,
    totalRegistros: 0,
    filtrosAplicados: false
};
//#endregion

//#region RENDERERIZAÇÃO

// Funções de renderização de status
function renderizarStatusReserva(presente) {
    const estaReservado = presente.codigo_convidado !== null;
    let status = '';
    let cor = '';
    let icone = '';

    if (estaReservado) {
        status = 'Reservado';
        cor = 'info';
        icone = 'bookmark-check';
    } else {
        status = 'Disponível';
        cor = 'success';
        icone = 'gift';
    }

    return (
        `<span class='badge bg-${cor}'>
            <i class='bi bi-${icone} me-1'></i>
            ${status}
        </span>`
    );
}

function renderizarFotoPresente(presente) {
    const temFoto = presente.tx_foto_presente && presente.tx_foto_presente.trim() !== '';
    
    if (temFoto) {
        const caminhoFoto = `/admin/presentes/api/../../../_uploads/presentes/${presente.tx_foto_presente}`;
        return `
            <div class="presente-foto-container mb-3">
                <img src="${caminhoFoto}" 
                     alt="${escapeHtml(presente.tx_nome_presente)}"
                     class="presente-foto img-fluid rounded"
                     onerror="this.style.display='none'">
            </div>
        `;
    } else {
        return `
            <div class="presente-sem-foto mb-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                     style="height: 120px;">
                    <i class="bi bi-image text-muted display-6"></i>
                </div>
            </div>
        `;
    }
}

function renderizarConvidadoReserva(presente) {
    if (presente.codigo_convidado && presente.tx_nome_convidado) {
        return `
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-person-check text-muted me-2" style="width: 16px;"></i>
                <small class="text-muted">
                    Reservado por: ${escapeHtml(presente.tx_nome_convidado)}
                </small>
            </div>
        `;
    }
    return '';
}

/**
 * Renderizar card individual de presente
 */
function renderPresenteCard(presente) {
    const descricao = presente.tx_descricao_presente ? `
        <div class="mb-2">
            <small class="text-muted">${escapeHtml(presente.tx_descricao_presente)}</small>
        </div>
    ` : '';

    const dataRegistro = formatarData(presente.dt_registro);

    return `
        <div class="col-12 col-sm-6 col-lg-4 presente-item">
            <div class="card presente-card touch-feedback h-100">
                <div class="card-body">
                    <!-- Foto do Presente -->
                    ${renderizarFotoPresente(presente)}

                    <!-- Header do Card -->
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0 text-truncate pe-2">
                            ${escapeHtml(presente.tx_nome_presente)}
                        </h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary border-0" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item touch-feedback" 
                                       href="../registrar/registrar-presente.html.php?cd=${presente.codigo_presente}">
                                        <i class="bi bi-pencil me-2"></i>Editar
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger touch-feedback" 
                                       href="../desativar/desativar-presentes.html.php?cd=${presente.codigo_presente}">
                                        <i class="bi bi-trash me-2"></i>Desativar
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Descrição -->
                    ${descricao}

                    <!-- Informações do Presente -->
                    <div class="mb-3">
                        ${renderizarConvidadoReserva(presente)}
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar text-muted me-2" style="width: 16px;"></i>
                            <small class="text-muted">${dataRegistro}</small>
                        </div>
                    </div>

                    <!-- Status e Badges -->
                    <div>
                        ${renderizarStatusReserva(presente)}
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Renderizar grid de cards de presentes
 */
function renderPresentesGrid(presentes) {
    if (!presentes || presentes.length === 0) {
        return '';
    }

    return presentes.map(presente => renderPresenteCard(presente)).join('');
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
function initPresentesController() {
    vincularEventosPresentes();
    carregarDadosIniciaisPresentes();
}

// Vinculação de eventos
function vincularEventosPresentes() {
    const filtrosForm = document.getElementById('filtrosForm');
    if (filtrosForm) {
        filtrosForm.addEventListener('submit', function(e) {
            e.preventDefault();
            aplicarFiltrosPresentes();
        });
    }

    const carregarMaisBtn = document.getElementById('carregar-mais-btn');
    if (carregarMaisBtn) {
        carregarMaisBtn.addEventListener('click', carregarMaisPresentes);
    }

    const clearSearchBtn = document.getElementById('clearSearchBtn');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', limparBuscaPresentes);
    }

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            toggleBotaoLimparBuscaPresentes(e.target.value);
        });
    }
}
//#endregion

//#region CONTROLLER - CARREGAMENTO DE DADOS

// Funções de carregamento de dados
async function carregarDadosIniciaisPresentes() {
    await carregarDadosPresentes(1, false);
}

async function aplicarFiltrosPresentes() {
    estadoPresentes.filtrosAplicados = true;
    verificarFiltrosAtivosPresentes();
    await carregarDadosPresentes(1, false);
}

async function carregarDadosPresentes(pagina, append) {
    if (estadoPresentes.carregando) return;
    estadoPresentes.carregando = true;
    estadoPresentes.pagina = pagina;

    mostrarLoadingPresentes(append);

    try {
        const params = obterParametrosFiltrosPresentes();
        params.append('page', pagina);
        params.append('limit', 12);
        
        const response = await fetch('/admin/presentes/api/obter_todos.php?' + params.toString());
        const data = await response.json();

        if (data.success) {
            processarRespostaPresentes(data, append);
        } else {
            throw new Error(data.error || 'Erro na resposta do servidor');
        }

    } catch (error) {
        tratarErroPresentes(error, append);
    } finally {
        estadoPresentes.carregando = false;
        esconderLoadingPresentes(append);
    }
}

// Funções auxiliares de dados
function obterParametrosFiltrosPresentes() {
    const formData = new FormData(document.getElementById('filtrosForm'));
    const params = new URLSearchParams();
    const mapeamentoCampos = {
        'nao_reservados': 'nao_reservados',
        'reservados': 'reservados',
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

function processarRespostaPresentes(data, append) {
    estadoPresentes.temMaisResultados = data.data.has_next_page;
    estadoPresentes.totalRegistros = data.data.nu_count;
    
    // Renderizar HTML no cliente usando as funções de renderização
    const html = renderPresentesGrid(data.data.rows);
    
    if (!append) {
        mostrarConteudoPrincipalPresentes();
        if (data.data.rows.length > 0) {
            document.getElementById('presentes-container').classList.remove('d-none');
            document.getElementById('presentes-grid').innerHTML = html;
        } else {
            mostrarEstadoVazioPresentes(data.data.nu_count === 0);
        }
    } else {
        document.getElementById('presentes-grid').insertAdjacentHTML('beforeend', html);
    }

    atualizarBotaoCarregarMaisPresentes();
    atualizarTotalPresentes();
    
    // Inicializar tooltips do Bootstrap
    if (typeof bootstrap !== 'undefined') {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
    }
}
//#endregion

//#region CONTROLLER - UI/ESTADO

function mostrarLoadingPresentes(append) {
    if (!append) {
        showLoading('Carregando presentes...');
        document.getElementById('loading-inicial').classList.remove('d-none');
        document.getElementById('presentes-container').classList.add('d-none');
        document.getElementById('estado-vazio').classList.add('d-none');
        document.getElementById('erro-container').classList.add('d-none');
    } else {
        document.getElementById('carregar-mais-btn').style.display = 'none';
        document.getElementById('carregando-mais').style.display = 'block';
    }
}

function esconderLoadingPresentes(append) {
    if (!append) {
        hideLoading();
    } else {
        document.getElementById('carregando-mais').style.display = 'none';
        if (estadoPresentes.temMaisResultados) {
            document.getElementById('carregar-mais-btn').style.display = 'inline-block';
        }
    }
}

function mostrarConteudoPrincipalPresentes() {
    document.getElementById('loading-inicial').classList.add('d-none');
    document.getElementById('erro-container').classList.add('d-none');
}

function mostrarEstadoVazioPresentes(semPresentes) {
    document.getElementById('presentes-container').classList.add('d-none');
    document.getElementById('estado-vazio').classList.remove('d-none');
    document.getElementById('mensagem-vazio').textContent =
        semPresentes ?
        'Comece adicionando seu primeiro presente.' :
        'Nenhum presente encontrado com os filtros aplicados.';
}

function tratarErroPresentes(error, append) {
    console.error('Erro ao carregar presentes:', error);

    if (!append) {
        document.getElementById('loading-inicial').classList.add('d-none');
        document.getElementById('erro-container').classList.remove('d-none');
        document.getElementById('mensagem-erro').textContent =
            'Erro ao carregar presentes. Tente recarregar a página.';
    } else {
        alert('Erro ao carregar mais presentes. Tente novamente.');
    }
}

function carregarMaisPresentes() {
    if (!estadoPresentes.carregando && estadoPresentes.temMaisResultados) {
        carregarDadosPresentes(estadoPresentes.pagina + 1, true);
    }
}

function atualizarBotaoCarregarMaisPresentes() {
    const container = document.getElementById('carregar-mais-container');
    const botao = document.getElementById('carregar-mais-btn');

    if (container && botao) {
        if (estadoPresentes.temMaisResultados) {
            container.classList.remove('d-none');
            botao.style.display = 'inline-block';
        } else {
            container.classList.add('d-none');
        }
    }
}

function atualizarTotalPresentes() {
    const elemento = document.getElementById('total-presentes');
    if (elemento) {
        elemento.textContent = `${estadoPresentes.totalRegistros} ${estadoPresentes.totalRegistros === 1 ? 'presente' : 'presentes'}`;
    }
}

function verificarFiltrosAtivosPresentes() {
    const params = obterParametrosFiltrosPresentes();
    const temFiltros = params.toString() !== '';

    const filtrosAtivo = document.getElementById('filtros-ativo');
    if (filtrosAtivo) {
        filtrosAtivo.classList.toggle('d-none', !temFiltros);
    }
}

function toggleBotaoLimparBuscaPresentes(valor) {
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    if (clearSearchBtn) {
        clearSearchBtn.classList.toggle('d-none', !valor);
    }
}

function limparBuscaPresentes() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.value = '';
        toggleBotaoLimparBuscaPresentes(false);
    }
}

function limparFiltrosPresentes() {
    const filtrosForm = document.getElementById('filtrosForm');
    if (filtrosForm) {
        filtrosForm.reset();
    }

    limparBuscaPresentes();

    const filtrosAtivo = document.getElementById('filtros-ativo');
    if (filtrosAtivo) {
        filtrosAtivo.classList.add('d-none');
    }

    estadoPresentes.filtrosAplicados = false;
    carregarDadosPresentes(1, false);
}
//#endregion

//#region FUNÇÕES GLOBAIS E INICIALIZAÇÃO

// Funções globais para uso em botões HTML
function limparFiltrosGlobal() {
    limparFiltrosPresentes();
}

function clearSearchGlobal() {
    limparBuscaPresentes();
}

// Inicializar quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    initPresentesController();

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