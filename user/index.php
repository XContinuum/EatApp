<?php
    require("../requests/receive_information.php");
    require("../requests/load_menu.php");

    $username=htmlspecialchars($_GET["username"]); //get the username from the url
    $username_logged=getChainLink(); //get the username from the session: MOD 2017 changed from get_restaurant_username()

    $panel=setPanel();

    $head_param.="<script src='/user/js/show_partitions.js'></script>";

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
