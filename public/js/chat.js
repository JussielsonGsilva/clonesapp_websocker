let meuId = document.body.getAttribute("data-user-id");

// Enviar mensagem
document.getElementById("btnEnviar").addEventListener("click", enviarMensagem);

function enviarMensagem() {
    const input = document.getElementById("campoMensagem");
    const texto = input.value.trim();

    if (texto === "" || !contatoAtual) return;

    input.value = "";

    // 1. Salvar no banco primeiro
    const formData = new FormData();
    formData.append("contato_id", contatoAtual);
    formData.append("mensagem", texto);

    fetch("/clonesapp_websocker/actions/enviar_mensagem.php", {
        method: "POST",
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === "success") {

            // 2. Enviar para o WebSocket COM message_id
            socket.send(JSON.stringify({
                acao: "enviar_mensagem",
                message_id: res.message_id,   // <-- ESSENCIAL
                sender_id: meuId,
                receiver_id: contatoAtual,
                conteudo: texto
            }));

            // 3. Mostrar na tela imediatamente
            adicionarMensagemNaTela({
                sender_id: meuId,
                conteudo: texto,
                enviado_em: new Date().toISOString(),
                message_id: res.message_id   // <-- ESSENCIAL PARA O ✓✓
            });

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

    // AQUI: guardar o ID da mensagem
    if (msg.message_id) {
        div.setAttribute("data-id", msg.message_id);
    }

    div.innerHTML = `
        <div class="msg-texto">${msg.conteudo}</div>
        <div class="msg-hora">
            ${new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}
            <span class="msg-status"></span>
        </div>
    `;

    area.appendChild(div);
}

function marcarMensagemComoEntregue(messageId) {
    const msg = document.querySelector(`.msg-enviada[data-id="${messageId}"]`);
    if (!msg) {
        console.log("Mensagem não encontrada para marcar como entregue:", messageId);
        return;
    }

    let status = msg.querySelector(".msg-status");

    // Se não existir, cria
    if (!status) {
        status = document.createElement("span");
        status.classList.add("msg-status");
        msg.querySelector(".msg-hora").appendChild(status);
    }

    status.textContent = "✓✓";
}

// Scroll automático
function scrollChatParaBaixo() {
    const area = document.getElementById("chatMensagens");
    area.scrollTop = area.scrollHeight;
}

// Receber mensagens do WebSocket
socket.onmessage = function(e) {
    const data = JSON.parse(e.data);

    // Quando chega uma nova mensagem
    if (data.acao === "nova_mensagem") {
        adicionarMensagemNaTela({
            sender_id: data.sender_id,
            conteudo: data.conteudo,
            message_id: data.message_id
        });

        scrollChatParaBaixo();
    }

    // Quando o servidor confirma que a mensagem foi entregue (✓✓)
    if (data.acao === "mensagem_entregue") {
        marcarMensagemComoEntregue(data.message_id);
    }
};
