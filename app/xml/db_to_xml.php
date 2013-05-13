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

		<!ELEMENT rss (item*)>
		<!ELEMENT item (title,link,description)>
		<!ELEMENT title (#PCDATA)>
		<!ELEMENT link (#PCDATA)>
		<!ELEMENT description (#PCDATA)>

	]>";
	$xml .= $dtd;
	$xml .= "<rss>";	
	$result = $db->query("SELECT * FROM reservations INNER JOIN users ON reservations.user_ID = users.ID");

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		#main tag
		$xml .= "<item>";

		$xml .= "<title>";
		$xml .= $row['res_date'];
		$xml .= "</title>";
		$xml .= "<link>http://localhost/Roomservice/app/</link>";
		$xml .= "<description>";
		$xml .= $row['username'] . " has reserved room no. " . $row['room_ID'];
		$xml .= "</description>";
		$xml .= "</item>";
	}
		
	$xml .= "</rss>";
	//header ("Content-Type:text/xml");
	saveXML($xml);
}

function saveXML($xmlString) {
	$dom = new DOMDocument;
	$dom->preserveWhiteSpace = FALSE;
	$dom->loadXML($xmlString);
	$dom->formatOutput = TRUE;
	$dom->save("reservations.xml");
}
?>