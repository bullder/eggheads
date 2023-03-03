<?php

declare(strict_types=1);

namespace Cli;

use PDO;

class Db
{
    private PDO $con;

    public function __construct()
    {
        $host = '127.0.0.1';
        $db   = 'test';
        $user = 'root';
        $pass = 'admin123';
        $port = '3308';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->con = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * @return array|P[]
     */
    public function get(): array
    {
        $stmt = $this->con->query('SELECT * FROM p');
        $result = [];
        while ($row = $stmt->fetch())
        {
            $result[] = new P($row['id'], $row['name'], $row['city'], $row['address']);
        }

        return $result;
    }
}
