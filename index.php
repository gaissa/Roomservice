<!DOCTYPE html>
<html>

	<title>Roomservice</title>
	<link href="CALENDAR-VIEW/css/normalize.css" rel="stylesheet" type="text/css"/>
	<link href="CALENDAR-VIEW/css/datepicker.css" rel="stylesheet" type="text/css"/>	
	<script type="text/javascript" src="CALENDAR-VIEW/js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="CALENDAR-VIEW/js/jquery-ui-1.8.18.custom.min.js"></script>
	
	<script type="text/javascript">
		
	$(function() {
	
		var Event = function(text, className) {
			this.text = text;
			this.className = className;
		};
		
		    var date_list=[];
			var text_list=[];
    
		// For getting dates
    	<?php
      		$db=mysql_connect('localhost','root','') or die('Error connecting to the server');
      		mysql_select_db('roomservice') or die('Error selecting database');
      
      		$result=mysql_query('SELECT * FROM reservations') or die ('Error performing query');
      
      		while($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
      
    	?>
    
    	date_list[date_list.length]=<?php echo '"'.$row['res_date'].'"'?>;    	
    
    	<?php
    
      		}       
    	?>
		
		// For getting reservation text content
    	<?php
      		$db=mysql_connect('localhost','root','') or die('Error connecting to the server');
      		mysql_select_db('roomservice') or die('Error selecting database');
      
      		$result=mysql_query('SELECT * FROM reservations') or die ('Error performing query');
      
      		while($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
      
    	?>
    
    	text_list[text_list.length]=<?php echo '"'.$row['res_text'].'"'?>;
    	
    
    	<?php    
      		}       
    	?>
    	//alert(list.length);
    	
    	var events = [];
    	
    	for(var i=0; i < date_list.length; i++) {
        	//alert(list[i]);
        	events[new Date(date_list[i])] = new Event(text_list[i], "pink");        	
    	}
		
		//events[new Date("04/10/2013")] = new Event("Valentines Day", "pink");
		//events[new Date("04/12/2013")] = new Event("Payday", "green");

		console.dir(events);

		$("#datepicker").datepicker({
		
			beforeShowDay: function(date) {
				console.log("beforeShowDay");
				var event = events[date];
				
				if (event) {
					//alert(event.text);
					//e = event.text;
					return [true, event.className, event.text];
					
				}
				else {
					return [true, '', ''];
				}
			},
			
			onSelect: function(date) {
				
				/*for(var i = 0; i < date_list.length; i++) {
					if(date == date_list[i]) {
						alert(text_list[i]);
					}
				}*/
				
				getDate(date);
			}
		});
		
	});
	
	function getDate(date) {
		$.post("getres.php", { date: date, roomid: "15" }, 
			function(data) {
				alert("Data Loaded: " + data);
			});
	}
    
    </script>
	
</head>
<head>
</head>
<body>
	<div id="datepicker"></div>
</body>
</html>
