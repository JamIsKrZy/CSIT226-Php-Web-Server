<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private $host;
    private $db_name;
    private $user;
    private $password;
    private $pdo;

    public function __construct() {
        // Get credentials from environment or use defaults
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->db_name = getenv('DB_NAME') ?: 'mydb';
        $this->user = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASSWORD') ?: '';

        $this->connect();
    }

    /**
     * Connect to database
     */
    private function connect() {
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4';
            
            $this->pdo = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die('Database Connection Error: ' . $e->getMessage());
        }
    }

    /**
     * Get PDO instance
     */
    public function getConnection() {
        return $this->pdo;
    }

    /**
     * Execute SELECT query
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die('Query Error: ' . $e->getMessage());
        }
    }

    /**
     * Execute single row SELECT query
     */
    public function queryOne($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            die('Query Error: ' . $e->getMessage());
        }
    }

    /**
     * Execute INSERT, UPDATE, DELETE query
     */
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die('Execute Error: ' . $e->getMessage());
        }
    }

    /**
     * Get last inserted ID
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    /**
     * Get row count from last query
     */
    public function rowCount($stmt) {
        return $stmt->rowCount();
    }
}
