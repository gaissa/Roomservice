<?php

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
	$xml .= "<!DOCTYPE reservations [

		<!ELEMENT reservations (reservation*)>
		<!ELEMENT reservation (ID,start_time,duration,res_date,res_text,user_ID,room_ID)>
		<!ELEMENT ID (#PCDATA)>
		<!ELEMENT start_time (#PCDATA)>
		<!ELEMENT duration (#PCDATA)>
		<!ELEMENT res_date (#PCDATA)>
		<!ELEMENT res_text (#PCDATA)>
		<!ELEMENT user_ID (#PCDATA)>
		<!ELEMENT room_ID (#PCDATA)>

	]>";
	
	$xml .= "<reservations>";	
	$result = $db->query("SELECT * FROM reservations");

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		#main tag
		$xml .= "<reservation>";
		
		foreach($row as $key => $value) {
			#first tag
			$xml .= "<$key>";
			#sql data
			$xml .= $value;
			#end tag
			$xml .= "</$key>";
		}
		#end tag
		$xml .= "</reservation>";
	}
		
	$xml .= "</reservations>";
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