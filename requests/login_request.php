<?php
  if (isset($_POST["login_submit"]))
    {
        require ("hash_algorithm.php");
        require ("server_connection.php");

        $DB_Email=htmlspecialchars($_POST['Email']);
        $DB_Password=htmlspecialchars($_POST['Password']);

        //Login check up
        $sql = "SELECT Password,Email FROM CHAIN_OWNER WHERE Email='$DB_Email'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);


        if (PassHash::check_password($row["Password"], $DB_Password)) // MOD 2017 changed $FA_Password to $DB_Password
        {
            //Logged in!
            $salt=rand(403,600) . "P20x" . rand(760,930);
            $string=time() . $salt . $row["Email"] . $salt;
            $token=sha1($string);

            $t=3600; //set time of destruction

            if (isset($_POST["remember_me"]))
            {
                $t=3600*24*365; //set time of destruction
            }

            $sql = "UPDATE CHAIN_OWNER SET Token='$token' WHERE Email='$DB_Email'";
            mysqli_query($conn,$sql);

            session_start();

            $_SESSION['chain_owner_token']=$token;
            $_SESSION['time']=$t;

            header("Location: ../index.php");
        }
        else
        {
            //Checking if ADMIN Logged in+++
            $sql = "SELECT Username,Password FROM ADMIN_PANEL WHERE Username='$DB_Email'";
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($result);
            //---

            if (PassHash::check_password($row["Password"], $DB_Password)) // MOD 2017 changed $FA_Password to $DB_Password
            {
                 //Logged in!
                $salt=rand(403,600) . "P20x" . rand(760,930);
                $string=time() . $salt . $row["Username"] . $salt;
                $token=sha1($string);

                $t=3600; //set time of destruction

                if (isset($_POST["remember_me"]))
                {
                    $t=3600*24*365; //set time of destruction
                }

                $sql = "UPDATE ADMIN_PANEL SET Token='$token' WHERE Username='$DB_Email'";
                mysqli_query($conn,$sql);

                session_start();

                $_SESSION['admin_token']=$token;
                $_SESSION['time']=$t;

                header("Location: ../panel/admin/index.php");
            }
            else
            {
                header("Location: ../login/index.php?error=1");
            }
        }

        mysqli_close($conn);
    }
?>
