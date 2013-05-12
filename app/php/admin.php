<?php
	session_start();
	
	if ($_SESSION["admin"] == false) {
		header("location:../index.php");
	}	
	
	if(!isset($_SESSION["logged_in"])) {
		header("location:../mainpage.html");
	}
	
    // Database config
    require_once('../conf/config.php');

    // Connect to server and select databse.
    mysql_connect("$db_host", "$db_user", "$db_pass")or die("cannot connect");
    mysql_select_db("$db_name")or die("cannot select DB");

    $sql = "SELECT * FROM users";
    $result = mysql_query($sql);

    $sql2 = "SELECT * FROM room";
    $result2 = mysql_query($sql2);

?>

<!DOCTYPE html>
<html>


<head>

    <title>Roomservice</title>

    <meta charset="utf-8">

    <link href="../css/normalize.css" rel="stylesheet" type="text/css"/>
    <link href="../css/datepicker.css" rel="stylesheet" type="text/css"/>
    <link href="../css/adminstyle.css" rel="stylesheet" type="text/css"/>

</head>

<body>

    <table border="0" cellspacing="1" cellpadding="0">
        <tr>
            <td>

                <form name="form1" method="post" action="">
                <table border="8" cellpadding="3" cellspacing="4" bgcolor="#000">

                <tr>

                    <td bgcolor="#AAA">&nbsp;</td>
                    <td colspan="5" bgcolor="#AAA"><strong><h2>Käyttäjät</h2></strong> </td>

                </tr>

                <tr>

                    <td width="40" align="center" bgcolor="#BBB">#</td>
                    <td bgcolor="#BBB"><strong>Id</strong></td>
                    <td bgcolor="#BBB"><strong>Käyttäjätaso</strong></td>
                    <td bgcolor="#BBB"><strong>Nimi</strong></td>
                    <td bgcolor="#BBB"><strong>Email</strong></td>
                    <td bgcolor="#BBB"><strong>Salasana (SHA-1)</strong></td>

                </tr>

                <?php while ($rows = mysql_fetch_array($result)): ?>

                <tr>

                    <td align="center" bgcolor="#FFFFFF">

                    <input name="need_delete[<?php echo $rows['ID']; ?>]" type="checkbox" id="checkbox[<?php echo $rows['ID']; ?>]" value="<?php echo $rows['ID']; ?>">

                    </td>

                    <td bgcolor="#FFF"><?php echo $rows['ID']; ?></td>
                    <td bgcolor="#FFF"><?php echo htmlspecialchars($rows['userlevel']); ?></td>
                    <td bgcolor="#FFF"><?php echo htmlspecialchars($rows['username']); ?></td>
                    <td bgcolor="#FFF"><?php echo htmlspecialchars($rows['email']); ?></td>
                    <td bgcolor="#FFF"><?php echo htmlspecialchars($rows['password']); ?></td>

                </tr>

                <?php endwhile; ?>

                <tr>

                    <td colspan="6" align="left" bgcolor="#555">
                    <input name="delete" type="submit" id="delete" value="Poista"></td>
                </tr>                

                <?php

                    // Check if delete button is active
                    if (!empty($_POST['delete'])) {

                        foreach ($_POST['need_delete'] as $id => $value) {

                            $sql = "DELETE FROM users WHERE ID='$value'";
                            mysql_query($sql);
                        }

                        echo '<meta http-equiv="refresh" content="0;URL=admin.php">';
                    }
                ?>

                </table>
                </form>
            </td>
            
        </tr>
        
    </table>
    
    <div id="wrap">
    
        <form name="add" action="add_admin.php" method="post"> 
    
            Id <input type="text" name="id">
            Käyttäjätaso <input type="text" name = "taso"> 
            nimi <input type="text" name = "nimi">
            email <input type="text" name = "email">
            salasana <input type="text" name = "salasana">
        
            <input align="left" type="submit" value="Lisää">    
        
        </form>   
        
     </div>
               

    <table border="0" cellspacing="1" cellpadding="0">
        <tr>
            <td>

                <form name="form1" method="post" action="">
                <table border="8" cellpadding="3" cellspacing="4" bgcolor="#000">

                <tr>

                    <td bgcolor="#AAA">&nbsp;</td>
                    <td colspan="4" bgcolor="#AAA"><strong><h2>Huoneet</h2></strong> </td>

                </tr>

                <tr>

                    <td width="40" align="center" bgcolor="#BBB">#</td>
                    <td colspan="3" bgcolor="#BBB"><strong>Id</strong></td>
                    <td bgcolor="#BBB"><strong>Huoneen tunnus</strong></td>

                </tr>

                <?php while ($rows = mysql_fetch_array($result2)): ?>

                <tr>

                    <td align="center" bgcolor="#FFFFFF">

                    <input name="need_delete[<?php echo $rows['ID']; ?>]" type="checkbox" id="checkbox[<?php echo $rows['ID']; ?>]" value="<?php echo $rows['ID']; ?>">

                    </td>

                    <td colspan="3" bgcolor="#FFF"><?php echo $rows['ID']; ?></td>
                    <td bgcolor="#FFF"><?php echo $rows['room_ID']; ?></td>

                </tr>

                <?php endwhile; ?>

                <tr>

                    <td colspan="5" align="left" bgcolor="#555">
                    <input name="delete" type="submit" id="delete" value="Poista"></td>

                </tr>

                <?php

                    // Check if delete button is active
                    if (!empty($_POST['delete'])) {

                        foreach ($_POST['need_delete'] as $id => $value) {

                            $sql2 = "DELETE FROM room WHERE ID='$value'";
                            mysql_query($sql2);
                        }

                        echo '<meta http-equiv="refresh" content="0;URL=admin.php">';
                    }

                    mysql_close();
                ?>

                </table>
                </form>
            </td>
        </tr>
    </table>
    
    <div id="wrap">
    
        <form name="add" action="#" method="post"> 
    
            Id <input type="text" name="id">
            Tunnus <input type="text" name = "tunnus">             
        
            <input align="left" type="submit" value="Lisää">    
        
        </form>   
        
     </div>

</body>

</html>