<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

require_once "../config/db.php";
require_once "../src/User.php";

$userModel = new User($pdo);

$meuId = $_SESSION['user_id'];

$contatos = $userModel->listarUsuarios($meuId);

echo json_encode($contatos);