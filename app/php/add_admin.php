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

    $sql="INSERT INTO users (ID, username, password, email, userlevel) VALUES ('$id','$nimi','$salasana','$email',$taso)";

    if (!mysqli_query($con,$sql))
    {
      die('Error: ' . mysqli_error($con));
    }
    
    echo "1 record added";

    mysqli_close($con);
    
    $url = 'admin.php';
    echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL='.$url.'">';        
     
?> 