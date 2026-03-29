<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["erro" => "não logado"]);
    exit;
}

require_once "../config/db.php";
require_once "../src/Chat.php";

$db = new Database();
$pdo = $db->connect();

$chatModel = new Chat($pdo);

$meuId = $_SESSION['user_id'];
$contatoId = $_GET['contato_id'] ?? null;

if (!$contatoId) {
    echo json_encode(["erro" => "contato inválido"]);
    exit;
}

$resultado = $chatModel->carregarMensagens($meuId, $contatoId);

echo json_encode($resultado);