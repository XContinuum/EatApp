<?php
require("../requests/receive_information.php");

/*if admin is logged*/
if (isAdminLogged()==1)
{
    header("Location: ../panel/admin/index.php");
}
else
    /*if the chain owner is logged*/
    if (isOwnerLogged()==1)
    {
        header("Location: ../index.php");
    }
    /*No one is logged*/
    else
    {
        $title="EatApp - Sign up";

        $content=file_get_contents("sign_up_form.html");

        $head_param="<script src='imagecrop.js'></script>";
        $head_param.= "<script src='sign_up_script.js'></script>";
        $head_param.="<link rel='stylesheet' type='text/css' href='sign_up_style.css' >";

        include("../user_template.html");
    }
?>
