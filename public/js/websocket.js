// 🔥 IMPORTANTE: Troque pelo IP da sua máquina na rede local
// Exemplo: ws://192.168.0.10:8080
const socket = new WebSocket("ws://192.168.101.7:8080");

// Quando conectar
socket.onopen = () => {
    console.log("Conectado ao WebSocket!");

    // Registrar o usuário no servidor
    socket.send(JSON.stringify({
        acao: "registrar",
        user_id: USER_ID
    }));
};

// Quando receber mensagem
socket.onmessage = (event) => {
    const data = JSON.parse(event.data);
    console.log("Mensagem recebida:", data);

    // 🔥 1. Se for confirmação de entrega (✓✓)
    if (data.acao === "mensagem_entregue") {
        marcarMensagemComoEntregue(data.message_id);
        return;
    }

    // 🔥 2. Só processa mensagens novas
    if (data.acao !== "nova_mensagem") return;

    // Se a mensagem não é para mim, ignora
    if (data.receiver_id != USER_ID) return;

    // Se a mensagem é do contato que estou conversando AGORA
    if (data.sender_id == contatoAtual) {
        adicionarMensagemNaTela({
            sender_id: data.sender_id,
            conteudo: data.conteudo,
            enviado_em: new Date().toISOString(),
            message_id: data.message_id
        });

        scrollChatParaBaixo();
    }
};

// Quando desconectar
socket.onclose = () => {
    console.log("WebSocket desconectado");
};

// Quando der erro
socket.onerror = (error) => {
    console.log("Erro no WebSocket:", error);
};
