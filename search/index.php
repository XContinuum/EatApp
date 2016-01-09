<?php
    $search_query=$_GET["q"];

    require("../requests/receive_information.php");
    $username_logged=get_restaurant_username(); //get the username from the session

    //Panel+++
    if (isUserLogged()==1)
    {
        //Restaurant logged
        $username=get_restaurant_username();
        $image_src="../restaurant_data/Profile/".getInfo($username,"FA_Pic");

        if (getInfo($username,"FA_Pic")=="none" || getInfo($username,"FA_Pic")==0)
        {
            $image_src=setLinkMute("/images/default.png");
        }

        $panel="<div id='user_top_panel'>";
        $panel.="<div id='username_bar'><a href='/".$username."'>".$username."</a></div>";
        $panel.="<div id='profile_picture'><img src=$image_src id='image_circle'/></div>";
        $panel.="</div>";
    }
    else
    if (isAdminLogged()==1)
    {
        $username=getAdminUsername();

        $panel="<div class='abc'><a href='/panel/admin/index.php'>".$username."</a></div>";
        $panel.="<div class='abc'><a href='requests/log_out_request.php'>Logout</a></div>";
    }
    else
    {
        //Restaurant not logged
        $panel="<div id='top_sign_in'><div class='abc'><a href='../sign_up/index.php'>Sign up</a></div>";
        $panel.="<div class='abc'><a href='../login/index.php'>Login</a></div></div>";
    }
    //Panel---

    $head_param="<script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.3.min.js'></script>";
    $head_param.="<script src='../user/show_partitions.js'></script>";
    $head_param.="<script src='js/ajaxSearch.js'></script>";

    ob_start();
    require_once("search_form.html");
    $content=ob_get_clean();

    include("../user_template.html");
    ?>
