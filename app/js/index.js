var rArray = [];
	var events = [];
	var index;
	
	$(document).ready(function() {	
	var rooms = getUserRooms(userID);
	
	// ID of currently selected room
	var currentRoom = rooms[0];
	
	createTabs();
	updateTabs();
	datePicker();
	
	function datePicker() {
        // Get all reservations to this array
        getAllReservations(userID, currentRoom);
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

        var eventss = [];

        var Event = function(text, className) {

            this.text = text;
            this.className = className;

        };

       for(var i = 0; i < rArray.length; i++) {
			var n = rArray[i].split(".");
			var na = $.datepicker.formatDate('yy.mm.dd', new Date(n[2], n[1] - 1, n[0]));
            
            var newdate = n[2] + "/" + n[1] + "/" + n[0];
			console.log(newdate);
            eventss[new Date(newdate)] = new Event("Varattu", "eventcolor");
			console.log(eventss);
        }

        return eventss;
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
					getAllReservations(userID, currentRoom);
					events = createEvents(rArray);
					$("#datepicker").datepicker("refresh");
					console.log("Clicked tab " + index + ", room_ID = " + rooms[index]);
			});
			
			console.log(rooms[i]);
		}
		
		$("#tabs").tabs();
	}
	
	function updateTabs(){
					index = $("#tabs").tabs('option', 'active');
					currentRoom = rooms[index];
					getAllReservations(userID, currentRoom);
					events = createEvents(rArray);
					$("#datepicker").datepicker("refresh");
					console.log("Clicked tab " + index + ", room_ID = " + rooms[index]);
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
		
        $.ajax({
            type: 'POST',
            url: 'php/getallreservations.php',
            dataType: 'json',
			data: { currentroom: roomID, userid: userID },
            async: false,
            success: function(result){
				reservationArray = result;
				rArray = result;
				console.log("Success: " + rArray);
			}
        });

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
                    
            // If date has reservations, show DELETE button.
            if(data.isreserved === true) {
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
                $('#dialog-confirm').append('<textarea id="restextarea" rows="13" cols="5"></textarea>');
								
                var buttonSet = $('#dialog-confirm').parent().find('.ui-dialog-buttonset');                
                
                var newButton = $('<button>Add reservation</button>');
                
                newButton.button().click(function () {
					var res_text = $('textarea#restextarea').val();
					addReservation(date, res_text, userID, currentRoom);
					getAllReservations(userID, currentRoom);					
					events = createEvents(rArray);					
					$("#datepicker").datepicker("refresh");
					$('#dialog-confirm').dialog( "close" );  
                });
                
                buttonSet.append(newButton);                
            }
        },

        "json");
        
    }
	
	// Function for adding reservation
    function addReservation(datee, rese_text,userID, currentRoom) {
        alert(datee + " " + rese_text + " " + userID + " " +  currentRoom);
        // Array containing date + room id
        var dataArray = { date: datee, reservationtext: rese_text, userid: userID, roomid: currentRoom };

        // Post request to delres.php
      /*  $.post("php/insertreservation.php", {
               "dataArray": JSON.stringify(dataArray)
        },

        function(data) {
        
            alert('Varaus luotu');
                      
            
        },

        "json");*/
		
		 $.ajax({
            type: 'POST',
            url: 'php/insertreservation.php',
            dataType: 'json',
			data: { 'dataArray': JSON.stringify(dataArray)},
			async: false,
            success: function(result){
				alert("JEE");
			}
        });
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