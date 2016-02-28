<?php
require_once("../../requests/receive_information.php");
$menu_name=$_GET["name"];

/*No one is logged*/
if (isOwnerLogged()==0 && isAdminLogged()==0)
{
    header("Location: ../../index.php");
}
else
    /*If the current Chain owner does not own a menu*/
    if (getMenuOwnerID($menu_name)!=getChainId() && getMenuOwnerID($menu_name)>-1)
    {
        header("Location: ../../index.php");
    }
    /*If the current owner owns the menu or the menu does not exist yet*/
    else
    {
        require("load_menu.php");

        $panel=setPanel();

        $head_param="<script src='js/imagecrop.js'></script>";
        $head_param.="<script src='js/multi_select.js'></script>";
        $head_param.="<script src='js/setup_menu.js'></script>";

        $content=file_get_contents("setup_menu_form.html");
        $content=str_replace("%Menu%",load_editMenu($menu_name),$content);
        $content=str_replace("%name%",$menu_name,$content);

        include("../../user_template.html");
    }

?>
