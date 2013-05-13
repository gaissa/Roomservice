<?php

    $con=mysqli_connect("localhost","root","","roomservice");

    // Check connection
    if (mysqli_connect_errno())
    {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $tunnus=$_POST['tunnus'];

    $check = checkData($tunnus);

    if (($check)) {

        $sql="INSERT INTO room (room_ID) VALUES ('$tunnus')";

        if (!mysqli_query($con,$sql))
        {
            header('Location: admin.php#wrap');
        }

        mysqli_close($con);

        header('Location: admin.php#wrap');
    }

    else {

        header('Location: admin.php#wrap');
    }

    function checkData($tunnus) {

        if (
           ($tunnus != '') &&
           (strlen($tunnus) < 1000) &&
           ( (is_numeric($tunnus)) && (!strstr($tunnus, '.')) )
           ) {

            $validated = true;
        }

        else {

            $validated = false;
        }

        return $validated;
    }

?>