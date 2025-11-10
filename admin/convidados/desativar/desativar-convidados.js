// desativar-convidados.js

//#region VARIÁVEIS GLOBAIS
let codigoConvidado = null;
//#endregion

//#region INICIALIZAÇÃO
function initDesativarConvidado() {
    obterParametroUrl();
    vincularEventosForm();

    if (codigoConvidado) {
        carregarDadosConvidado();
    } else {
        mostrarErro('Código do convidado não informado');
    }
}

function obterParametroUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    const cd = urlParams.get('cd');

    if (cd) {
        codigoConvidado = cd;
        document.getElementById('codigo_convidado').value = cd;
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
async function carregarDadosConvidado() {
    mostrarLoading();

    try {
        const response = await fetch(`/admin/convidados/api/obter_por_codigo.php?cd=${codigoConvidado}`);
        const data = await response.json();

        if (data.success) {
            exibirInformacoesConvidado(data.data);
        } else {
            throw new Error(data.error || 'Erro ao carregar dados do convidado');
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
            codigo_convidado: codigoConvidado,
            usuario_desativacao: 'admin' // Ou pegar do usuário logado
        };

        const response = await fetch('/admin/convidados/api/desativar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            mostrarSucesso('Convidado desativado com sucesso!');
            setTimeout(() => {
                window.location.href = '../listar/listar-convidados.html.php';
            }, 2000);
        } else {
            throw new Error(data.error || 'Erro ao desativar convidado');
        }
    } catch (error) {
        mostrarErro('Erro ao desativar: ' + error.message);
    } finally {
        esconderLoading();
    }
}
//#endregion

//#region FUNÇÕES DE UI

function getStatusConvidado(convidado) {

    let cor = '';
    let status = '';

    switch (convidado.bl_confirmacao) {
        case null:
            cor = 'warning';
            status = 'Pendente';
            break;
        case '0':
            cor = 'danger';
            status = 'Não confirmado';
            break;
        case '1':
            cor = 'success';
            status = 'Confirmado';
            break;
    }

    return (
        `<div class="col-12">
                    <strong>Status:</strong> 
                    <span class="badge bg-${cor}">
                      ${status}
                    </span>
          </div>`
    );
}
function exibirInformacoesConvidado(convidado) {

    const infoContainer = document.getElementById('infoConvidado');

    if (convidado) {
        infoContainer.innerHTML = `
            <div class="row">
                <div class="col-12">
                    <strong>Nome:</strong> ${convidado.tx_nome_convidado || 'Não informado'}
                </div>
                <div class="col-12">
                    <strong>Telefone:</strong> ${convidado.tx_telefone_convidado || 'Não informado'}
                </div>
                <div class="col-12">
                    <strong>Quantidade de Pessoas:</strong> ${convidado.nu_qtd_pessoas || 1}
                </div>
                <div class="col-12">
                    <strong>Data de Registro:</strong> ${formatarData(convidado.dt_registro)}
                </div>
                ${getStatusConvidado(convidado)}
            </div>
        `;
    } else {
        infoContainer.innerHTML = '<p class="text-danger">Convidado não encontrado</p>';
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
    return data.toLocaleDateString('pt-BR');
}
//#endregion

//#region INICIALIZAÇÃO
document.addEventListener('DOMContentLoaded', function () {
    initDesativarConvidado();
});
//#endregion