<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error']);
    exit;
}

if (!isset($_POST['contato_id']) || !isset($_POST['mensagem'])) {
    echo json_encode(['status' => 'error']);
    exit;
}

$meuId = $_SESSION['user_id'];
$contatoId = intval($_POST['contato_id']);
$mensagem = trim($_POST['mensagem']);

require_once "../config/db.php";
require_once "../src/Chat.php";

$chat = new Chat($pdo);

// Obtém ou cria o chat
$chatId = $chat->getOrCreateChat($meuId, $contatoId);

// Envia a mensagem
$ok = $chat->enviarMensagem($chatId, $meuId, $mensagem);

echo json_encode(['status' => $ok ? 'success' : 'error']);