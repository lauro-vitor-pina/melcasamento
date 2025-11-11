// /admin/presentes/registrar/registrar-presente.js

//#region VARIÁVEIS GLOBAIS
let modoEdicao = false;
let codigoPresente = null;
let fotoAtualPresente = null;
//#endregion

//#region INICIALIZAÇÃO
function initPresenteForm() {
    obterParametroUrl();
    vincularEventosForm();

    if (modoEdicao) {
        carregarDadosPresente();
    }
}

function obterParametroUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    const cd = urlParams.get('cd');

    if (cd) {
        modoEdicao = true;
        codigoPresente = cd;
        document.getElementById('codigo_presente').value = cd;
        document.getElementById('btnText').textContent = 'Atualizar Presente';
    }
}

function vincularEventosForm() {
    const form = document.getElementById('formPresente');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            salvarPresente();
        });
    }

    // Evento para remover foto
    const btnRemoverFoto = document.getElementById('btn-remover-foto');
    if (btnRemoverFoto) {
        btnRemoverFoto.addEventListener('click', function() {
            removerFotoAtual();
        });
    }
}
//#endregion

//#region FUNÇÕES DE FORMULÁRIO
function validarFormulario() {
    const form = document.getElementById('formPresente');
    const nomeInput = document.getElementById('tx_nome_presente');

    if (!nomeInput.value.trim()) {
        nomeInput.classList.add('is-invalid');
        return false;
    }

    form.classList.add('was-validated');
    return form.checkValidity();
}

function obterDadosFormulario() {
    const form = document.getElementById('formPresente');
    const formData = new FormData(form);
    
    // Se estamos em modo edição e a foto foi removida, adicionar flag
    if (modoEdicao && fotoAtualPresente === null) {
        formData.append('remover_foto', '1');
    }
    
    return formData;
}

function preencherFormulario(presente) {
    document.getElementById('tx_nome_presente').value = presente.tx_nome_presente || '';
    document.getElementById('tx_descricao_presente').value = presente.tx_descricao_presente || '';
    
    // CORREÇÃO: Não preencher o input file, mas sim exibir a foto atual
    if (presente.tx_foto_presente && presente.tx_foto_presente.trim() !== '') {
        fotoAtualPresente = presente.tx_foto_presente;
        document.getElementById('tx_foto_presente_atual').value = presente.tx_foto_presente;
        exibirFotoAtual(presente.tx_foto_presente);
    }
}

function exibirFotoAtual(nomeFoto) {
    const container = document.getElementById('foto-atual-container');
    const img = document.getElementById('foto-atual-img');
    
    if (container && img) {
        const caminhoFoto = `/admin/presentes/api/../../../_uploads/presentes/${nomeFoto}`;
        img.src = caminhoFoto;
        img.alt = 'Foto atual do presente';
        container.style.display = 'block';
    }
}

function removerFotoAtual() {
    const container = document.getElementById('foto-atual-container');
    const inputFotoAtual = document.getElementById('tx_foto_presente_atual');
    
    if (container && inputFotoAtual) {
        container.style.display = 'none';
        inputFotoAtual.value = '';
        fotoAtualPresente = null;
        
        mostrarSucesso('Foto removida. A foto será excluída ao salvar.');
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
            preencherFormulario(data.data);
        } else {
            throw new Error(data.error || 'Erro ao carregar dados do presente');
        }
    } catch (error) {
        console.error('Erro ao carregar dados:', error);
        mostrarErro('Erro ao carregar dados: ' + error.message);
    } finally {
        esconderLoading();
    }
}

async function salvarPresente() {
    if (!validarFormulario()) {
        return;
    }

    mostrarLoading();

    try {
        const formData = obterDadosFormulario();
        const endpoint = modoEdicao ? '/admin/presentes/api/editar.php' : '/admin/presentes/api/inserir.php';

        const response = await fetch(endpoint, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            mostrarSucesso(modoEdicao ? 'Presente atualizado com sucesso!' : 'Presente cadastrado com sucesso!');
            setTimeout(() => {
                window.location.href = '../listar/listar-presentes.html.php';
            }, 1500);
        } else {
            throw new Error(data.error || 'Erro ao salvar presente');
        }
    } catch (error) {
        mostrarErro('Erro ao salvar: ' + error.message);
    } finally {
        esconderLoading();
    }
}
//#endregion

//#region FUNÇÕES DE UI
function mostrarLoading() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    const btnSalvar = document.getElementById('btnSalvar');

    if (loadingOverlay) loadingOverlay.style.display = 'flex';
    if (btnSalvar) btnSalvar.disabled = true;
}

function esconderLoading() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    const btnSalvar = document.getElementById('btnSalvar');

    if (loadingOverlay) loadingOverlay.style.display = 'none';
    if (btnSalvar) btnSalvar.disabled = false;
}

function mostrarSucesso(mensagem) {
    mostrarMensagem(mensagem, 'success');
}

function mostrarErro(mensagem) {
    mostrarMensagem(mensagem, 'danger');
}

function mostrarMensagem(mensagem, tipo) {
    // Remove mensagens anteriores
    const mensagensAntigas = document.querySelectorAll('.alert-message');
    mensagensAntigas.forEach(msg => msg.remove());

    // Cria nova mensagem
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show alert-message`;
    alertDiv.innerHTML = `
        ${mensagem}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Insere antes do formulário
    const form = document.getElementById('formPresente');
    if (form) {
        form.parentNode.insertBefore(alertDiv, form);
    }
}
//#endregion

//#region INICIALIZAÇÃO E FUNÇÕES GLOBAIS
// Inicializar quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', function () {
    initPresenteForm();
});
//#endregion