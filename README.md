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
4. Inicie o servidor WebSocket: ws/server.php --> php ws-server.php

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

