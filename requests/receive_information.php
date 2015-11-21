<?php
    //get restautrant username if logged
    function get_restaurant_username()
    {
        require("server_connection.php");

        session_start();

        $sql = "SELECT FA_Username FROM FA_RESTORANTS WHERE FA_Token='".$_SESSION['token']."'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);

        if (isset($_SESSION['token']))
        {
            return $row['FA_Username'];
        }
        else
        {
            return -1; //not logged
        }
    }

    //get restaurant's ID if logged
    function get_restaurant_id()
    {
        require('server_connection.php');

        session_start();
        $sql = "SELECT ID FROM FA_RESTORANTS WHERE FA_Token='".$_SESSION['token']."'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);

        if (isset($_SESSION['token']))
        {
            return $row['ID'];
        }
        else
        {
            return -1; //not logged
        }
    }

    //fetch different infos about the restaurant based on the username
    function getInfo($username_,$info)
    {
        require("server_connection.php");

        $sql = "SELECT ID,FA_Restaurant_Name,FA_Postal_Code,FA_Address FROM FA_RESTORANTS WHERE FA_Username='$username_'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        mysqli_close($conn);

        return $row[$info];
    }


    //check if username is in the database
    function checkUsernameStatus($username_)
    {
        require("server_connection.php");

        $sql="SELECT FA_Username FROM FA_RESTORANTS WHERE FA_Username='$username_'";
        $result=mysqli_query($conn,$sql);

        if (mysqli_num_rows($result)>0)
        {
            return 1; //user found
        }
        else
        {
            return 0; //no user
        }

        mysqli_close($conn);
    }

    //set a global path for files that are in different directories
    function setLink($string)
    {
        $ini_array=parse_ini_file("settings.ini", true);

        $path=$ini_array['server']['path'];
        echo "'".$path.$string."'";
    }
?>
