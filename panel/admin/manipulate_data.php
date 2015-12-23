<?php
    if (isset($_GET['id']) && isset($_GET['type']))
    {
        $type=$_GET['type'];
        $id=$_GET['id'];

        require("server_connection.php");

        if ($type=='validate')
        {
            //validation
            $sql = "UPDATE FA_RESTORANTS SET FA_Validated='1' WHERE ID='$id'";
            $result = mysqli_query($conn,$sql);
        }
        else
        if ($type=='unvalidate')
        {
            //unvalidation
            $sql = "UPDATE FA_RESTORANTS SET FA_Validated='0' WHERE ID='$id'";
            $result = mysqli_query($conn,$sql);
        }
        else
            if ($type=='ban')
            {
               //ban
            }

    }
?>
