### Question 1.

Задание
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

### Question 2.

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

Рефакторинг [полный код](https://github.com/bullder/eggheads/blob/master/src/Db.php#L48) [покрытие тестами](https://github.com/bullder/eggheads/blob/master/test/DbTest.php) 
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
```

### Question 3.

> Имеем следующие таблицы:
> 1. users — контрагенты
> 2. orders — заказы

> Необходимо выбрать одним запросом следующее (следует учесть, что будет включена опция only_full_group_by в MySql):
> 1. Имя контрагента
> 2. Его телефон
> 3. Сумма всех его заказов
> 4. Его средний чек
> 5. Дата последнего заказа

Не очень понятно к чему тут отсыл only_full_group_by когда для такого запроса не используется having или order по не агрегированным полям, операции которые отклоняются only_full_group_by 

[Фиддл базы и запроса](http://sqlfiddle.com/#!9/195bdb/1)
[Черновик запросов](https://github.com/bullder/eggheads/blob/master/thirdQuestion.sql#L54)

```sql
SELECT
   u.name AS user_name,
   u.phone AS user_phone,
   SUM(o.subtotal) AS total_spent,
   AVG(o.subtotal) AS average_order_value,
   MAX(o.created) AS last_order_date
FROM users u
LEFT JOIN orders o ON u.id = o.user_id
GROUP BY u.id, u.name, u.phone 
```


### Question 4.

Отрефакторить
```js
function printOrderTotal(responseString) {
    var responseJSON = JSON.parse(responseString);
    responseJSON.forEach(function(item, index){
        if (item.price = undefined) {
            item.price = 0;
        }
        orderSubtotal += item.price;
    });
    console.log( 'Стоимость заказа: ' + total > 0? 'Бесплатно': total + ' руб.');
}
```

Исправленая версия [js-фидл с тестами](https://jsfiddle.net/bullder/s3gbfdLq/3/)
```js
function printOrderTotal(responseString) {
    const responseJSON = JSON.parse(responseString);
    const prices = responseJSON.map(item => item.price || 0);
    const orderSubtotal = prices.reduce((sum, price) => sum + price, 0);
    console.log(`Стоимость заказа: ${orderSubtotal > 0 ? orderSubtotal + ' руб.' : 'Бесплатно'}`);
}
```
