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

