<?php
/**
 * login.php
 *
 * Valida o login do usuário.
 * Verifica email e senha e cria a sessão.
 *
 * Funções principais:
 * - Validar credenciais
 * - Verificar senha com password_verify()
 * - Criar sessão do usuário
 * - Redirecionar para home.php
 */

session_start();
require_once "config/db.php";

// Criar conexão usando a classe
$db = new Database();
$pdo = $db->connect(); // mantém o nome $pdo para não quebrar nada

// Verifica se veio via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Verifica campos vazios
    if (empty($email) || empty($senha)) {
        $_SESSION['cadastro_sucesso'] = "Preencha todos os campos!";
        header("Location: index.php");
        exit;
    }

    // Busca usuário pelo email
    $sql = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $sql->execute([$email]);

    if ($sql->rowCount() === 0) {
        $_SESSION['cadastro_sucesso'] = "Email não encontrado!";
        header("Location: index.php");
        exit;
    }

    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    // Verifica senha
    if (!password_verify($senha, $usuario['senha'])) {
        $_SESSION['cadastro_sucesso'] = "Senha incorreta!";
        header("Location: index.php");
        exit;
    }

    // Login OK — cria sessão
    $_SESSION['user_id'] = $usuario['id'];
    $_SESSION['user_nome'] = $usuario['nome'];

    // Redireciona para home
    header("Location: home.php");
    exit;
}
?>