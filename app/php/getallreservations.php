<?php

     // Database config.
    require_once('../conf/config.php');
	$currentRoom = $_POST['currentroom'];
	$userID = $_POST['userid'];

    // Connect to database.
    // Database connection
	$db = new PDO("mysql:host=$db_host; dbname=$db_name",
                "root", "$db_pass", array(PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    // Select from database.
    $sql = "SELECT * FROM reservations WHERE room_id = $currentRoom AND user_ID = $userID";

	$result = $db->query($sql);
	$reservations = array();
	
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        
		$reservations[] = $row['res_date'];      

    }
    
	echo json_encode($reservations);
?>