<?php
    require("../../requests/receive_information.php");

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

            $panel=setPanel();
            $chainLink=getChainLink();
            $rest_num=0; //number of restaurants

            $content=file_get_contents("index_form.html");
            $content=str_replace("%list%",loadRestaurantList($rest_num),$content); //Show list of restaurants if such exists
            $content=str_replace("%menu%",loadMenuList(),$content); //Show list of menus if such exists
            $content=str_replace("%options%",loadMenuOptions('none'),$content); //global variable for the JavaScript to read
            $content.="<input type='hidden' value='$chainLink:$rest_num' id='link_name' />";

            $head_param="<script src='js/script.js'></script>";

            include("../../user_template.html");
        }
        else
            {
                header("Location: ../../index.php");
            }
?>
