<?php
session_start();
#database config
require_once('../conf/config.php');

#database connection
try {
	$db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF-8", "root", "$db_pass", array (PDO::ATTR_EMULATE_PREPARES => false,
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	
	createXML($db);
	
} catch (PDOException $e) {
	echo $e->getMessage();
}

#create xml from reservations
function createXML($db) {
	
	$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>";
	$dtd = "<!DOCTYPE reservations [

		<!ELEMENT rooms (room*)>
		<!ELEMENT room (ID,room_ID,user_ID)>
		<!ELEMENT ID (#PCDATA)>
		<!ELEMENT room_ID (#PCDATA)>
		<!ELEMENT user_ID (#PCDATA)>

	]>";
	$xml .= $dtd;
	$xml .= "<rooms>";	
	$result = $db->query("SELECT * FROM room;");

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		#main tag
		$xml .= "<room>";

		$xml .= "<ID>";
		$xml .= $row['ID'];
		$xml .= "</ID>";
		$xml .= "<room_ID>";
		$xml .= $row['room_ID'];
		$xml .= "</room_ID>";
		$xml .= "<user_ID>";
		$xml .= $row['user_ID'];
		$xml .= "</user_ID>";
		$xml .= "</room>";
	}
		
	$xml .= "</rooms>";
	//header ("Content-Type:text/xml");
	saveXML($xml);
}

function saveXML($xmlString) {
	$dom = new DOMDocument;
	$dom->preserveWhiteSpace = FALSE;
	$dom->loadXML($xmlString);
	$dom->formatOutput = TRUE;
	$dom->save("rooms.xml");
}
?>