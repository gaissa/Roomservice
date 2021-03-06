var rArray = [];
var redArray = [];
var events = [];
var index;
var isReserved;

    $(document).ready(function() {
    var rooms = getUserRooms();

    // ID of currently selected room
    var currentRoom = rooms[0];

    createTabs();
    updateTabs();
    datePicker();
	
	$('#rooms').click(function() {

        $.ajax( {
            type: 'POST',
            url: '../xml/rooms_to_xml.php',
            async: false,
            success: function(result){
                    showDialogRSS();
            }
        });

    });
	
	$('#users').click(function() {

        $.ajax( {
            type: 'POST',
            url: '../xml/users_to_xml.php',
            async: false,
            success: function(result){
                    showDialogRSS();
            }
        });

    });
	
    $('#rss').click(function() {

        $.ajax( {
            type: 'POST',
            url: 'xml/reservations_to_rss.php',
            async: false,
            success: function(result){
                    showDialogRSS();
            }
        });

    });

    function datePicker() {

        // Get all reservations to this array
        getAllReservations(userID, currentRoom);
        getEveryReservation(currentRoom);

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

            }
        });
    }

    function showDialog() {

        $(function() {

            $( "#dialog-confirm" ).dialog({

                resizable: false,
                height: 440,
                width: 440,
                modal: true,

                buttons: {

                    // Must have at least one button for some reason?!
                    "Sulje": function() {
                        $(this).dialog().find('.ui-dialog-buttonpane button:last');
                        $( this ).dialog( "close" );
                    }
                }
            });
        });
    }

    function showDialogRSS() {
    
        $(function() {
			
			var locationURL = window.location.href;
			var rssURL = locationURL.substring(0, locationURL.search("/app/"));
            $('#dialog-rss').text(rssURL + "/app/rss/reservations.xml");            

            $( "#dialog-rss" ).dialog({

                resizable: false,
                height: 440,
                width: 440,
                modal: true,

                buttons: {

                    // Must have at least one button for some reason?!
                    "Sulje": function() {

                        $(this).dialog().find('.ui-dialog-buttonpane button:last');
                        $(this).dialog( "close" );
                    }
                }
            });
        });
    }

    // Creates events from reservations
    function createEvents(rArray) {

        var eventss = [];

        var Event = function(text, className) {

            this.text = text;
            this.className = className;

        };

        for(var j = 0; j < redArray.length; j++) {
            var m = redArray[j].split(".");
            console.log("redarray" + m);
            var mewdate = m[2] + "/" + m[1] + "/" + m[0];
            console.log(mewdate);
            eventss[new Date(mewdate)] = new Event("asd", "eventcolor2");
        }

       for(var i = 0; i < rArray.length; i++) {
            var n = rArray[i].split(".");

            var newdate = n[2] + "/" + n[1] + "/" + n[0];
            console.log(newdate);
            eventss[new Date(newdate)] = new Event("Varattu", "eventcolor1");

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
                    getEveryReservation(currentRoom);
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
                    getEveryReservation(currentRoom);
                    events = createEvents(rArray);
                    $("#datepicker").datepicker("refresh");
                    console.log("Clicked tab " + index + ", room_ID = " + rooms[index]);
    }

    function getUserRooms() {

        var roomArray;

        $.ajax({
            type: 'POST',
            url: 'php/getuserrooms.php',
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
                rArray = result;
                console.log("Success: " + rArray);
            }
        });

    }

    // Function for getting all room reservations
    function getEveryReservation(roomID) {

        $.ajax({
            type: 'POST',
            url: 'php/geteveryreservation.php',
            dataType: 'json',
            data: { currentroom: roomID},
            async: false,
            success: function(result){
                redArray = result;
                console.log("Success: " + redArray);
            }
        });

    }

    // Function for getting specific reservation when clicking calendar cell
    function getDate(date, roomid) {

        // Array containing date + roomid + userid
        var dataArray = { date: date, roomid: roomid, userid: userID };

        // Post request to getreservation.php
        $.post("php/getreservation.php", {
               "dataArray": JSON.stringify(dataArray)
        },

        function(data) {

            // If date has reservations, show DELETE button and DIALOG
            if(data.isreserved === true && data.resuserid == userID) {

                showDialog();
                $('#dialog-confirm').text(data.restext);
                $('#dialog-confirm').parent().find("span.ui-dialog-title").html(date);

                var buttonSet = $('#dialog-confirm').parent().find('.ui-dialog-buttonset');

                var newButton = $('<button>Poista varaus</button>');

                newButton.button().click(function () {
                    delDate(date);
                    getAllReservations(userID, currentRoom);
                    getEveryReservation(currentRoom);

                    // Get all events to this array for calendar display
                    events = createEvents(rArray);
                    $("#datepicker").datepicker("refresh");
                    $('#dialog-confirm').dialog( "close" );
                });

                buttonSet.append(newButton);

            // If someone else has reservations to that room, show ALERT
            } else if(data.isreserved === true && data.resuserid != userID) {

                alert("PVM ON VARATTU!");

            // If date has no reservations, show ADD button and DIALOG
            } else {

                showDialog();
                $('#dialog-confirm').text('');
                $('#dialog-confirm').parent().find("span.ui-dialog-title").html(date);
                $('#dialog-confirm').append('<textarea id="restextarea" rows="13" cols="5"></textarea>');

                var buttonSet = $('#dialog-confirm').parent().find('.ui-dialog-buttonset');

                var newButton = $('<button>Lisää varaus</button>');

                newButton.button().click(function () {
                    var res_text = $('textarea#restextarea').val();
                    addReservation(date, res_text, userID, currentRoom);
                    getAllReservations(userID, currentRoom);
                    getEveryReservation(currentRoom);
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

        // Array containing date + room id
        var dataArray = { date: datee, reservationtext: rese_text, userid: userID, roomid: currentRoom };

        $.ajax( {
            type: 'POST',
            url: 'php/insertreservation.php',
            dataType: 'json',
            data: { 'dataArray': JSON.stringify(dataArray)},
            async: false,
            success: function(result) {
            }
        });        
    }

    // Function for deleting a specific reservation
    function delDate(date) {

        // Array containing date + room id
        var dataArray = { date: date, roomid: currentRoom };

        $.ajax( {
            type: 'POST',
            url: 'php/delres.php',
            dataType: 'json',
            data: { 'dataArray': JSON.stringify(dataArray)},
            async: false,
            success: function(result) {
            }
        });   
    }

    // for refreshing the page + updating the database.
    function clear() {

        //alert("Varaus poistettu!");
    }
});
