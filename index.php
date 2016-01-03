<?php
    require("requests/receive_information.php");

    if (isAdminLogged()==1)
    {
        $username=getAdminUsername();

        $panel="<div class='abc'><a href='/panel/admin/index.php'>".$username."</a></div>";
        $panel.="<div class='abc'><a href='requests/log_out_request.php'>Logout</a></div>";
    }
    else
    if (isUserLogged()==1)
    {
        //Restaurant logged
        $username=get_restaurant_username();

        $panel="<div style='height:50px;width:200px;' onMouseOver='show_drop_down();' onMouseOut='hide_drop_down();'>"; // MOD 2017 width changed from 100px to 200px
        $panel.="<div id='username_bar'><a href='/".$username."'>".$username."</a></div>";
        $panel.="<div id='profile_picture'></div></div>";
    }
    else
    {
        //Restaurant not logged
        $panel="<div class='abc'><a href='sign_up/index.php'>Sign up</a></div>";
        $panel.="<div class='abc'><a href='login/index.php'>Login</a></div>";
    }


    include("index_template.html");
?>
