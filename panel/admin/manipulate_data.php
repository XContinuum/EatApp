<?php
    if (isset($_GET['id']) && isset($_GET['type']))
    {
        $type=$_GET['type'];
        $id=$_GET['id'];

        require("../../requests/receive_information.php");
        $db=new Db();

        if ($type=='validate')
        {
            //validation
            $sql = "UPDATE CHAIN_OWNER SET Validated='1' WHERE ID='$id'";
            $db->query($sql);
        }
        else
        if ($type=='unvalidate')
        {
            //unvalidation
            $sql = "UPDATE CHAIN_OWNER SET Validated='0' WHERE ID='$id'";
            $db->query($sql);
        }
        else
            if ($type=='ban')
            {
               //ban
            }

    }
?>
