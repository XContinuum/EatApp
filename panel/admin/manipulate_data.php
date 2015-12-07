<?php
    if (isset($_GET['id']) && isset($_GET['type']))
    {
        $type=$_GET['type'];
        $id=$_GET['id'];

        if ($type=='validate')
        {
            //validation
            require("server_connection.php");

            $sql = "UPDATE FA_RESTORANTS SET FA_Validated='1' WHERE ID='$id'";
            $result = mysqli_query($conn,$sql);
        }
        else
            if ($type=='ban')
            {
               //ban
            }
    }
?>
