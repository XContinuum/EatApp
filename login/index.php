<?php
    require("../requests/receive_information.php");

    if (isAdminLogged()==1)
    {
        //Admin logged
        header("Location: ../panel/admin/index.php");
    }
    else
    if (isUserLogged()==1)
    {
        //User is logged
        header("Location: ../index.php");
    }
    else
    {
        //No one is logged
        $error="";

        if ($_GET["error"]=="1")
            $error="<div id='error_box' style='top:0px;'>Login or password incorrect!</div>";

        ob_start();
        require_once('login_box.html');
        $content = ob_get_clean();

        include("../template.html");
    }

?>
