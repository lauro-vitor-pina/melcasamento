// desativar-presentes.js

//#region VARIÁVEIS GLOBAIS
let codigoPresente = null;
//#endregion

//#region INICIALIZAÇÃO
function initDesativarPresente() {
    obterParametroUrl();
    vincularEventosForm();

    if (codigoPresente) {
        carregarDadosPresente();
    } else {
        mostrarErro('Código do presente não informado');
    }
}

function obterParametroUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    const cd = urlParams.get('cd');

    if (cd) {
        codigoPresente = cd;
        document.getElementById('codigo_presente').value = cd;
    }
}

function vincularEventosForm() {
    const form = document.getElementById('formDesativar');
    if (form) {
        form.addEventListener('submit', function (e) {
            confirmarDesativacao(e);
        });
    }
}
//#endregion

//#region FUNÇÕES DE API
async function carregarDadosPresente() {
    mostrarLoading();

    try {
        const response = await fetch(`/admin/presentes/api/obter_por_codigo.php?cd=${codigoPresente}`);
        const data = await response.json();

        if (data.success) {
            exibirInformacoesPresente(data.data);
        } else {
            throw new Error(data.error || 'Erro ao carregar dados do presente');
        }
    } catch (error) {
        mostrarErro('Erro ao carregar dados: ' + error.message);
        document.getElementById('btnConfirmar').disabled = true;
    } finally {
        esconderLoading();
    }
}

async function confirmarDesativacao(event) {
    event.preventDefault();

    mostrarLoading();

    try {
        const formData = {
            codigo_presente: codigoPresente,
            usuario_desativacao: 'admin' // Ou pegar do usuário logado
        };

        const response = await fetch('/admin/presentes/api/desativar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            mostrarSucesso('Presente desativado com sucesso!');
            setTimeout(() => {
                window.location.href = '../listar/listar-presentes.html.php';
            }, 2000);
        } else {
            throw new Error(data.error || 'Erro ao desativar presente');
        }
    } catch (error) {
        mostrarErro('Erro ao desativar: ' + error.message);
    } finally {
        esconderLoading();
    }
}
//#endregion

//#region FUNÇÕES DE UI

function getStatusPresente(presente) {
    const estaReservado = presente.codigo_convidado !== null;
    let cor = '';
    let status = '';
    let icone = '';

    if (estaReservado) {
        cor = 'info';
        status = 'Reservado';
        icone = 'bookmark-check';
    } else {
        cor = 'success';
        status = 'Disponível';
        icone = 'gift';
    }

    return `
        <div class="col-12">
            <strong>Status:</strong> 
            <span class="badge bg-${cor}">
                <i class="bi bi-${icone} me-1"></i>
                ${status}
            </span>
        </div>
    `;
}

function getFotoPresente(presente) {
    if (presente.tx_foto_presente && presente.tx_foto_presente.trim() !== '') {
        const caminhoFoto = `/admin/presentes/api/../../../_uploads/presentes/${presente.tx_foto_presente}`;
        return `
            <div class="col-12 text-center mb-3">
                <strong>Foto do Presente:</strong>
                <div class="mt-2">
                    <img src="${caminhoFoto}" 
                         alt="${escapeHtml(presente.tx_nome_presente)}"
                         class="img-fluid rounded"
                         style="max-height: 200px; max-width: 100%;"
                         onerror="this.style.display='none'">
                </div>
            </div>
        `;
    } else {
        return `
            <div class="col-12 text-center mb-3">
                <strong>Foto do Presente:</strong>
                <div class="mt-2">
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                         style="height: 120px; width: 100%;">
                        <i class="bi bi-image text-muted display-6"></i>
                    </div>
                    <small class="text-muted">Nenhuma foto cadastrada</small>
                </div>
            </div>
        `;
    }
}

function getConvidadoReserva(presente) {
    if (presente.codigo_convidado && presente.tx_nome_convidado) {
        return `
            <div class="col-12">
                <strong>Reservado por:</strong> ${escapeHtml(presente.tx_nome_convidado)}
            </div>
            ${presente.tx_telefone_convidado ? `
                <div class="col-12">
                    <strong>Telefone:</strong> ${escapeHtml(presente.tx_telefone_convidado)}
                </div>
            ` : ''}
        `;
    }
    return '';
}

function exibirInformacoesPresente(presente) {
    const infoContainer = document.getElementById('infoPresente');

    if (presente) {
        infoContainer.innerHTML = `
            <div class="row">
                ${getFotoPresente(presente)}
                <div class="col-12">
                    <strong>Nome:</strong> ${escapeHtml(presente.tx_nome_presente || 'Não informado')}
                </div>
                ${presente.tx_descricao_presente ? `
                    <div class="col-12">
                        <strong>Descrição:</strong> ${escapeHtml(presente.tx_descricao_presente)}
                    </div>
                ` : ''}
                ${getConvidadoReserva(presente)}
                <div class="col-12">
                    <strong>Data de Registro:</strong> ${formatarData(presente.dt_registro)}
                </div>
                ${presente.dt_atualizacao ? `
                    <div class="col-12">
                        <strong>Última Atualização:</strong> ${formatarData(presente.dt_atualizacao)}
                    </div>
                ` : ''}
                ${getStatusPresente(presente)}
            </div>
        `;
    } else {
        infoContainer.innerHTML = '<p class="text-danger">Presente não encontrado</p>';
        document.getElementById('btnConfirmar').disabled = true;
    }
}

function mostrarLoading() {
    document.getElementById('loadingOverlay').style.display = 'flex';
    document.getElementById('btnConfirmar').disabled = true;
}

function esconderLoading() {
    document.getElementById('loadingOverlay').style.display = 'none';
    document.getElementById('btnConfirmar').disabled = false;
}

function mostrarSucesso(mensagem) {
    mostrarMensagem(mensagem, 'success');
}

function mostrarErro(mensagem) {
    mostrarMensagem(mensagem, 'danger');
}

function mostrarMensagem(mensagem, tipo) {
    const mensagensAntigas = document.querySelectorAll('.alert-message');
    mensagensAntigas.forEach(msg => msg.remove());

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show alert-message`;
    alertDiv.innerHTML = `
        ${mensagem}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    const card = document.querySelector('.card');
    if (card) {
        card.parentNode.insertBefore(alertDiv, card);
    }
}

function formatarData(dataString) {
    const data = new Date(dataString);
    return data.toLocaleDateString('pt-BR') + ' ' + data.toLocaleTimeString('pt-BR');
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
//#endregion

//#region INICIALIZAÇÃO
document.addEventListener('DOMContentLoaded', function () {
    initDesativarPresente();
});
//#endregion