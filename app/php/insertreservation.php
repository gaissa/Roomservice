<?php

     // Database config.
    require_once('../conf/config.php');

     // Json header
    header("Content-Type: application/json", true);
    // Json array from JS
    $json = $_POST['dataArray'];
    // Decode json
    $array = json_decode($json, true);

    $userid = $array['userid'];
    $roomid = $array['roomid'];
    $res_text = $array['reservationtext'];
    $res_date = $array['date'];

    /*$userid = '';
    $roomid = '';
    $res_text = '';
    $res_date = '';*/

    // Connect to database.
    // Database connection
    $db = new PDO("mysql:host=$db_host; dbname=$db_name",
                "$db_user", "$db_pass", array(PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));


   insertReservation($db, $res_date, $res_text, $userid, $roomid);

   # Sends user information to the database
    function insertReservation($db, $res_date, $res_text, $userid, $roomid) {

        $sql = "INSERT INTO reservations (res_date, res_text, user_ID, room_ID)
                VALUES ('$res_date', '$res_text', '$userid', '$roomid');";

        $db->query($sql);
    }
?>