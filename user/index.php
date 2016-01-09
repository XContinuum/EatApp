<?php
    $username=htmlspecialchars($_GET["username"]); //get the username from the url

    require("../requests/receive_information.php");
    require("../requests/load_menu.php");

    $username_logged=get_restaurant_username(); //get the username from the session

    //PANEL
    if (isUserLogged()==1)
    {
        $image_src="../restaurant_data/Profile/".getInfo($username_logged,"FA_Pic");

        $panel="<div id='user_top_panel'>";
        $panel.="<div id='username_bar'><a href='/".$username_logged."'>".$username_logged."</a></div>";
        $panel.="<div id='profile_picture'><img src='$image_src' id='image_circle'/></div>";
        $panel.="</div>";
    }
    else
        if (isAdminLogged()==1)
        {
            $get_username=getAdminUsername();

            $panel="<div style='position:absolute;right:0px;top:0px;vertical-align:top;margin-top:10px;margin-right:10px;'>";
            $panel.="<a href='../requests/log_out_request.php'>Logout</a></div>";
            $panel.="<div style='float:right;vertical-align:top;margin-top:10px;margin-right:15px;'>";
            $panel.="<a href='../panel/admin/index.php'>$get_username</a></div>";
        }
        else
            {
                $panel="<div style='float:right;vertical-align:top;margin-top:10px;margin-right:10px;'>";
                $panel.="<a href='../login/index.php'>Login</a></div>";
                $panel.="<div style='float:right;vertical-align:top;margin-top:10px;margin-right:15px;'>";
                $panel.="<a href='../sign_up/index.php'>Sign up</a></div>";
            }
    //PANEL

    $head_param="<script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.3.min.js'></script>";
    $head_param.="<script src='/user/show_partitions.js'></script>";

    if (strtolower($username_logged)==strtolower($username))
    {
         //If the user is logged a new page appears where he/she can edit his/her content as well as settings
        include("user_page.php");
    }
    else
    {
        //if the username in the url is not the same as the useranme of the logged user is
        include("show_restaurant.php");
    }

    include("../user_template.html");
?>
