<?php
	createDay();
	
	// Creates an XML of current month's days starting from the last monday of previous month.
	function createMonth() {
		$startTime = strtotime("last monday of last month");
		$currentTime = strtotime("now");
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
		$xml .= "<month number=\"" . date('n', $currentTime) . "\">";
		for($w = 0; $w < 6; $w++) {
			$xml .= "<week number=\"" . date("W", $startTime + ($w + 1) * 7 * 86400) . "\">";
			for($i = 0; $i < 7; $i++) {
				$day = date("j", $startTime + ($i + ($w * 7)) * 86400);
				$weekday = date("l", $startTime + ($i + ($w * 7)) * 86400);
				if(date("Ymd", $currentTime) == date("Ymd", $startTime + ($i + ($w * 7)) * 86400)) {
					$xml .= "<day number=\"$day\" day=\"$weekday\" today=\"true\"></day>";
				} else {
					$xml .= "<day number=\"$day\" day=\"$weekday\"></day>";
				}
			}
			$xml .= "</week>";
		}
		$xml .= "</month>";
		
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		$dom->loadXML($xml);
		$dom->formatOutput = TRUE;
		$dom->save("month.xml");
		
		$xslt = new DOMDocument();
		$xslt->load("styling.xslt");
		
		$xsltProcessor = new XSLTProcessor();
		$xsltProcessor->importStyleSheet($xslt);
		
		print $xsltProcessor->transformToXml($dom);
	}
	
	function createDay() {
		$currentTime = strtotime("now");
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
		$xml .= "<day date=\"" . date('Ymd', $currentTime) . "\">";
		for($h = 0; $h < 24; $h++) {
			$xml .= "<hour number=\"$h\">";
			
			if(checkReservationStatus(1, date("Y-m-d H:i:s", mktime($h, 0, 0, date("n"), date("j"), date("Y"))))) {
				
				$xml .= "<reservation></reservation>";
				
			}
			
			$xml .= "</hour>";
		}
		$xml .= "</day>";
	
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		$dom->loadXML($xml);
		$dom->formatOutput = TRUE;
		$dom->save("day.xml");
		
		$xslt = new DOMDocument();
		$xslt->load("day.xslt");
		
		$xsltProcessor = new XSLTProcessor();
		$xsltProcessor->importStyleSheet($xslt);
		
		print $xsltProcessor->transformToXml($dom);
	}
	
	function checkReservationStatus($roomId, $startTime) {
		
		$reservationXML = new DOMDocument;
		$reservationXML->load("reservations.xml");
		
		$reservations = $reservationXML->getElementsByTagName( "reservations" );
		
		foreach( $reservations as $reservation )
		{
			$reservationTime = $reservation->getElementsByTagName( "start_time" );
			$reserved = $reservationTime->item(0)->nodeValue;
			
			if($startTime === $reserved) {
				return true;				
			} else {
				return false;
			}
		}
	}
?>