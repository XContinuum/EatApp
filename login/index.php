<?php
require("../requests/receive_information.php");

/*Admin logged*/
if (isAdminLogged())
{
    header("Location: ../panel/admin/index.php");
}
else
    /*Chain owner is logged*/
    if (isOwnerLogged())
    {
        header("Location: ../index.php");
    }
    /*No one is logged*/
    else
    {
        $error="";
        $title="EatApp - Login";

        if ($_GET["error"]=="1")
            $error="<div id='error_box' style='top:0px;'>Login or password incorrect!</div>";

        $content=file_get_contents("login_box.html");
        $content=str_replace("%error%",$error,$content);

        include("../user_template.html");
    }
?>
