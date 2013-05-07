<?php

session_start();

if(!isset($_SESSION["logged_in"])) {
	header("location:mainpage.html");
} 

?>

<!DOCTYPE html>
<html>

<head>

    <title>Roomservice</title>

    <meta charset="utf-8">

    <link href="css/normalize.css" rel="stylesheet" type="text/css"/>
    <link href="css/datepicker.css" rel="stylesheet" type="text/css"/>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="js/jquery.ui.datepicker-fi.js"></script>
	<script type="text/javascript">var userID = '<?php echo $_SESSION["id"] ?>'</script>	
	<script type="text/javascript" src="js/index.js"></script>	
	<script type="text/javascript" src="js/date.js"></script>	
    
</head>

<body>

	<div class="logout"><a href ="php/logout.php">Kirjaudu ulos</a></div>
	<div id="tabs">
		<ul>
		</ul>
		<div id="empty"></div>
		<div id="datepicker"></div>
	</div>
    <div id="dialog-confirm"></div>
    

</body>

</html>
