<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$nomeUsuario = $_SESSION['user_nome'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClonesApp - Chat</title>

<style>
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background: #e5ddd5;
}

/* TOPO */
.topo {
    background: #075E54;
    color: white;
    padding: 15px;
    font-size: 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.topo button {
    background: #128C7E;
    border: none;
    padding: 8px 12px;
    color: white;
    border-radius: 5px;
    cursor: pointer;
}

/* CONTAINER PRINCIPAL */
.container {
    display: flex;
    height: calc(100vh - 60px);
}

/* LISTA DE CONTATOS */
.contatos {
    width: 30%;
    background: #fff;
    border-right: 1px solid #ccc;
    overflow-y: auto;
}

.contato {
    padding: 15px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.contato:hover {
    background: #f5f5f5;
}

.foto {
    width: 45px;
    height: 45px;
    background: #ccc;
    border-radius: 50%;
}

/* ÁREA DO CHAT */
.chat {
    width: 70%;
    display: flex;
    flex-direction: column;
    background: #efeae2;
}

.chat-topo {
    background: #075E54;
    color: white;
    padding: 15px;
    font-size: 16px;
}

/* CONTAINER DAS MENSAGENS */
.mensagens {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* DIVISOR DE DATA */
.divisor-data {
    text-align: center;
    margin: 10px 0;
    color: #555;
    font-size: 13px;
}

/* BALÕES DE MENSAGEM */
.msg {
    max-width: 65%;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 15px;
    line-height: 1.4;
    display: block;
}

.msg-enviada {
    background: #dcf8c6;
    margin-left: auto;
    border-bottom-right-radius: 0;
}

.msg-recebida {
    background: #ffffff;
    margin-right: auto;
    border-bottom-left-radius: 0;
}

.msg-texto {
    word-wrap: break-word;
}

.msg-hora {
    font-size: 11px;
    color: #555;
    text-align: right;
    margin-top: 4px;
}

/* CAMPO DE DIGITAÇÃO */
.input-area {
    display: flex;
    padding: 10px;
    background: #f0f0f0;
    border-top: 1px solid #ccc;
    position: sticky;
    bottom: 0;
}

.input-area input {
    flex: 1;
    padding: 10px;
    border-radius: 20px;
    border: 1px solid #ccc;
    outline: none;
    font-size: 16px;
}

.input-area button {
    margin-left: 10px;
    padding: 10px 15px;
    background: #128C7E;
    border: none;
    color: white;
    border-radius: 20px;
    cursor: pointer;
    font-size: 16px;
}

/* RESPONSIVO */
@media (max-width: 480px) {
    .container {
        flex-direction: column;
        height: auto;
    }

    .contatos {
        width: 100%;
        height: 40vh;
    }

    .chat {
        width: 100%;
        height: 60vh;
    }
}
</style>

</head>
<body data-user-id="<?= $_SESSION['user_id'] ?>">

<div class="topo">
    <div>Olá, <?= htmlspecialchars($nomeUsuario) ?></div>
    <form action="logout.php" method="POST">
        <button type="submit">Sair</button>
    </form>
</div>

<div class="container">

    <!-- LISTA DE CONTATOS -->
    <div class="contatos" id="lista-contatos"></div>

    <!-- ÁREA DO CHAT -->
    <div class="chat">
        <div class="chat-topo">
            <h2 id="nomeContato">Selecione um contato</h2>
        </div>

        <div class="mensagens" id="chatMensagens"></div>

        <div class="input-area">
            <input type="text" id="campoMensagem" placeholder="Digite uma mensagem...">
            <button id="btnEnviar">Enviar</button>
        </div>
    </div>

</div>

<script>
function carregarContatos() {
    fetch('/clonesapp_websocker/actions/buscar_contatos.php')
        .then(response => response.json())
        .then(contatos => {
            const lista = document.getElementById('lista-contatos');
            lista.innerHTML = '';

            contatos.forEach(contato => {
                const item = document.createElement('div');
                item.classList.add('contato');

                item.innerHTML = `
                    <div class="foto"></div>
                    <div class="nome">${contato.nome}</div>
                `;

                item.onclick = () => abrirChat(contato.id, contato.nome);

                // garante que clicar nos filhos também chama abrirChat
                item.querySelectorAll('*').forEach(el => {
                    el.onclick = () => abrirChat(contato.id, contato.nome);
                });

                lista.appendChild(item);
            });
        });
}

carregarContatos();

let chatAtual = null;
let contatoAtual = null;

function abrirChat(contatoId, nomeContato) {
    contatoAtual = contatoId;

    document.getElementById("nomeContato").innerText = nomeContato;

    fetch(`/clonesapp_websocker/actions/carregar_mensagens.php?contato_id=${contatoId}`)
        .then(response => response.json())
        .then(data => {
            chatAtual = data.chat_id;

            const mensagensDiv = document.getElementById("chatMensagens");
            mensagensDiv.innerHTML = "";

            let ultimaData = "";

            data.mensagens.forEach(msg => {

                let dataHora = new Date(msg.enviado_em.replace(" ", "T"));

                // NORMALIZA A DATA DA MENSAGEM
                let dataMsg = new Date(dataHora);
                dataMsg.setHours(0, 0, 0, 0);

                // NORMALIZA HOJE E ONTEM
                let hoje = new Date();
                hoje.setHours(0, 0, 0, 0);

                let ontem = new Date();
                ontem.setHours(0, 0, 0, 0);
                ontem.setDate(ontem.getDate() - 1);

                let labelData = "";

                if (dataMsg.getTime() === hoje.getTime()) {
                    labelData = "HOJE";
                } else if (dataMsg.getTime() === ontem.getTime()) {
                    labelData = "ONTEM";
                } else {
                    labelData = dataHora.toLocaleDateString("pt-BR");
                }

                // SE MUDOU A DATA, MOSTRA O DIVISOR
                if (labelData !== ultimaData) {
                    ultimaData = labelData;

                    const divisor = document.createElement("div");
                    divisor.classList.add("divisor-data");
                    divisor.innerText = labelData;

                    mensagensDiv.appendChild(divisor);
                }

                // FORMATA A HORA
                let horaFormatada = dataHora.toLocaleTimeString("pt-BR", {
                    hour: "2-digit",
                    minute: "2-digit"
                });

                // CRIA A MENSAGEM
                const div = document.createElement("div");
                div.classList.add("msg");

                if (msg.sender_id == <?= $_SESSION['user_id'] ?>) {
                    div.classList.add("msg-enviada");
                } else {
                    div.classList.add("msg-recebida");
                }

                div.innerHTML = `
                    <div class="msg-texto">${msg.conteudo}</div>
                    <div class="msg-hora">${horaFormatada}</div>
                `;

                mensagensDiv.appendChild(div);
            });

            mensagensDiv.scrollTop = mensagensDiv.scrollHeight;
        });
}
</script>
<script>
    const USER_ID = <?= $_SESSION['user_id'] ?>;
</script>

<script src="public/js/chat.js"></script>
<script src="public/js/websocket.js"></script>
</body>
</html>