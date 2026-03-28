<?php
/**
 * index.php
 *
 * Tela inicial do projeto clonesapp_websocker.
 * Exibe o formulário de login e também o link para cadastro.
 * Este arquivo é carregado automaticamente ao acessar a raiz do projeto.
 *
 * Funções principais:
 * - Validar login do usuário
 * - Redirecionar para home.php após autenticação
 * - Exibir erros de login
 */

session_start();

// Exibe mensagem de sucesso após cadastro
$mensagem_sucesso = "";
if (isset($_SESSION['cadastro_sucesso'])) {
    $mensagem_sucesso = $_SESSION['cadastro_sucesso'];
    unset($_SESSION['cadastro_sucesso']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClonesApp - Login</title>

    <style>
        /* ============================
           ESTILO GERAL
        ============================ */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 420px;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #00000020;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        input {
            width: 94%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #0084ff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #006fd6;
        }

        .link {
            text-align: center;
            margin-top: 12px;
            font-size: 14px;
        }

        .link a {
            color: #0084ff;
            text-decoration: none;
        }

        /* ============================
           MENSAGEM DE SUCESSO
        ============================ */
        .sucesso {
            background: #4CAF50;
            color: white;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 15px;
            opacity: 1;
            transition: opacity 1s ease-out;
        }

        .fade-out {
            opacity: 0;
        }

        /* ============================
           RESPONSIVIDADE
        ============================ */
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }    

            .container {
                padding: 16px;
            }

            h2 {
                font-size: 20px;
            }

            input, button {
                font-size: 16px;
            }
        }
    </style>

</head>
<body>

<div class="container">

    <?php if ($mensagem_sucesso): ?>
        <div id="msgSucesso" class="sucesso">
            <?= $mensagem_sucesso ?>
        </div>
    <?php endif; ?>

    <h2>Entrar</h2>

    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Seu email" required>
        <input type="password" name="senha" placeholder="Sua senha" required>
        <button type="submit">Login</button>
    </form>

    <hr style="margin: 25px 0;">

    <h2>Cadastrar</h2>

    <form action="register.php" method="POST">
        <input type="text" name="nome" placeholder="Seu nome completo" required>
        <input type="email" name="email" placeholder="Seu email" required>
        <input type="password" name="senha" placeholder="Crie uma senha" required>
        <button type="submit">Cadastrar</button>
    </form>

</div>

<script>
    // Fade-out da mensagem de sucesso
    const msg = document.getElementById("msgSucesso");
    if (msg) {
        setTimeout(() => {
            msg.classList.add("fade-out");

            // Remove o elemento após a animação
            setTimeout(() => {
                msg.remove();
            }, 1000); // tempo igual ao transition do CSS
        }, 2000);
    }
</script>

</body>
</html>
