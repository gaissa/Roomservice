<?php
session_start();

if(!isset($_SESSION["logged_in"])) {
	header("location:mainpage.html");
} 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Varausjärjestelmä</title>
	<meta http-equiv="Content-Type" content="xhtml+xml; charset=utf-8" />
		<link href="style1.css" rel="stylesheet" />
</head>
<body>

<div id="wrap">
<a href="logout.php">Kirjaudu ulos</a>
<a href="varaukset.php">Varaukset</a>
<br><br>
ETUSIVU PLAAPLAAPLAA

</div>
</body>
</html>