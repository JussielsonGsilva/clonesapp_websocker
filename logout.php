<?php
/**
 * logout.php
 *
 * Finaliza a sessão do usuário e retorna para a tela de login.
 */

session_start();

// Remove todas as variáveis da sessão
session_unset();

// Destroi a sessão
session_destroy();

// Redireciona para a página inicial
header("Location: index.php");
exit;