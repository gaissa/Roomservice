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

		var events = {};
		events[new Date("04/10/2013")] = new Event("Valentines Day", "pink");
		events[new Date("04/12/2013")] = new Event("Payday", "green");

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
				
				alert(date);
			}
		});
		
	});
	
	</script>
</head>
<head>
</head>
<body>
	<div id="datepicker"></div>
</body>
</html>
