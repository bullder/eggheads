Question 1.

```php
$mysqli = new mysqli("localhost", "my_user", "my_password", "world");
$id = $_GET['id'];
$res = $mysqli->query('SELECT * FROM users WHERE u_id='. $id);
$user = $res->fetch_assoc();
```

Не экранировое использование ввода для квери стейтмента
Классическая инъекция `1'; DROP TABLE users;--` положит таблицу

Исправленая версия 

```php
$mysqli = new mysqli("localhost", "my_user", "my_password", "world");
$id = $_GET['id'];
$stmt = $mysqli->prepare('SELECT * FROM users WHERE u_id=?');
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
```

Question 2.

Исходный код
```php
$questionsQ = $mysqli->query('SELECT * FROM questions WHERE catalog_id='. $catId);
$result = array();
while ($question = $questionsQ->fetch_assoc()) {
    $userQ = $mysqli->query('SELECT name, gender FROM users WHERE id='. $question['user_id']);
    $user = $userQ->fetch_assoc();
    $result[] = array('question'=>$question, 'user'=>$user);
    $userQ->free();
}

$questionsQ->free();
```

Рефакторинг [полный код]() [покрытие тестами]() 
```php
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
```php

