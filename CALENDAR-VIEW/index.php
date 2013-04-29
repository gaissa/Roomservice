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

                $(function() {

                    $( "#dialog-confirm" ).dialog({

                        resizable: false,
                        height: 240,
                        modal: true,

                        buttons: {
                            "Delete reservation": function() {
                                delDate(date);
                                $( this ).dialog( "close" );
                            },
                            Cancel: function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    });
                });
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
            //alert("RESERVATION: " + data.restext + "\nDATE: " + date + "\nIS RESERVED: " + data.isreserved);

            $('#dialog-confirm').text(data.restext);
            $('#dialog-confirm').parent().find("span.ui-dialog-title").html(date);
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
            //alert("RESERVATION: " + data.restext + "\nDATE: " + date + "\nIS RESERVED: " + data.isreserved);
            clear();
        },

        "json");
    }

    // for refreshing the page + updating the database.
    function clear() {

        alert("ALL CLEAR!");        
        location.reload();
    }

    </script>

    <div id="dialog-confirm"></div>
    <div id="datepicker"></div>

</body>

</html>
