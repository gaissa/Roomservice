<?php

    $con=mysqli_connect("localhost","root","","roomservice");

    // Check connection
    if (mysqli_connect_errno())
    {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $id=$_POST['id'];
    $taso=$_POST['taso'];
    $nimi=$_POST['nimi'];
    $email=$_POST['email'];
    $salasana=$_POST['salasana'];

    $check = checkData($id, $taso, $nimi, $email, $salasana);
    $mail = validateEmail($email);

    if (($check) && ($mail)) {

        $sql="INSERT INTO users (ID, username, password, email, userlevel) VALUES ('$id','$nimi','$salasana','$email',$taso)";

        if (!mysqli_query($con,$sql))
        {
            header('Location: admin.php'); 
        }

        mysqli_close($con);

        header('Location: admin.php'); 
    }

    else {

        header('Location: admin.php'); 
    }
    
    function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    # validate input
    function checkData($id, $taso, $nimi, $email, $salasana) {

        if (
           ($id != '') && ($taso != '') && ($nimi != '') && ($email != '') &&
           ($salasana != '') &&
           (strlen($id) < 1000) && (strlen($taso) < 2) && (strlen($nimi) < 21) &&
           (strlen($email) < 51) && (strlen($salasana) < 41) &&
           ( (is_numeric($id)) && (!strstr($id, '.')) ) &&
           ( (is_numeric($taso)) && (!strstr($taso, '.')) )
           ) {

            $validated = true;
        }

        else {

            $validated = false;
        }

        return $validated;
    }

?>