<?php
session_start();

if(!isset($_SESSION["logged_in"])) {
	header("location:mainpage.html");
} 

//print_r($_SESSION);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Roomservice</title>

    <meta charset="utf-8">

    <link href="css/normalize.css" rel="stylesheet" type="text/css"/>
    <link href="css/datepicker.css" rel="stylesheet" type="text/css"/>

    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script type="text/javascript" src="js/jquery.ui.datepicker-fi.js"></script>

</head>

<body>

    <script type="text/javascript">
	var userID = '<?php echo $_SESSION["id"] ?>';
	//alert(userID);
	var rArray = [];
	var events = [];
	var index;
	
	$(document).ready(function() {	
	var rooms = getUserRooms(userID);
	
	// ID of currently selected room
	var currentRoom = rooms[0];
	
	createTabs();
	datePicker();
	
	function datePicker() {
        // Get all reservations to this array
        rArray = getAllReservations(userID, currentRoom);
        // Get all events to this array for calendar display
        events = createEvents(rArray);

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

                getDate(date, rooms[index]);

                $(function() {
                    
                    $( "#dialog-confirm" ).dialog({

                        resizable: false,
                        height: 440,
                        width: 440,
                        modal: true,
                        
                        buttons: {                            
                            
                            // Must have at least one button for some reason?!
                            Cancel: function() {      
                                $(this).dialog().find('.ui-dialog-buttonpane button:last'); 
                                $( this ).dialog( "close" );
                            }
                        }
                    });
                });
            }
        });
	}

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
	
	// Creates tabs for selecting a room.
	function createTabs() {
		
		console.log(rooms.length);
		
		for(var i = 0 ; i < rooms.length ; i++) {
			
			$("#tabs").find("ul").append('<li><a href="#datepicker">' + rooms[i] + '</a></li>').click(
			
				// Tabs' onClick function. Posts 
				function(){
					index = $("#tabs").tabs('option', 'active');
					currentRoom = rooms[index];
					rArray = getAllReservations(userID, currentRoom);
					createEvents(rArray);
					console.log("Clicked tab " + index + ", room_ID = " + rooms[index]);
			});
			
			console.log(rooms[i]);
		}
		
		$("#tabs").tabs();
	}
	
	function getUserRooms(userID) {
		
		var roomArray;

        $.ajax({
            type: 'POST',
            url: 'php/getuserrooms.php',
			data: {userid: userID},
            dataType: 'json',
            async: false,
            success: function(result){
					console.log(result);
					roomArray = result;
				}
        });

        return roomArray;
	}
	
    // Function for getting all room reservations
    function getAllReservations(userID, roomID) {

        var reservationArray;
		
        $.ajax({
            type: 'POST',
            url: 'php/getallreservations.php',
            dataType: 'json',
			data: { currentroom: roomID, userid: userID },
            async: false,
            success: function(result){
				reservationArray = result;
				console.log("Success: " + reservationArray);
			}
        });

        return reservationArray;
    }

    // Function for getting specific reservation
    function getDate(date, roomid) {

        // Array containing date + room id
        var dataArray = { date: date, roomid: roomid };

        // Post request to getres.php
        $.post("php/getreservation.php", {
               "dataArray": JSON.stringify(dataArray)
        },

        function(data) {
        
            //alert("RESERVATION: " + data.restext + "\nDATE: " + date + "\nIS RESERVED: " + data.isreserved);            
            
            // If date has reservations, show DELETE button.
            if(data.isreserved === true) {
				alert("RESTEXT" + data.restext);
                $('#dialog-confirm').text(data.restext);
                $('#dialog-confirm').parent().find("span.ui-dialog-title").html(date);
                
                var buttonSet = $('#dialog-confirm').parent().find('.ui-dialog-buttonset');                
                
                var newButton = $('<button>Delete reservation</button>');
                
                newButton.button().click(function () {                    
                    delDate(date);
					// Get all reservations to this array
					alert("BEFORE ARRAY" +rArray);
					getAllReservations(userID, currentRoom);
					alert("AFTER ARRAY" +rArray);
					// Get all events to this array for calendar display
					events = createEvents(rArray);
					//datePicker();
					$("#datepicker").datepicker("refresh");
                    $('#dialog-confirm').dialog( "close" );                     
                });
                
                buttonSet.append(newButton);
            }

            // If date has reservations, show ADD button.
            else {
            
                $('#dialog-confirm').text('');                
                $('#dialog-confirm').parent().find("span.ui-dialog-title").html(date);
                
                var buttonSet = $('#dialog-confirm').parent().find('.ui-dialog-buttonset');                
                
                var newButton = $('<button>Add reservation</button>');
                
                newButton.button().click(function () {
                    alert('ADD BUTTON CLICKED!');
                });
                
                buttonSet.append(newButton);                
            }
        },

        "json");
        
    }

    // Function for deleting a specific reservation
    function delDate(date) {
        
        // Array containing date + room id
        var dataArray = { date: date, roomid: currentRoom };

        // Post request to delres.php
        $.post("php/delres.php", {
               "dataArray": JSON.stringify(dataArray)
        },

        function(data) {
        
            //alert("RESERVATION: " + data.restext + "\nDATE: " + date + "\nIS RESERVED: " + data.isreserved);
            
            clear();
            
        },

        "json");
    }

    // for refreshing the page + updating the database.
    function clear() {
                              
        alert("Reservation deleted!");      
        //location.reload();
    }
});
    </script>
	<div id="tabs">
		<ul>
		</ul>
		<div id="empty"></div>
		<div id="datepicker"></div>
	</div>
    <div id="dialog-confirm"></div>
    

</body>

</html>
