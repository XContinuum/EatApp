<?php
    require("../requests/receive_information.php");

    if (isAdminLogged()==1)
    {
        //if admin is logged
        header("Location: ../panel/admin/index.php");
    }
    else
        if (isUserLogged()==1)
        {
            //if the user is logged
            header("Location: ../index.php");
        }
        else
        {
            //Neither are logged
            $page='forgot_pass.html';

            ob_start();
            require_once($page);
            $content=ob_get_clean();

            $head_param= "<script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.3.min.js'></script>";
            $head_param.= "<script src='resend_password.js'></script>";

            include("../template.html");
        }
?>
