<?php
    require("requests/receive_information.php");

    if (isUserLogged()==1)
    {
        //Restaurant logged
        $username=get_restaurant_username();
        $image_src="../restaurant_data/Profile/".getInfo($username,"FA_Pic");

        $panel="<div id='user_top_panel'>";
        $panel.="<div id='username_bar'><a href='/".$username."'>".$username."</a></div>";
        $panel.="<div id='profile_picture'><img src='$image_src' id='image_circle'/></div>";
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
        $panel="<div id='top_sign_in'><div class='abc'><a href='sign_up/index.php'>Sign up</a></div>";
        $panel.="<div class='abc'><a href='login/index.php'>Login</a></div></div>";
    }


    include("index_template.html");
?>
