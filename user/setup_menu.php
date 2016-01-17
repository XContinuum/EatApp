<?php
    require("../requests/receive_information.php");

    if (isUserLogged()==0 && isAdminLogged()==0)
    {
        //user not logged
        header("Location: ../index.php");
    }
    else
    {
        //Load menu+++
        require("../requests/load_menu.php");
        $panel=setPanel();

        $menu=FillMenuBlanks(get_restaurant_id());
        $res_username=get_restaurant_username();
        //Load menu---

        $confirm_email="style='display:none;'";
        $success_style="style='display:none;'";


        if ($_GET['saved']==1)
            $success_style="style='display:block;'";

        if ($_GET['verify']==1)
        {
            $confirm_email="style='display:block;'";
            $restaurant_email=getInfo($res_username,'FA_Email');
        }

        ob_start();
        require_once("setup_menu_form.html");
        $content=ob_get_clean();

        //For filter select+++
        $head_param="<link href='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css' rel='stylesheet'/>";
        $head_param.="<script src='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js'></script>";
        //For filter select---

        $head_param.="<script src='js/imagecrop.js'></script>";
        $head_param.="<script src='setup_menu.js'></script>";
        $head_param.="<script src='send_email.js'></script>";


        include("../user_template.html");
    }

?>
