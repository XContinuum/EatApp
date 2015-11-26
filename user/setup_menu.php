<?php
    session_start();

    if (!isset($_SESSION['token']))
    {
        //user not logged
        header("Location: index.php");
    }
    else
    {
        if ($_GET['saved']==1)
        {
            $success_style="style='display:block;'";
        }
        else
        {
            $success_style="style='display:none;'";
        }

        //Load menu+++
        require("../requests/receive_information.php");
        require("../requests/load_menu.php");

        $restaurant_id=get_restaurant_id();
        $menu=FillMenuBlanks($restaurant_id);
        $res_username=get_restaurant_username();
        //Load menu---

        ob_start();
        require_once("setup_menu_form.html");
        $content=ob_get_clean();

        $head_param ="<script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.3.min.js'></script>";
        $head_param.="<link href='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css' rel='stylesheet'/>";
        $head_param.="<script src='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js'></script>";
        $head_param.="<script src='jquery_setup_menu.js'></script>";

        include("../user_template.html");
    }
?>
