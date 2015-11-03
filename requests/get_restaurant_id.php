<?php
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
            return -1;
        }
    }
?>
