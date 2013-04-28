<?php

    // Database config.
    require_once('config.php');
	// Json header
	header("Content-Type: application/json", true);
	// Json array from JS
	$json = $_POST['dataArray'];
	// Decode json
	$array = json_decode($json, true);
	// Get parameters from json
    $date = $array['date'];
    $room_id = $array['roomid'];
	$reserved = null;
	
    $reservation = getReservation($date, $room_id, $db_host, $db_name, $db_pass);
	
	if($reservation['res_date'] !== null) {
		$reserved = true;
	} else {
		$reserved = false;
	}
	
	//echo json_encode(array("restext" => $reservation, "isreserved" => $room_id));
	echo json_encode(array("restext" => $reservation['res_text'], "isreserved" => $reserved));
	
	// Function for getting reservation row
    function getReservation($date, $room_id, $db_host, $db_name, $db_pass) {

        // Database connection
        $db = new PDO("mysql:host=$db_host; dbname=$db_name; charset=UTF-8",
                      "root", "$db_pass", array(PDO::ATTR_EMULATE_PREPARES => false,
                      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        $sql = "SELECT * FROM reservations WHERE res_date = '$date'
                         AND room_ID = '$room_id'";

        $result = $db->query($sql);

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {

            return $row;

        }

    }

?>