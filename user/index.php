<?php
    $username=htmlspecialchars($_GET["username"]); //get the username from the url

    require("../requests/receive_information.php");
    require("../requests/load_menu.php");

    $username_logged=get_restaurant_username(); //get the username from the session

    if (strtolower($username_logged)!=strtolower($username))
    {
        //if the username in the url is not the same as the useranme of the logged user is
        include("show_restaurant.php");
    }
    else
    {
        //If the user is logged a new page appears where he/she can edit his/her content as well as settings
        include("user_page.php");
    }

    include("../user_template.html");
?>
