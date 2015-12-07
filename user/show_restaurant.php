<?php
    if (checkUsernameStatus($username)==1 && (checkIfValidated($username)==1 || isAdminLogged()==1))
    {
        //If the user exists and it is validated or the admin is logged
        $head_param ="<script src='/user/show_partitions.js'></script>";

        $restaurant_name=getInfo($username,'FA_Restaurant_Name');
        $address=getInfo($username,'FA_Address');
        //$phone_number
        //$opening_time


        $inside_menu=LoadMenu(getInfo($username,'ID'));
        $last_modified=getLastModified(getInfo($username,'ID'));


        //NO MENU+++
        if ($inside_menu=="0")
        {
            $inside_menu="The restaurant haven't uploaded the menu";
            $last_modified="";
        }
        //NO MENU---

        $menu="<table style='width:100%;'>".$inside_menu."</table>";

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
