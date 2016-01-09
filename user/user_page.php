<?php
    $restaurant_id=get_restaurant_id();
    $menu=LoadMenu($restaurant_id);
    $last_modified=getLastModified($restaurant_id);

    $address=getInfo($username,'FA_Address');
    $restaurant_name=getInfo($username,'FA_Restaurant_Name');
    $phone_number=getInfo($username,'FA_Phone_Number');
    $website=getInfo($username,'FA_Website');

    if ($menu!="0")
    {
        //if menu is set
        $first_row="<table id='menu_list' style='width:100%;background-color:white;border:solid 1px #ced1d7;'>";

        $a=$menu;
        $menu="<table id='menu_list'>".$first_row.$a."</table>";
    }
    else
    {
        //if there is no menu
        $menu="";
        $last_modified="";
    }

    ob_start();
    require_once("user_content.html");
    $content=ob_get_clean();
?>
