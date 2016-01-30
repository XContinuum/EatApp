<?php
    $restaurant_id=getChainId(); //MOD 2017 changed from get_restaurant_id()
    $menu=LoadMenu($restaurant_id);

    $address=getInfo($username,'FA_Address');
    $restaurant_name=getInfo($username,'FA_Restaurant_Name');
    $phone_number=getInfo($username,'FA_Phone_Number');
    $website=getInfo($username,'FA_Website');

    $image_src=getPicLink($username);

    if ($menu!="0")
    {
        //if menu is set
        $last_modified=getLastModified($restaurant_id);
        $pre_menu="<a href='user/setup/setup_menu.php' style='color:#4d85f2;'>Edit menu</a>";
        $menu="<table id='menu_list'>".$menu."</table>";
    }
    else
    {
        //if there is no menu
        $last_modified="";
        $pre_menu="It seems that you haven't uploaded a menu yet.<br>";
        $pre_menu.="<a href='user/setup/setup_menu.php' style='color:#4d85f2;'>Add menu</a>";
        $menu="";
    }

    ob_start();
    require_once("user_content.html");
    $content=ob_get_clean();
?>
