<?php

    // Database config
    require_once('../conf/config.php');

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

    $reservation = delReservation($date, $room_id, $db_host, $db_name, $db_pass);

    if($reservation['res_date'] !== null) {
        $reserved = true;
    } else {
        $reserved = false;
    }

    // Echo back the content
    echo json_encode(array("restext" => $reservation['res_text'], "isreserved" => $reserved));

    // Function for getting reservation row
    function delReservation($date, $room_id, $db_host, $db_name, $db_pass) {

        // Database connection
        $db = new PDO("mysql:host=$db_host; dbname=$db_name",
                      "root", "$db_pass", array(PDO::ATTR_EMULATE_PREPARES => false,
                      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

         // Delete data
        $sql = "DELETE FROM reservations WHERE res_date = '$date'
                                         AND room_ID = '$room_id'";

        // The query
        $result = $db->query($sql);
    }
?>