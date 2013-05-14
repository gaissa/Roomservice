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

		<!ELEMENT users (user*)>
		<!ELEMENT user (ID,username,email,userlevel)>
		<!ELEMENT ID (#PCDATA)>
		<!ELEMENT username (#PCDATA)>
		<!ELEMENT email (#PCDATA)>
		<!ELEMENT userlevel (#PCDATA)>

	]>";
	$xml .= $dtd;
	$xml .= "<users>";	
	$result = $db->query("SELECT * FROM users;");

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		#main tag
		$xml .= "<user>";

		$xml .= "<ID>";
		$xml .= $row['ID'];
		$xml .= "</ID>";
		$xml .= "<username>";
		$xml .= $row['username'];
		$xml .= "</username>";
		$xml .= "<email>";
		$xml .= $row['email'];
		$xml .= "</email>";
		$xml .= "<userlevel>";
		$xml .= $row['userlevel'];
		$xml .= "</userlevel>";
		$xml .= "</user>";
	}
		
	$xml .= "</users>";
	//header ("Content-Type:text/xml");
	saveXML($xml);
}

function saveXML($xmlString) {
	$dom = new DOMDocument;
	$dom->preserveWhiteSpace = FALSE;
	$dom->loadXML($xmlString);
	$dom->formatOutput = TRUE;
	$dom->save("users.xml");
}
?>