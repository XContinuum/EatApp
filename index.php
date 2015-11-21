<?php
    session_start();
    if (isset($_SESSION['token']))
    {
        $user_name=get_restaurant_username();

        include("index_template_logged.html");
    }
    else
    {
        include("index_template.html");
    }


    function get_restaurant_username()
    {
        require('requests/server_connection.php');

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
            return -1;
        }
    }
?>
