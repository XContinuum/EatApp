<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require("../requests/receive_information.php");

if (isAdminLogged()==1)
{
    //if admin is logged
    header("Location: ../panel/admin/index.php");
}
else
    if (isOwnerLogged()==1)
    {
        //if the user is logged
        header("Location: ../index.php");
    }
    else
    {
        //Neither are logged
        $title="EatApp - Forgot Password";

        $content=file_get_contents("forgot_pass.html");
        $content=str_replace("%error%", "", $content);

        $head_param="<script src='resend_password.js'></script>";

        include("../template.html");
    }
?>
