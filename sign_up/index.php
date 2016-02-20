<?php
    require("../requests/receive_information.php");

    if (isAdminLogged()==1)
    {
        //if admin is logged
        header("Location: ../panel/admin/index.php");
    }
    else
        if (isOwnerLogged()==1)
        {
            //if the chain owner is logged
            header("Location: ../index.php");
        }
        else
        {
            //Neither are logged
            $title="EatApp - Sign up";

            $content=file_get_contents("sign_up_form.html");

            $head_param= "<script src='imagecrop.js'></script>";
            $head_param.= "<script src='sign_up_script.js'></script>";
            $head_param.="<link rel='stylesheet' type='text/css' href='sign_up_style.css' >";

            $head_param.="<script src='time_picker.js'></script>";

            include("../template.html");
        }
?>
