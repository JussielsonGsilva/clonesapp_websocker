<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

require_once "../config/db.php";
require_once "../src/User.php";

// CRIA A CONEXÃO CORRETAMENTE
$db = new Database();
$pdo = $db->connect();

$userModel = new User($pdo);

$meuId = $_SESSION['user_id'];

// Busca todos os usuários, exceto o logado
$contatos = $userModel->listarUsuarios($meuId);

echo json_encode($contatos);
