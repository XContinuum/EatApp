<?php
    if (checkUsernameStatus($username)==1)
    {
        $head_param ="<script src='/user/show_partitions.js'></script>";

        $restaurant_name=getInfo($username,'FA_Restaurant_Name');
        $address=getInfo($username,'FA_Address');
        //$phone_number
        //$opening_time


        $last_modified=getLastModified(getInfo($username,'ID'));
        $menu="<table style='width:100%;'>".LoadMenu(getInfo($username,'ID'))."</table>";

        ob_start();
        require_once("restaurant_view.html");
        $content=ob_get_clean();
    }
    else
    {
        //username not found
        $content="<br><br><br>";
        $content.="<div align='center'>Restaurant <b>".$username."</b> not found</div>";
    }
?>
