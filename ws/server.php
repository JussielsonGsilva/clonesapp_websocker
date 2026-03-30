<?php
/**
 * Servidor WebSocket do projeto clonesapp_websocker.
 * Responsável por manter conexões ativas e transmitir mensagens entre usuários.
 *
 * Funções principais:
 * - Gerenciar conexões WebSocket
 * - Receber mensagens e repassar ao destinatário
 * - Atualizar status das mensagens (sent, delivered, read)
 *
 * Este arquivo deve ser executado via terminal:
 * php ws-server.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Src\WebSocketHandler;

// Mensagem inicial no terminal
echo "Iniciando servidor WebSocket...\n";

$port = 8080;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new WebSocketHandler()
        )
    ),
    $port,
    '0.0.0.0' 
);

echo "Servidor WebSocket rodando na porta {$port}...\n";

$server->run();

/**Para rodar o servidor:
 * 
  php ws/server.php

 */