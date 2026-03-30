<?php

namespace Src;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class WebSocketHandler implements MessageComponentInterface
{
    protected $clients;
    protected $usuarios = []; // user_id -> connection

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        echo "WebSocketHandler inicializado.\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "Nova conexão: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "Mensagem recebida da conexão {$from->resourceId}: $msg\n";

        $data = json_decode($msg, true);

        if (!$data) {
            echo "JSON inválido recebido.\n";
            return;
        }

        // Registrar usuário
        if ($data["acao"] === "registrar") {

            $userId = $data["user_id"];
            $this->usuarios[$userId] = $from;

            echo "Usuário $userId registrado na conexão {$from->resourceId}\n";
            return;
        }

        // Enviar mensagem
        if ($data["acao"] === "enviar_mensagem") {

            $sender = $data["sender_id"];
            $receiver = $data["receiver_id"];
            $conteudo = $data["conteudo"];

            echo "Mensagem de $sender para $receiver: $conteudo\n";

            // Se o destinatário está conectado
            if (isset($this->usuarios[$receiver])) {

                $destConn = $this->usuarios[$receiver];

                $destConn->send(json_encode([
                    "acao" => "nova_mensagem",
                    "sender_id" => $sender,
                    "receiver_id" => $receiver,
                    "conteudo" => $conteudo
                ]));

                echo "Mensagem enviada ao usuário $receiver\n";
            } else {
                echo "Usuário $receiver não está conectado.\n";
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);

        foreach ($this->usuarios as $id => $conexao) {
            if ($conexao === $conn) {
                unset($this->usuarios[$id]);
                echo "Usuário $id desconectado.\n";
                break;
            }
        }

        echo "Conexão encerrada: {$conn->resourceId}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Erro: {$e->getMessage()}\n";
        $conn->close();
    }
}
