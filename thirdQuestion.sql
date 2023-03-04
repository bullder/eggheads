CREATE TABLE users (
id INT(11) NOT NULL AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
phone VARCHAR(20) NOT NULL,
email VARCHAR(255) NOT NULL,
created DATETIME NOT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (name, phone, email, created) VALUES
('John Smith', '555-1234', 'john.smith@example.com', '2022-02-01 10:30:00'),
('Jane Doe', '555-5678', 'jane.doe@example.com', '2022-02-02 11:45:00'),
('Bob Johnson', '555-9876', 'bob.johnson@example.com', '2022-02-03 14:20:00'),
('Alice Williams', '555-4321', 'alice.williams@example.com', '2022-02-04 09:15:00'),
('Tom Davis', '555-8765', 'tom.davis@example.com', '2022-02-05 16:00:00'),
('Sara Brown', '555-2345', 'sara.brown@example.com', '2022-02-06 13:30:00'),
('Mark Wilson', '555-7654', 'mark.wilson@example.com', '2022-02-07 11:00:00'),
('Julia Lee', '555-3210', 'julia.lee@example.com', '2022-02-08 12:45:00'),
('Mike Jackson', '555-6543', 'mike.jackson@example.com', '2022-02-09 15:15:00'),
('Karen Davis', '555-0987', 'karen.davis@example.com', '2022-02-10 10:00:00');

CREATE TABLE orders (
id INT(11) NOT NULL AUTO_INCREMENT,
subtotal DECIMAL(10,2) NOT NULL,
created DATETIME NOT NULL,
city_id INT(11) NOT NULL,
user_id INT(11) NOT NULL,
PRIMARY KEY (id),
FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO orders (subtotal, created, city_id, user_id) VALUES
(25.00, '2022-02-01 10:30:00', 1, 1),
(50.00, '2022-02-02 11:45:00', 2, 2),
(75.00, '2022-02-03 14:20:00', 3, 3),
(100.00, '2022-02-04 09:15:00', 4, 4),
(125.00, '2022-02-05 16:00:00', 5, 5),
(150.00, '2022-02-06 13:30:00', 6, 6),
(175.00, '2022-02-07 11:00:00', 7, 7),
(200.00, '2022-02-08 12:45:00', 8, 8),
(225.00, '2022-02-09 15:15:00', 9, 9),
(250.00, '2022-02-10 10:00:00', 10, 10),
(275.00, '2022-02-11 14:30:00', 1, 1),
(300.00, '2022-02-12 13:00:00', 2, 2),
(325.00, '2022-02-13 11:15:00', 3, 3),
(350.00, '2022-02-14 15:45:00', 4, 4),
(375.00, '2022-02-15 12:30:00', 5, 5),
(400.00, '2022-02-16 16:20:00', 6, 6),
(425.00, '2022-02-17 09:45:00', 7, 7),
(450.00, '2022-02-18 13:45:00', 8, 8),
(475.00, '2022-02-19 10:10:00', 9, 9),
(500.00, '2022-02-20 14:00:00', 10, 10);

SELECT
    u.name AS user_name,
    u.phone AS user_phone,
    SUM(o.subtotal) AS total_spent,
    AVG(o.subtotal) AS average_order_value,
    MAX(o.created) AS last_order_date
FROM users u
         LEFT JOIN orders o ON u.id = o.user_id
GROUP BY u.id, u.name, u.phone
