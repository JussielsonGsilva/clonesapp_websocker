<?php
/**
 * Classe responsável por operações relacionadas aos usuários.
 *
 * Funções principais:
 * - Criar novo usuário
 * - Buscar informações do usuário
 * - Listar contatos
 */

class User
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Buscar todos os usuários, exceto o usuário logado
    public function listarUsuarios($meuId)
    {
        $sql = "SELECT id, nome FROM usuarios WHERE id != :id ORDER BY nome ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $meuId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
