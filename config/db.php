<?php

class Database
{
    private $host = "localhost";
    private $db = "clonesap";
    private $user = "root";
    private $pass = "";
    public $pdo;

    public function connect()
    {
        if ($this->pdo) {
            return $this->pdo;
        }

        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4",
                $this->user,
                $this->pass
            );

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->pdo;

        } catch (PDOException $e) {
            die("Erro ao conectar: " . $e->getMessage());
        }
    }
}