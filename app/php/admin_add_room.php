<?php

    $con=mysqli_connect("localhost","root","","roomservice");

    // Check connection
    if (mysqli_connect_errno())
    {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $id2=$_POST['id2'];
    $tunnus=$_POST['tunnus'];    

    $check = checkData($id2, $tunnus);    

    if (($check)) {

        $sql="INSERT INTO room (user_ID, room_ID) VALUES ('$id2','$tunnus')";

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

    function checkData($id2, $tunnus) {

        if (
           ($id2 != '') && ($tunnus != '') &&           
           (strlen($id2) < 1000) && (strlen($tunnus) < 1000) &&
           ( (is_numeric($id2)) && (!strstr($id2, '.')) ) &&
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