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
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script type="text/javascript" src="../js/admin.js"></script>

</head>

<body>

    <div id="rss" class="logout"><a href="../admin_index.php">Takaisin</a></div>
    <div id="rooms" class="logout"><a href="#">Huoneet XML</a></div>
    <div id="users" class="logout"><a href="#">Käyttäjät XML</a></div>

    <div id="wrap_first">

        <table border="0">
            <tr>
                <td>

                    <form name="form1" method="post" action="">
                    <table border="8" bgcolor="#000">

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

                                $sql666 = "DELETE FROM reservations WHERE user_ID='$value'";
                                mysql_query($sql666);
                            }

                            echo '<meta http-equiv="refresh" content="0;URL=admin.php">';
                        }
                    ?>

                    </table>
                    </form>
                </td>

            </tr>

        </table>

    </div>

    <div id="wrap">

        <form name="add" action="admin_add_user.php" method="post">

            Id <input type="text" name="id">
            Käyttäjätaso <input type="text" name = "taso">
            Nimi <input type="text" name = "nimi">
            Email <input type="text" name = "email">
            Salasana <input type="password" name = "salasana">

            <input align="left" type="submit" value="Lisää">

        </form>

     </div>


    <table border="0">
        <tr>
            <td>

                <form name="form2" method="post" action="">
                <table border="8" bgcolor="#000">

                <tr>

                    <td bgcolor="#AAA">&nbsp;</td>
                    <td bgcolor="#AAA"><strong><h2>Huoneet</h2></strong> </td>

                </tr>

                <tr>

                    <td width="40" align="center" bgcolor="#BBB">#</td>
                   
                    <td bgcolor="#BBB"><strong>Huoneen tunnus</strong></td>

                </tr>

                <?php while ($rows = mysql_fetch_array($result2)): ?>

                <tr>

                    <td align="center" bgcolor="#FFFFFF">

                    <input name="need_delete[<?php echo $rows['room_ID']; ?>]" type="checkbox" id="checkbox[<?php echo $rows['room_ID']; ?>]" value="<?php echo $rows['room_ID']; ?>">

                    </td>

                   
                    <td bgcolor="#FFF"><?php echo $rows['room_ID']; ?></td>

                </tr>

                <?php endwhile; ?>

                <tr>

                    <td colspan="2" align="left" bgcolor="#555">
                    <input name="delete" type="submit" id="delete" value="Poista"></td>

                </tr>

                <?php

                    // Check if delete button is active
                    if (!empty($_POST['delete'])) {

                        foreach ($_POST['need_delete'] as $room_ID => $value) {

                            $sql2 = "DELETE FROM room WHERE room_ID='$value'";
                            mysql_query($sql2);

                            $sql666 = "DELETE FROM reservations WHERE room_ID='$value'";
                            mysql_query($sql666);

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

        <form name="add2" action="admin_add_room.php" method="post">
           
            Huoneen tunnus <input type="text" name="tunnus">

            <input align="left" type="submit" value="Lisää">

        </form>

     </div>

</body>

</html>