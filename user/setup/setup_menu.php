<?php
    require("../../requests/receive_information.php");
    $menu_name=$_GET["name"];

    if (isOwnerLogged()==0 && isAdminLogged()==0)
    {
        //user not logged
        header("Location: ../../index.php");
    }
    else
    /*If the current Chain owner does not own a menu*/
    if (getMenuOwnerID($menu_name)!=getChainId() && getMenuOwnerID($menu_name)>-1)
    {
        header("Location: ../../index.php");
    }
    /*If the current owner owns the menu or the menu does not exist yet*/
    else
    {
        require("load_menu.php");

        ob_start();
        require_once("setup_menu_form.html");
        $content=ob_get_clean();
        $content=str_replace("%Menu%",load_editMenu($menu_name),$content);
        $content=str_replace("%name%",$menu_name,$content);

        $panel=setPanel();


        //For filter select+++
        $head_param="<link href='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css' rel='stylesheet'/>";
        $head_param.="<script src='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js'></script>";
        //For filter select---

        $head_param.="<script src='js/imagecrop.js'></script>";
        $head_param.="<script src='js/setup_menu.js'></script>";

        include("../../user_template.html");
    }

?>
