<?php
require_once("../../requests/receive_information.php");

if (isAdminLogged()==1)
{
   //if admin is logged
   header("Location: ../../panel/admin/index.php");
}
else
   if (isOwnerLogged()==1)
    {
        //if the chain owner is logged
        require("load_restaurant_list.php");

        $head_param="<script src='js/time_picker.js'></script>";
        $head_param.="<script src='js/script.js'></script>";

        $panel=setPanel();
        $chainLink=getChainLink();

        $restaurant_edit=explode("##",file_get_contents("table_template.html"))[0];

        $content=file_get_contents("index_form.html");
        $content=str_replace("%list%",loadRestaurantList($restaurant_edit),$content); //Show list of restaurants if such exists
        $content=str_replace("%menu%",loadMenuList(),$content); //Show list of menus if such exists
        $content=str_replace("%schedule%",loadScheduleList(),$content); //

        $content=str_replace("%options%",loadMenuOptions('none'),$content); //global variable for the JavaScript to read
        $content=str_replace("%schedule_hidden_list%",loadScheduleOptions('none'),$content); //global variable for the JavaScript to read
        $content.="<input type='hidden' value='$chainLink' id='link_name' />";

        include_once("../../user_template.html");
    }
    else
        {
            header("Location: ../../index.php");
        }
?>
