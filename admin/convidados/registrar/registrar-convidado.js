// admin/convidados/registrar/registrar-convidado.js

//#region VARIÁVEIS GLOBAIS
let modoEdicao = false;
let codigoConvidado = null;
//#endregion

//#region INICIALIZAÇÃO
function initConvidadoForm() {
    obterParametroUrl();
    vincularEventosForm();

    if (modoEdicao) {
        carregarDadosConvidado();
    }
}

function obterParametroUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    const cd = urlParams.get('cd');

    if (cd) {
        modoEdicao = true;
        codigoConvidado = cd;
        document.getElementById('codigo_convidado').value = cd;
        document.getElementById('btnText').textContent = 'Atualizar Convidado';
        document.getElementById('campo-mensagem-enviada').style.display = 'block';
    }
}

function vincularEventosForm() {
    const form = document.getElementById('formConvidado');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            salvarConvidado();
        });
    }

    const telefoneInput = document.getElementById('tx_telefone_convidado');

    if (telefoneInput) {
        telefoneInput.addEventListener('input', aplicarMascaraTelefone);
    }

    if (modoEdicao) {
        telefoneInput.addEventListener('input', atualizarEstadoBotoesWhatsApp);
    }
}
//#endregion

//#region FUNÇÕES DE FORMULÁRIO
function aplicarMascaraTelefone(e) {
    let value = e.target.value.replace(/\D/g, '');

    if (value.length <= 11) {
        value = value.replace(/(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
    }

    e.target.value = value;
}

function validarFormulario() {
    const form = document.getElementById('formConvidado');
    const nomeInput = document.getElementById('tx_nome_convidado');

    if (!nomeInput.value.trim()) {
        nomeInput.classList.add('is-invalid');
        return false;
    }

    form.classList.add('was-validated');
    return form.checkValidity();
}

function obterDadosFormulario() {
    const form = document.getElementById('formConvidado');
    const formData = new FormData(form);
    const dados = {};

    for (let [key, value] of formData.entries()) {
        if (key === 'nu_qtd_pessoas') {
            dados[key] = parseInt(value);
        } else if (key === 'bl_mensagem_enviada') {
            dados[key] = value === 'on';
        } else {
            dados[key] = value;
        }
    }

    return dados;
}

function preencherFormulario(convidado) {
    document.getElementById('tx_nome_convidado').value = convidado.tx_nome_convidado || '';
    document.getElementById('tx_telefone_convidado').value = convidado.tx_telefone_convidado || '';
    document.getElementById('nu_qtd_pessoas').value = convidado.nu_qtd_pessoas || 2;

    if (convidado.bl_mensagem_enviada === '1') {
        document.getElementById('bl_mensagem_enviada').checked = true;
    } else {
        document.getElementById('bl_mensagem_enviada').checked = false;
    }
}


function configurarBotoesWhatsApp() {
    // Só configura os botões se estiver no modo edição
    if (!modoEdicao) return;

    const btnCopiarMensagem = document.getElementById('btnCopiarMensagem');
    const btnWhatsApp = document.getElementById('btnWhatsApp');
    const botoesContainer = document.getElementById('botoesWhatsAppContainer');

    if (btnCopiarMensagem && btnWhatsApp && botoesContainer) {
        // Configurar clique do botão Copiar Mensagem
        btnCopiarMensagem.addEventListener('click', function () {
            copiarMensagemPersonalizada();
        });

        // Configurar clique do botão WhatsApp
        btnWhatsApp.addEventListener('click', function () {
            abrirWhatsAppForm();
        });

        // Verificar se deve habilitar/desabilitar botões baseado no telefone
        atualizarEstadoBotoesWhatsApp();
    }
}

function atualizarEstadoBotoesWhatsApp() {
    if (!modoEdicao) return;

    const telefoneInput = document.getElementById('tx_telefone_convidado');
    const btnCopiarMensagem = document.getElementById('btnCopiarMensagem');
    const btnWhatsApp = document.getElementById('btnWhatsApp');

    if (telefoneInput && btnCopiarMensagem && btnWhatsApp) {

        const telefone = telefoneInput.value.replace(/\D/g, '');
        const temTelefoneValido = telefone.length >= 10;

        // Habilitar/desabilitar botões baseado no telefone
        btnCopiarMensagem.disabled = !temTelefoneValido;
        btnWhatsApp.disabled = !temTelefoneValido;

        // Adicionar tooltips para explicar quando desabilitado
        if (temTelefoneValido) {
            btnCopiarMensagem.title = 'Copiar mensagem personalizada para o convidado';
            btnWhatsApp.title = `Abrir WhatsApp para ${telefoneInput.value}`;
        } else {
            btnCopiarMensagem.title = 'Digite um telefone válido para habilitar';
            btnWhatsApp.title = 'Digite um telefone válido para habilitar';
        }
    }
}

function gerarMensagemPersonalizada() {
    const nomeInput = document.getElementById('tx_nome_convidado');
    const nome = nomeInput.value || 'Convidado';

    // Mensagem personalizada - você pode customizar esta mensagem
    const mensagem = `Olá ${nome}! Tudo bem? 🌸

Gostaríamos de confirmar sua presença no nosso casamento! 

💒 *Data:* [INSERIR DATA]
📍 *Local:* [INSERIR LOCAL]
⏰ *Horário:* [INSERIR HORÁRIO]

Por favor, nos confirme sua presença até [INSERIR DATA LIMITE].

Aguardamos você para celebrar conosco este momento especial! 💕

Com carinho,
[SEUS NOMES]`;

    return mensagem;
}

async function copiarMensagemPersonalizada() {
    // Verificar se tem telefone válido antes de copiar
    const telefoneInput = document.getElementById('tx_telefone_convidado');
    const telefone = telefoneInput.value.replace(/\D/g, '');

    if (telefone.length < 10) {
        mostrarErro('❌ Digite um telefone válido antes de copiar a mensagem');
        return;
    }

    const mensagem = gerarMensagemPersonalizada();

    try {

        await navigator.clipboard.writeText(mensagem);

        mostrarSucesso('✅ Mensagem copiada com sucesso! Agora clique em "Abrir WhatsApp" para enviar.');

        // Destacar o botão do WhatsApp após copiar
        const btnWhatsApp = document.getElementById('btnWhatsApp');
        if (btnWhatsApp) {
            btnWhatsApp.focus();
            btnWhatsApp.style.boxShadow = '0 0 0 0.2rem rgba(25, 135, 84, 0.25)';
            setTimeout(() => {
                btnWhatsApp.style.boxShadow = '';
            }, 2000);
        }
    } catch (error) {
        // Fallback para navegadores mais antigos
        const textArea = document.createElement('textarea');
        textArea.value = mensagem;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);

        mostrarSucesso('✅ Mensagem copiada com sucesso! Agora clique em "Abrir WhatsApp" para enviar.');
    }
}

function abrirWhatsAppForm() {
    const telefoneInput = document.getElementById('tx_telefone_convidado');

    if (!telefoneInput || !telefoneInput.value) {
        mostrarErro('❌ Digite um telefone válido para abrir o WhatsApp');
        return;
    }

    const telefone = telefoneInput.value.replace(/\D/g, '');

    if (telefone.length < 10) {
        mostrarErro('❌ Telefone inválido. Digite um número com DDD.');
        return;
    }

    // URL do WhatsApp sem mensagem (apenas abre a conversa)
    const urlWhatsApp = `https://wa.me/55${telefone}`;

    // Abrir em nova aba
    window.open(urlWhatsApp, '_blank');
}

//#endregion

//#region FUNÇÕES DE API
async function carregarDadosConvidado() {
    mostrarLoading();

    try {
        const response = await fetch(`/admin/convidados/api/obter_por_codigo.php?cd=${codigoConvidado}`);
        const data = await response.json();

        if (data.success) {
            preencherFormulario(data.data);
            configurarBotoesWhatsApp(); // Configurar botões WhatsApp (só na edição)
        } else {
            throw new Error(data.error || 'Erro ao carregar dados do convidado');
        }
    } catch (error) {
        mostrarErro('Erro ao carregar dados: ' + error.message);
    } finally {
        esconderLoading();
    }
}

async function salvarConvidado() {
    if (!validarFormulario()) {
        return;
    }

    mostrarLoading();

    try {
        const formData = obterDadosFormulario();
        const endpoint = modoEdicao ? '/admin/convidados/api/editar.php' : '/admin/convidados/api/inserir.php';

        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            mostrarSucesso(modoEdicao ? 'Convidado atualizado com sucesso!' : 'Convidado cadastrado com sucesso!');
            setTimeout(() => {
                window.location.href = '../listar/listar-convidados.html.php';
            }, 1500);
        } else {
            throw new Error(data.error || 'Erro ao salvar convidado');
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
    const form = document.getElementById('formConvidado');
    if (form) {
        form.parentNode.insertBefore(alertDiv, form);
    }
}
//#endregion

//#region INICIALIZAÇÃO E FUNÇÕES GLOBAIS
// Inicializar quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', function () {
    initConvidadoForm();
});


//#endregion