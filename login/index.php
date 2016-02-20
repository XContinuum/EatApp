<?php
    require("../requests/receive_information.php");

    if (isAdminLogged()==1)
    {
        //Admin logged
        header("Location: ../panel/admin/index.php");
    }
    else
        if (isOwnerLogged()==1)
        {
            //Chain owner is logged
            header("Location: ../index.php");
        }
        else
        {
            //No one is logged
            $error="";
            $title="EatApp - Login";

            if ($_GET["error"]=="1")
                $error="<div id='error_box' style='top:0px;'>Login or password incorrect!</div>";

            $content=file_get_contents("login_box.html");
            $content=str_replace("%error%",$error,$content);

            include("../template.html");
        }
?>
