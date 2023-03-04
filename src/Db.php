<?php

declare(strict_types=1);

namespace Cli;

use Cli\Models\Question;
use Cli\Models\User;
use Exception;
use PDO;
use PDOException;
use Throwable;

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
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * @return array|Question[]
     */
    public final function get(int $categoryId): array
    {
        // Refactor:
        //        $questionsQ = $mysqli->query('SELECT * FROM questions WHERE catalog_id='. $catId);
        //        $result = array();
        //        while ($question = $questionsQ->fetch_assoc()) {
        //            $userQ = $mysqli->query('SELECT name, gender FROM users WHERE id='. $question['user_id']);
        //            $user = $userQ->fetch_assoc();
        //            $result[] = array('question'=>$question, 'user'=>$user);
        //            $userQ->free();
        //        }
        //        $questionsQ->free();

        $stmt = $this->con->prepare('
            select q.*, u.name, u.gender from 
            questions q
            join users u on q.user_id = u.id
            where q.category_id = ?');
        $stmt->execute([$categoryId]);

        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = new Question(
                $row['id'],
                new User($row['user_id'], $row['name'], $row['gender'])
            );
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    public final function seedDb(): string
    {
        $this->seedDbUsers();
        $this->seedDbQuestions();

        return 'Db have been seed';
    }

    /**
     * @throws Exception
     */
    public final function seedDbQuestions(): void
    {
        $drop = $this->con->query('DROP TABLE IF EXISTS questions');
        if (!$drop->execute()) {
            throw new Exception('can not drop table');
        }

        $create = $this->con->query('
            CREATE TABLE IF NOT EXISTS questions (
            `id` int DEFAULT NULL,
            `category_id` int DEFAULT NULL,
            `user_id` int DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
        ');
        if (!$create->execute()) {
            throw new Exception('can not create table');
        }

        $data = [
            [100, 1, 1],
            [101, 1, 2],
            [103, 2, 1],
        ];
        $stmt = $this->con->prepare('INSERT INTO questions (id, category_id, user_id) VALUES (?,?,?)');
        try {
            $this->con->beginTransaction();
            foreach ($data as $row)
            {
                $stmt->execute($row);
            }
            $this->con->commit();
        } catch (Throwable){
            $this->con->rollback();
            throw new Exception('can not insert into questions');
        }
    }
    /**
     * @throws Exception
     */
    public final function seedDbUsers(): void
    {
        $drop = $this->con->query('DROP TABLE IF EXISTS users');
        if (!$drop->execute()) {
            throw new Exception('can not drop table users');
        }

        $create = $this->con->query('
            CREATE TABLE IF NOT EXISTS users (
            `id` int DEFAULT NULL,
            `name` varchar(255) DEFAULT NULL,
            `gender` varchar(255) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
        ');
        if (!$create->execute()) {
            throw new Exception('can not create table users');
        }

        $data = [
            [1,'Mike','Male'],
            [2,'Vik','Female'],
        ];
        $stmt = $this->con->prepare('INSERT INTO users (id, name, gender) VALUES (?,?,?)');
        try {
            $this->con->beginTransaction();
            foreach ($data as $row)
            {
                $stmt->execute($row);
            }
            $this->con->commit();
        } catch (Throwable){
            $this->con->rollback();
            throw new Exception('can not insert into users');
        }
    }
}
