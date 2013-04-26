<!DOCTYPE html>
<html>

	<title>Roomservice</title>
	
	<link href="css/normalize.css" rel="stylesheet" type="text/css"/>
	<link href="css/datepicker.css" rel="stylesheet" type="text/css"/>	
	
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
	
	<script type="text/javascript">
	
	$(function() {
	
		var Event = function(text, className) {
		
			this.text = text;
			this.className = className;
			
		};
		
		// A list for storing the dates of reservations.
		var list = [];
    
	   	<?php
	    	
	    	// Connect to database.
	    	$db = mysql_connect('<server>','<user>','<password>') or die('Error connecting to the server');
	      	mysql_select_db('<database>') or die('Error selecting database');

		// Select from database.
	      	$result = mysql_query('SELECT * FROM bondit') or die ('Error performing query');
	      	
	      	// Start fetching data from the database.
	      	while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {	      			
	    ?>
	    
	    list[list.length] = <?php echo '"'.$row['date'].'"'?>;	    	
	    
	    <?php
	    
	    	// End database fetching while loop here
	      	}	      		
	    ?>
    	
    	// DEBUG
    	alert(list.length);
    	
    	// A list for storing the events.
    	var events = [];
    	
    	for(var i = 0; i < list.length; i++) {
    	
    		// DEBUG
        	alert(list[i]);        	
        	
        	events[new Date(list[i])] = new Event("Valentines Day", "pink");        	
    	}

		// REPLACE THESE!
		events[new Date("04/10/2013")] = new Event("Valentines Day", "pink");
		events[new Date("04/12/2013")] = new Event("Payday", "green");

		// DEBUG
		console.dir(events);

		$("#datepicker").datepicker({
		
			beforeShowDay: function(date) {
			
				var event = events[date];
				
				if (event) {
				
					return [true, event.className, event.text];						
				}
				
				else {	
				
					return [true, '', ''];
				}
			},
			
			onSelect: function(date) {
				
				// REPLACE THIS!
				alert(date);
			}
		});
		
	});
    
    </script>	

<body>
	<div id="datepicker"></div>
</body>

</html>
