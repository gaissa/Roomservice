<?php

     // Database config.
    require_once('../conf/config.php');

    // Connect to database.
    // Database connection
    $db = new PDO("mysql:host=$db_host; dbname=$db_name",
                "root", "$db_pass", array(PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    // Select from database.
    $sql = "SELECT room_ID FROM room";

    $result = $db->query($sql);
    $rooms = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {

        $rooms[] = $row['room_ID'];
    }

    echo json_encode($rooms);
?>