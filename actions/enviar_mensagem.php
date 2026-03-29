<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'msg' => 'não logado']);
    exit;
}

if (!isset($_POST['contato_id']) || !isset($_POST['mensagem'])) {
    echo json_encode(['status' => 'error', 'msg' => 'dados incompletos']);
    exit;
}

$meuId = $_SESSION['user_id'];
$contatoId = intval($_POST['contato_id']);
$mensagem = trim($_POST['mensagem']);

require_once "../config/db.php";
require_once "../src/Chat.php";

// CRIA A CONEXÃO CORRETAMENTE
$db = new Database();
$pdo = $db->connect();

$chat = new Chat($pdo);

// Obtém ou cria o chat
$chatId = $chat->getOrCreateChat($meuId, $contatoId);

// Envia a mensagem
$ok = $chat->enviarMensagem($chatId, $meuId, $mensagem);

echo json_encode([
    'status' => $ok ? 'success' : 'error',
    'chat_id' => $chatId
]);
