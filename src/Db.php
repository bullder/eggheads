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
        try {
            $this->con = new PDO($dsn, $user, $pass);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * @return array|P[]
     */
    public final function get(): array
    {
        $stmt = $this->con->query('SELECT * FROM person');
        $result = [];
        while ($row = $stmt->fetch())
        {
            $result[] = new P($row['id'], $row['name'], $row['city'], $row['address']);
        }

        return $result;
    }

    /**
     * @throws \Exception
     */
    public final function seedDb(): string
    {
        $drop = $this->con->query('DROP TABLE IF EXISTS person');
        if (!$drop->execute()) {
            throw new \Exception('can not drop table');
        }

        $create = $this->con->query("
            CREATE TABLE IF NOT EXISTS person (
            `id` int DEFAULT NULL,
            `name` varchar(255) DEFAULT NULL,
            `address` varchar(255) DEFAULT NULL,
            `city` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
        "
        );
        if (!$create->execute()) {
            throw new \Exception('can not create table');
        }

        $data = [
            [1,'Mike','Pushkina 3','Saratov'],
            [2,'Vik','Pomortseva 12','Moscow'],
        ];
        $stmt = $this->con->prepare("INSERT INTO person (id, name, address, city) VALUES (?,?,?,?)");
        try {
            $this->con->beginTransaction();
            foreach ($data as $row)
            {
                $stmt->execute($row);
            }
            $this->con->commit();
        }catch (\Throwable $e){
            $pdo->rollback();
            throw new \Exception('can  not insert');
        }


        return 'Db have been seed';
    }
}
