<?php
    session_start();

    if (!isset($_SESSION['token']))
    {
      header("Location: index.php");
    }
    else
    {
    require('requests/load_menu.php');
    require('requests/get_restaurant_id.php');

    $restaurant_id=get_restaurant_id();
    $menu=LoadMenu($restaurant_id);
    $last_modified=getLastModified($restaurant_id);

    if ($menu!="0")
    {
        $first_row="<table id='menu_table'><tr><td width=20px>#</td><td>Picture</td><td width=130px>Meal/drink name</td><td width=90px>Price</td><td>Description</td><td width=150px>Contents</td></tr>";

        $a=$menu;
        $menu=$first_row.$a."</table>";
    }
    else
    {
        $menu="";
        $last_modified="";
    }

    ob_start();
    require_once('profile_content.html');
    $content = ob_get_clean();

    include("n_template_logged.html");
    }
?>
