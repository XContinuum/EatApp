<?php
require("../../requests/receive_information.php");

if (isAdminLogged() && isset($_GET['id']) && isset($_GET['type']))
{
    $type=$_GET['type'];
    $id=$_GET['id'];

    $db=new Db();

    switch ($type)
    {
        case "validate":
            $sql = "UPDATE CHAIN_OWNER SET Validated='1' WHERE ID='$id'";
            $db->query($sql);
        break;

        case "unvalidate":
            $sql = "UPDATE CHAIN_OWNER SET Validated='0' WHERE ID='$id'";
            $db->query($sql);
        break;

        case "ban":
        break;
    }
}
?>
