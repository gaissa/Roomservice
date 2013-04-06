<?php	
	createMonth();
	
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
?>