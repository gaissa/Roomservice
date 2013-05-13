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
	$sha1salasana = sha1($salasana);
	
    $check = checkData($id, $taso, $nimi, $email, $sha1salasana);
    $mail = validateEmail($email);

    if (($check) && ($mail)) {

        $sql="INSERT INTO users (ID, username, password, email, userlevel) VALUES ('$id','$nimi','$sha1salasana','$email',$taso)";

        if (!mysqli_query($con,$sql))
        {
            header('Location: admin.php#wrap_first'); 
        }

        mysqli_close($con);

        header('Location: admin.php#wrap_first'); 
    }

    else {

        header('Location: admin.php#wrap_first'); 
    }
    
    # validate email
    function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    # validate input
    function checkData($id, $taso, $nimi, $email, $sha1salasana) {

        if (
           ($id != '') && ($taso != '') && ($nimi != '') && ($email != '') &&
           ($sha1salasana != '') &&
           (strlen($id) < 1000) && (strlen($taso) < 2) && (strlen($nimi) < 21) &&
           (strlen($email) < 51) && (strlen($sha1salasana) < 41) &&
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