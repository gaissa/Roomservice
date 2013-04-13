<?php
require_once('config.php');
header("Content-Type: application/json", true);

$json = $_POST['dataArray'];
$array = json_decode($json, true);

# Variables
$username = $array['username'];
$password = $array['password'];
$email = $array['email'];
$infoArray;
	
	# Database connection
	$db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF-8", "root", "$db_pass", array
	(PDO::ATTR_EMULATE_PREPARES => false,
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	
	$infoArray[0] = checkUsername($db, $username);
	$infoArray[1] = checkEmail($db, $email);
	
	echo json_encode(array("username" => $infoArray[0], "email" => $infoArray[1]));	
	
	//echo json_encode(array("email" => $password));	
	
	if ($infoArray[0] === 0 && $infoArray[1] === 0) {
		insertData($db, $username, $password, $email);	
	}
	


# Checks if username exists
function checkUsername($db, $username) {
	
	$sql = "SELECT * FROM users WHERE username = '$username'";
	
	$res = $db->query($sql);
	$count = $res->rowCount();
	
	if ($count >= 1) {
		$infoArray['user'] = 1;
	} else {
		$infoArray['user'] = 0;
	}
	
	return $infoArray['user'];
}

# Checks if email exists
function checkEmail($db, $email) {
	
	$sql = "SELECT * FROM users WHERE email = '$email'";
	
	$res = $db->query($sql);
	$count = $res->rowCount();
	//echo 'emailcount' . $count;
	if ($count >= 1) {
		$infoArray['email'] = 1;
	} else {
		$infoArray['email'] = 0;
	}
	
	return $infoArray['email'];
}

# Sends user information to the database
function insertData($db, $username, $password, $email) {

	$sql = "INSERT INTO users (username, password, email, userlevel)
			VALUES (:username, :password, :email, 1)";

	$res = $db->prepare($sql);

	$tobeinserted = array(
			':username' => $username,
			':password' => $password,
			':email' => $email
	);

	 if ($res->execute($tobeinserted)) {
	 	
	 }	else {
	 }
}



?>