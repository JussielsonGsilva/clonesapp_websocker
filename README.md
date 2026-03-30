# clonesapp_websocker

Projeto de chat em tempo real inspirado no WhatsApp, utilizando PHP, MySQL e WebSocket.
## Estrutura
- Login e cadastro
- Lista de contatos
- Conversas individuais
- Mensagens em tempo real via WebSocket

## Tecnologias
- PHP 8+
- MySQL
- Ratchet WebSocket
- HTML/CSS/JS

## Como iniciar
1. Importe o arquivo `database.sql`
2. Configure o banco em `config/db.php`
3. Instale dependências com Composer

🧱 1. Estrutura inicial do projeto
clonesapp_websocker/
│
├── index.php
├── register.php
├── home.php
│
├── ws-server.php
│
├── config/
│   └── db.php
│
├── src/
│   ├── Auth.php
│   ├── User.php
│   ├── Chat.php
│   └── WebSocketHandler.php
│
├── public/
│   ├── css/
│   ├── js/
│   └── img/
│
├── database.sql
├── requirements.txt
└── README.md
├── login.php
├── logout.php

5. Acesse o projeto via navegador:
http://localhost/clonesapp_websocker/


---

## 🚀 O que foi configurado até agora

### ✔ 1. Criamos o servidor WebSocket (`ws/server.php`)

- Carrega o autoload do Composer
- Inicializa o Ratchet
- Sobe o servidor na porta **8080**
- Instancia o `WebSocketHandler`
- Exibe logs no terminal

### ✔ 2. Criamos o WebSocketHandler (`src/WebSocketHandler.php`)

O handler é responsável por:

- Registrar conexões
- Associar `user_id` à conexão
- Receber mensagens do cliente
- Repassar mensagens ao destinatário correto
- Remover usuários desconectados
- Exibir logs no terminal

### ✔ 3. Corrigimos erro do `SplObjectStorage`

O erro acontecia porque o servidor estava sendo executado com o **PHP do sistema**, que não tinha SPL ativa.

A solução foi rodar o servidor usando o PHP do LAMPP:

O PHP do LAMPP possui SPL ativa, permitindo o uso de:

- `SplObjectStorage`
- `attach()`
- `detach()`

---

Esse comando usa o PHP do LAMPP, que:
tem SPL ativa
reconhece SplObjectStorage
executa o Ratchet corretamente
evita o erro no attach()

🔥 1. Integrar o WebSocket com o frontend (JS)
abrir a conexão WebSocket no navegador
registrar o usuário conectado
enviar mensagens pelo WebSocket
receber mensagens em tempo real
atualizar a interface sem refresh
Isso transforma o chat em algo vivo, instantâneo.

📌 Arquivo a editar
public/js/websocket.js 

dentro do arquivo home.php
antes de </body>
<script>
    const USER_ID = <?= $_SESSION['user_id'] ?>;
</script>

colocar--> <script src="/public/js/websocket.js"></script>

⚠️ IMPORTANTE:  
Para testar em PC + celular, você deve usar o IP da sua máquina, não localhost.

const socket = new WebSocket("ws://seu_ip:8080");



## ▶️ Como iniciar o servidor WebSocket
No terminal, dentro da pasta do projeto:
/opt/lampp/bin/php ws/server.php