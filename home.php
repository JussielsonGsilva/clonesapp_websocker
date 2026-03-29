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

    .mensagens {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
    }

    .msg {
        max-width: 60%;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 8px;
        font-size: 14px;
    }

    .msg-enviada {
        background: #dcf8c6;
        margin-left: auto;
    }

    .msg-recebida {
        background: #fff;
        margin-right: auto;
    }

    /* CAMPO DE DIGITAÇÃO — AJUSTADO PARA iPHONE */
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
    @media (max-width: 768px) {
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
<body>

<div class="topo">
    <div>Olá, <?= htmlspecialchars($nomeUsuario) ?></div>
    <form action="logout.php" method="POST">
        <button type="submit">Sair</button>
    </form>
</div>

<div class="container">

    <!-- LISTA DE CONTATOS (AGORA DINÂMICA) -->
    <div class="contatos" id="lista-contatos"></div>

    <!-- ÁREA DO CHAT -->
    <div class="chat">
        <div class="chat-topo" id="chat-topo">Selecione um contato</div>

        <div class="mensagens" id="mensagens"></div>

        <div class="input-area">
            <input type="text" id="campo-msg" placeholder="Digite uma mensagem...">
            <button id="btn-enviar">Enviar</button>
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
                    <div>${contato.nome}</div>
                `;

                item.onclick = () => abrirChat(contato.id, contato.nome);

                lista.appendChild(item);
            });
        });
}

// Carrega ao abrir a página
carregarContatos();

// Função para abrir o chat e carregar mensagens
let chatAtual = null;
let contatoAtual = null;

function abrirChat(contatoId, nomeContato) {
    contatoAtual = contatoId;

    document.getElementById("chat-topo").innerText = nomeContato;

    fetch(`/clonesapp_websocker/actions/carregar_mensagens.php?contato_id=${contatoId}`)
        .then(response => response.json())
        .then(data => {
            chatAtual = data.chat_id;

            const mensagensDiv = document.getElementById("mensagens");
            mensagensDiv.innerHTML = "";

            data.mensagens.forEach(msg => {
                const div = document.createElement("div");
                div.classList.add("msg");

                if (msg.sender_id == <?= $_SESSION['user_id'] ?>) {
                    div.classList.add("msg-enviada");
                } else {
                    div.classList.add("msg-recebida");
                }

                div.innerText = msg.conteudo;
                mensagensDiv.appendChild(div);
            });

            mensagensDiv.scrollTop = mensagensDiv.scrollHeight;
        });
}

</script>

</body>
</html>