<?php
/**
 * Classe responsável por gerenciar conversas e mensagens.
 *
 * Funções principais:
 * - Criar ou localizar chat entre dois usuários
 * - Salvar mensagens no banco
 * - Buscar histórico de mensagens
 */
class Chat
{
    private $conn;

    public function __construct($pdo)
    {
        $this->conn = $pdo;
    }

    // Retorna o chat_id entre dois usuários (cria se não existir)
    public function getOrCreateChat($user1, $user2)
    {
        // Verifica se já existe
        $sql = "SELECT id FROM chats 
                WHERE (user1_id = :u1 AND user2_id = :u2)
                   OR (user1_id = :u2 AND user2_id = :u1)
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':u1', $user1);
        $stmt->bindParam(':u2', $user2);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
        }

        // Se não existir, cria
        $sql = "INSERT INTO chats (user1_id, user2_id)
                VALUES (:u1, :u2)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':u1', $user1);
        $stmt->bindParam(':u2', $user2);
        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    // Carregar mensagens de um chat
    public function carregarMensagens($chatId)
    {
        $sql = "SELECT * FROM messages 
                WHERE chat_id = :chatId
                ORDER BY enviado_em ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':chatId', $chatId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Enviar mensagem
    public function enviarMensagem($chatId, $senderId, $conteudo)
    {
        $sql = "INSERT INTO messages (chat_id, sender_id, conteudo)
                VALUES (:chatId, :senderId, :conteudo)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':chatId', $chatId);
        $stmt->bindParam(':senderId', $senderId);
        $stmt->bindParam(':conteudo', $conteudo);

        return $stmt->execute();
    }
}
