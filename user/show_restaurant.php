<?php
    if (checkUsernameStatus($username)==1 && (checkIfValidated($username)==1 || isAdminLogged()==1))
    {
        //If the restaurant exists and is validated or the admin is logged
        $restaurant_name=getInfo($username,'FA_Restaurant_Name');

        $address=getInfo($username,'FA_Address');
        $phone_number=getInfo($username,'FA_Phone_Number');
        $website=getInfo($username,'FA_Website');

        $inside_menu=LoadMenu(getInfo($username,'ID'));
        $last_modified=getLastModified(getInfo($username,'ID'));

        $sort_price="<div style='width:100%;text-align:right;'>";
        $sort_price.="<input type='text' placeholder='search item by price' style='font-size:16px;padding-left:12px;width:200px;height:25px;'>";
        $sort_price.="</input></div>";

        //NO MENU+++
        if ($inside_menu=="0")
        {
            $inside_menu="The restaurant haven't uploaded the menu";
            $last_modified="";
            $sort_price="";
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
