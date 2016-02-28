<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

    require_once("../requests/receive_information.php");
    require_once("../requests/load_restaurant_list.php");


    $CHAIN_Link=htmlspecialchars($_GET["link"]); //get the link from the url
    $CHAIN_Logged=getChainLink(); //get Chain Link from the session

    $panel=setPanel();
    $head_param="";

    if (strtolower($CHAIN_Logged)==strtolower($CHAIN_Link))
    {
         //If the CHAIN is logged a new page appears where OWNER can edit his/her content as well as settings
        include("chain_page.php");
    }
    else
    {
        //if the LINK in the url is not the same as the LINK of the logged CHAIN OWNER
        include("show_restaurant.php");
    }

    include("../user_template.html");
?>
