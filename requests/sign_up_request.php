<?php
    if (isset($_POST["sign_up_button"]))
    {
    require ("hash_algorithm.php");
    require ("server_connection.php");

    //Collect data
    $FA_Email=$_POST['FA_Email'];
    $FA_Password=$_POST['FA_Password'];
    $FA_Username=$_POST['FA_Username'];
    $FA_Confirm_Password=$_POST['FA_Confirm_Password'];
    $FA_Restaurant_Name=$_POST['FA_Restaurant_Name'];

    $FA_Country=$_POST['FA_Country'];
    $FA_State_Province=$_POST['FA_State_Province'];
    $FA_City=$_POST['FA_City'];
    $FA_Address=$_POST['FA_Address'];
    $FA_Postal_Code=$_POST['FA_Postal_Code'];

    //Registration
    $register=true;

    if ($FA_Password!=$FA_Confirm_Password)
    {
        $register=false;
       // echo "Passwords not the same!";
    }
    else
    if (strlen($FA_Password)<6)
    {
        $register=false;
       // echo "Password has to be at least 6 characters!";
    }

    if ($register==true)
    {
    $hash=PassHash::hash($FA_Password);

    $sql = "INSERT INTO FA_RESTORANTS (FA_Email,FA_Pass,FA_Username,FA_Restaurant_Name, FA_Country,FA_State_Province,FA_City,FA_Address,FA_Postal_Code) VALUES ('$FA_Email', '$hash','$FA_Username','$FA_Restaurant_Name','$FA_Country','$FA_State_Province','$FA_City','$FA_Address','$FA_Postal_Code')";

    if (mysqli_query($conn, $sql))
    {
        //Create session
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

        echo "<script> location.replace('../user/setup_menu.php'); </script>"; // MOD 2017
    }
    else
    {
        //error
        echo "<script> location.replace('../sign_up/index.php'); </script>"; // MOD 2017
    }
    }

    if ($register==false)
    {
        //error
        echo "<script> location.replace('../sign_up/index.php'); </script>"; // MOD 2017
    }

    mysqli_close($conn);
    }
?>
