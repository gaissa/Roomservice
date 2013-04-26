<?php

$db_host = 'localhost';
$db_name = 'roomservice';
$db_pass = '';

$date = $_POST['date'];
$room_id = $_POST['roomid'];

echo getReservation($date, $room_id, $db_host, $db_name, $db_pass);

function getReservation($date, $room_id, $db_host, $db_name, $db_pass) {

	# Database connection
	$db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF-8", "root", "$db_pass", array
	(PDO::ATTR_EMULATE_PREPARES => false,
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	
	$sql = "SELECT * FROM reservations WHERE res_date = '$date' AND room_id = '$room_id'";
	
	$result = $db->query($sql);
	
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		return $row['res_text'];
	}
  }
?>