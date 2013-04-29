<!DOCTYPE html>
<html>

<head>

    <title>Roomservice</title>

    <meta charset="utf-8">

    <link href="css/normalize.css" rel="stylesheet" type="text/css"/>
    <link href="css/datepicker.css" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery.ui.datepicker-fi.js"></script>

</head>

<body>

    <script type="text/javascript">
				
    $(function() {
		// Get all reservations to this array
		var rArray = getAllReservations();	
		// Get all events to this array for calendar display
        var events = createEvents(rArray);
	
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

                getDate(date);

                // FOR TESTING!
                //delDate(date);
            }
        });

    });
	
	// Creates events from reservations
	function createEvents(rArray) {
		
		var events = [];	
		
		var Event = function(text, className) {

            this.text = text;
            this.className = className;

        };        
		
       for(var i = 0; i < rArray.length; i++) {

            var n = rArray[i].split(".");
            var newdate = n[1] + "." + n[0] + "." + n[2];
            
            events[new Date(newdate)] = new Event("Valentines Day", "pink");
        }
		
		return events;
	}
		
	// Function for getting all room reservations
    function getAllReservations() {
		
		var reservationArray;   
       
		$.ajax({
			type: 'POST',
			url: 'php/getallreservations.php',
			dataType: 'text',
			async: false,
			success: function(result){ reservationArray = result.split(" "); }
		});
		
		return reservationArray;
    }
	
    // Function for getting specific reservation
    function getDate(date) {

        // Array containing date + room id
        var dataArray = { date: date, roomid: "14" };

        // Post request to getres.php
        $.post("php/getreservation.php", {
            "dataArray": JSON.stringify(dataArray)
        },

        function(data) {
            alert("RESERVATION: " + data.restext + "\nDATE: " + date + "\nIS RESERVED: " + data.isreserved);
        },

        "json");
    }

    // Function for deleting a specific reservation
    function delDate(date) {

        // Array containing date + room id
        var dataArray = { date: date, roomid: "14" };

        // Post request to delres.php
        $.post("php/delres.php", {
            "dataArray": JSON.stringify(dataArray)
        },

        function(data) {
            alert("RESERVATION: " + data.restext + "\nDATE: " + date + "\nIS RESERVED: " + data.isreserved);
            clear();
        },

        "json");
    }

    // for refreshing the page + updating the database.
    function clear() {

        alert("ALL CLEAR!");

    }

    </script>

    <div id="datepicker"></div>

</body>

</html>
