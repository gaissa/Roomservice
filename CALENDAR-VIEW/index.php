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

        var Event = function(text, className) {

            this.text = text;
            this.className = className;

        };

        // A list for storing the dates of reservations.
        var list = [];

        <?php

            // Database config.
            require_once('config.php');

            // Connect to database.
            $db = mysql_connect($db_host, $db_user, $db_pass) or die('Error connecting to the server');
            mysql_select_db($db_name) or die('Error selecting database');

            // Select from database.
            $result = mysql_query('SELECT * FROM reservations') or die ('Error performing query');

            // Start fetching data from the database.
            while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        ?>

        list[list.length] = <?php echo '"'.$row['res_date'].'"'?>;

        <?php

            // End database fetching  loop here.
            }

            // Close the database connection.
            mysql_close($db);
        ?>

        // DEBUG!
        //alert(list.length);

        // A list for storing the events.
        var events = [];

        for(var i = 0; i < list.length; i++) {

            events[new Date(list[i])] = new Event("Valentines Day", "pink");
        }

        // DEBUG!
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

        $.post("getres.php", {

            // NOTICE THE ROOM_ID HERE!!!!!
            date: date, roomid: "14"
        },

        function(data) {
            alert("Data Loaded: " + data + " " + date);
        });
    }

    </script>

    <div id="datepicker"></div>

</body>

</html>
