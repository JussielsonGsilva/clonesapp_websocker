let contatoSelecionado = null;
let meuId = document.body.getAttribute("data-user-id");

// Quando clicar em um contato
function abrirChat(contatoId, nome) {
    contatoSelecionado = contatoId;

    document.getElementById("nomeContato").innerText = nome;

    carregarMensagens();
}

// Carregar mensagens do backend
function carregarMensagens() {
    if (!contatoSelecionado) return;

    fetch(`/clonesapp_websocker/actions/carregar_mensagens.php?contato_id=${contatoSelecionado}`)
        .then(r => r.json())
        .then(data => {
            const area = document.getElementById("chatMensagens");
            area.innerHTML = "";

            data.mensagens.forEach(msg => {
                adicionarMensagemNaTela(msg);
            });

            scrollChatParaBaixo();
        });
}

// Enviar mensagem
document.getElementById("btnEnviar").addEventListener("click", enviarMensagem);

function enviarMensagem() {
    const input = document.getElementById("campoMensagem");
    const texto = input.value.trim();

    if (texto === "" || !contatoSelecionado) return;

    const formData = new FormData();
    formData.append("contato_id", contatoSelecionado);
    formData.append("mensagem", texto);

    fetch("/clonesapp_websocker/actions/enviar_mensagem.php", {
        method: "POST",
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === "success") {

            adicionarMensagemNaTela({
                sender_id: meuId,
                conteudo: texto,
                enviado_em: new Date().toISOString()
            });

            input.value = "";
            scrollChatParaBaixo();
        }
    });
}

// Adicionar mensagem na tela
function adicionarMensagemNaTela(msg) {
    const area = document.getElementById("chatMensagens");

    const div = document.createElement("div");
    div.classList.add("msg");

    if (msg.sender_id == meuId) {
        div.classList.add("msg-enviada");
    } else {
        div.classList.add("msg-recebida");
    }

    div.innerHTML = `
        <div class="msg-texto">${msg.conteudo}</div>
        <div class="msg-hora">${new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}</div>
    `;

    area.appendChild(div);
}

// Scroll automático
function scrollChatParaBaixo() {
    const area = document.getElementById("chatMensagens");
    area.scrollTop = area.scrollHeight;
}
