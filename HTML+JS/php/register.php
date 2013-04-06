<?php


$dataArray = $_POST['dataArray'];

$username = $dataArray['username'];
$password1 = $dataArray['password1'];
$password2 = $dataArray['password2'];
$email = $dataArray['email'];

echo $username . $password1 . $password2 . $email;

?>