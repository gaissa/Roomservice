<?php
session_start();
require_once('config.php');

$usernm = $_POST['username'];
$passwd = $_POST['password'];
$sha1pass = sha1($passwd);

try {
	$db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF-8", "root", "$db_pass", array (PDO::ATTR_EMULATE_PREPARES => false,
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	
	validateData($db, $usernm, $passwd);
	
} catch (PDOException $e) {
	echo $e->getMessage();
}

# Validates user information
function validateData($db, $username, $password) {
	$res = $db->prepare("SELECT username, password FROM users
			WHERE username= :username");
	$res->bindParam(':username', $username, PDO::PARAM_STR);
	
	#execute query and check if fetched rows password matches the user input

	if($res->execute() && $row = $res->fetch()) {
		if($row['password'] === $password) {
			$_SESSION["logged_in"] = true;
			header("location: index.php");
		} else if ($row['password'] !== $password){
			echo "Login failed";
		}

	}
}