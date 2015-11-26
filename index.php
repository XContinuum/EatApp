<?php
    require("requests/receive_information.php");
    $username=get_restaurant_username();

    if ($username!=-1)
    {
        //Restaurant logged
        $panel="<div style='display:inline-block;margin:10px;'><a href='/".$username."' style='color:#848b98;'>".$username."</a></div>";
        $panel.="<div style='display:inline-block;margin:10px;'><a href='requests/log_out_request.php' style='color:#848b98;'>Logout</a></div>";
    }
    else
    {
        //Restaurant not logged
        $panel="<div style='display:inline-block;margin:10px;'><a href='sign_up/index.php' style='color:#848b98;'>Sign up</a></div>";
        $panel.="<div style='display:inline-block;margin:10px;'><a href='login/index.php' style='color:#848b98;'>Login</a></div>";
    }

    include("index_template.html");
?>
