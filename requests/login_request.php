<?php
    if (isset($_POST["login_submit"]))
    {
        require ("hash_algorithm.php");
        require ("server_connection.php");

        $FA_Email=htmlspecialchars($_POST['FA_Email']);
        $FA_Password=htmlspecialchars($_POST['FA_Password']);

        //Login check up
        $sql = "SELECT FA_Pass,FA_Email FROM FA_RESTORANTS WHERE FA_Email='$FA_Email'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);

        if (PassHash::check_password($row["FA_Pass"], $FA_Password))
        {
            //Logged in!
            $salt=rand(403,600) . "P20x" . rand(760,930);
            $string=time() . $salt . $row["FA_Email"] . $salt;
            $token=sha1($string);

            $t=3600; //set time of destruction

            if (isset($_POST["remember_me"]))
            {
                $t=3600*24*365; //set time of destruction
            }

            $sql = "UPDATE FA_RESTORANTS SET FA_Token='$token' WHERE FA_Email='$FA_Email'";
            mysqli_query($conn,$sql);

            session_start();

            $_SESSION['token']=$token;
            $_SESSION['time']=$t;
            echo "<script> location.replace('../index.php'); </script>";

        }
        else
        {
            echo "<script> location.replace('../login.php?error=1'); </script>";
        }

        mysqli_close($conn);
    }
?>
