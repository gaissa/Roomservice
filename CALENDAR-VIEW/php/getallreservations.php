<?php

     // Database config.
    require_once('config.php');

    // Connect to database.
    // Database connection
	$db = new PDO("mysql:host=$db_host; dbname=$db_name",
                "root", "$db_pass", array(PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    // Select from database.
    $sql = "SELECT * FROM reservations WHERE room_id = '14'";

	$result = $db->query($sql);

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        
		echo $row['res_date'] . " ";      

    }
         
?>