<?php

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
        $sql = "SELECT id FROM chats 
                WHERE (user1_id = :u1 AND user2_id = :u2)
                   OR (user1_id = :u2 AND user2_id = :u1)
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':u1' => $user1,
            ':u2' => $user2
        ]);

        $chat = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($chat) {
            return $chat['id'];
        }

        // Se não existir, cria
        $sql = "INSERT INTO chats (user1_id, user2_id)
                VALUES (:u1, :u2)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':u1' => $user1,
            ':u2' => $user2
        ]);

        return $this->conn->lastInsertId();
    }

    // Carregar mensagens entre dois usuários
    public function carregarMensagens($userId, $contatoId)
    {
        // 1. Obter ou criar chat
        $chatId = $this->getOrCreateChat($userId, $contatoId);

        // 2. Buscar mensagens
        $sql = "SELECT sender_id, conteudo, enviado_em 
                FROM messages 
                WHERE chat_id = :chatId
                ORDER BY enviado_em ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':chatId' => $chatId]);

        $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            "chat_id" => $chatId,
            "mensagens" => $mensagens
        ];
    }

    // Isso permite que o WebSocket atualize o status no banco.
    public function atualizarStatus($messageId, $status)
    {
    $sql = "UPDATE messages SET status = :status WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
        ':status' => $status,
        ':id' => $messageId
    ]);
    }


    // Enviar mensagem
    public function enviarMensagem($chatId, $senderId, $conteudo)
    {
        $sql = "INSERT INTO messages (chat_id, sender_id, conteudo)
                VALUES (:chatId, :senderId, :conteudo)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':chatId' => $chatId,
            ':senderId' => $senderId,
            ':conteudo' => $conteudo
        ]);

        return $this->conn->lastInsertId(); // <-- ESSA LINHA É O QUE IMPORTA
    }
}
