<?php
/**
 * Tela de cadastro de novos usuários.
 * Recebe nome, email e senha e salva no banco de dados.
 *
 * Funções principais:
 * - Criar novo usuário
 * - Validar email duplicado
 * - Redirecionar para index.php após cadastro
 */

session_start();
require_once "config/db.php";

// Criar conexão usando a classe Database
$db = new Database();
$pdo = $db->connect();

// Verifica se veio via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Verifica campos vazios
    if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['cadastro_sucesso'] = "Preencha todos os campos!";
        header("Location: index.php");
        exit;
    }

    // Verifica se email já existe
    $sql = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $sql->execute([$email]);

    if ($sql->rowCount() > 0) {
        $_SESSION['cadastro_sucesso'] = "Este email já está cadastrado!";
        header("Location: index.php");
        exit;
    }

    // Criptografa a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere no banco
    $insert = $pdo->prepare("INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)");
    $insert->execute([$nome, $email, $senhaHash]);

    // Mensagem de sucesso
    $_SESSION['cadastro_sucesso'] = "Cadastro realizado com sucesso!";

    header("Location: index.php");
    exit;
}
?>
