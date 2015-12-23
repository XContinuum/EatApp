<?php
    if (isset($_POST["sign_up_button"]))
    {
    require("hash_algorithm.php");
    require("server_connection.php");
    require("receive_information.php");


    //Collect data
    $FA_Email=$_POST['FA_Email'];
    $FA_Username=$_POST['FA_Username'];
    $FA_Password=$_POST['FA_Password'];
    $FA_Confirm_Password=$_POST['FA_Confirm_Password'];
    $FA_Restaurant_Name=$_POST['FA_Restaurant_Name'];

    $FA_Country=$_POST['FA_Country'];
    $FA_State_Province=$_POST['FA_State_Province'];
    $FA_City=$_POST['FA_City'];
    $FA_Address=$_POST['FA_Address'];
    $FA_Postal_Code=$_POST['FA_Postal_Code'];

    $FA_Hash = md5(rand(0,1000));
    $FA_Username=strtolower($FA_Username);
    //Registration
    $register=true;

    if ($FA_Password!=$FA_Confirm_Password)
    {
        $register=false;
       //Passwords are not the same
    }
    else
        if (strlen($FA_Password)<6)
        {
            $register=false; //Password has to be at least 6 characters
        }

    if ($register==true)
    {
        $password_hash=PassHash::hash($FA_Password);

        $sql="INSERT INTO FA_RESTORANTS (FA_Email,FA_Pass,FA_Username,FA_Restaurant_Name, FA_Country,FA_State_Province,FA_City,FA_Address,FA_Postal_Code,FA_Hash) ";
        $sql.="VALUES ('$FA_Email','$password_hash','$FA_Username','$FA_Restaurant_Name','$FA_Country','$FA_State_Province','$FA_City','$FA_Address','$FA_Postal_Code','$FA_Hash')";

        if (mysqli_query($conn, $sql))
        {
            //Logged in!
            $salt=rand(403,600)."P20x".rand(760,930);
            $string=time().$salt.$FA_Email.$salt;
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

            sendEmail($FA_Email,$FA_Username,$FA_Hash);
            header("Location: ../user/setup_menu.php?verify=1");
        }
        else
            {
                //error
                header("Location: ../sign_up/index.php?connection=failed");
            }
    }

    if ($register==false)
    {
        header("Location: ../sign_up/index.php"); //error
    }

    mysqli_close($conn);
    }
    else
    {
        header("Location: ../sign_up/index.php");
    }
?>
