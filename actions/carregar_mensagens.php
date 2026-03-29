<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

if (!isset($_GET['contato_id'])) {
    echo json_encode([]);
    exit;
}

$meuId = $_SESSION['user_id'];
$contatoId = intval($_GET['contato_id']);

require_once "../config/db.php";
require_once "../src/Chat.php";

$chat = new Chat($pdo);

// Obtém ou cria o chat entre os dois usuários
$chatId = $chat->getOrCreateChat($meuId, $contatoId);

// Carrega as mensagens
$mensagens = $chat->carregarMensagens($chatId);

// Retorna também o chat_id
echo json_encode([
    "chat_id" => $chatId,
    "mensagens" => $mensagens
]);