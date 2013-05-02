CREATE TABLE users
(
    ID INT NOT NULL AUTO_INCREMENT UNIQUE PRIMARY KEY,
    username VARCHAR(20) UNIQUE,
    password VARCHAR(40),
    email VARCHAR(50),
    userlevel INT(1)
);

CREATE TABLE reservations
(
    ID INT NOT NULL AUTO_INCREMENT UNIQUE PRIMARY KEY,
    start_time DATETIME,
    duration INT(2),
    res_date VARCHAR(10),
    res_text VARCHAR(65000),
    user_ID INT,
    room_ID INT
);

CREATE TABLE room
(
    ID INT NOT NULL AUTO_INCREMENT UNIQUE PRIMARY KEY,
    user_ID INT,
    room_ID INT
);
