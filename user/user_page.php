<?php
    $head_param ="<script src='user/show_partitions.js'></script>"; // MOD 2017

    $restaurant_id=get_restaurant_id();
    $menu=LoadMenu($restaurant_id);
    $last_modified=getLastModified($restaurant_id);

    if ($menu!="0")
    {
        //if menu is set
        $first_row="<table id='menu_table'>";
        $first_row.="<tr class='under'>";
        $first_row.="<td>Picture</td>";
        $first_row.="<td width=200px>Meal</td>";
        $first_row.="<td width=90px>Price (CAD)</td>";
        $first_row.="<td>Description</td>";
        $first_row.="<td width=150px>Contents</td></tr>";

        $a=$menu;
        $menu=$first_row.$a."</table>";
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
