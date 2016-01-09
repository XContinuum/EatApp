<?php
    require("../requests/receive_information.php");

    $username_logged=get_restaurant_username(); //get the username from the session

    if (isUserLogged()==1)
    {
        $image_src="../restaurant_data/Profile/".getInfo($username_logged,"FA_Pic");

        $panel="<div id='user_top_panel'>";
        $panel.="<div id='username_bar'><a href='/".$username_logged."'>".$username_logged."</a></div>";
        $panel.="<div id='profile_picture'><img src='$image_src' id='image_circle'/></div>";
        $panel.="</div>";


        $address=getInfo($username_logged,'FA_Address');
        $restaurant_name=getInfo($username_logged,'FA_Restaurant_Name');
        $phone_number=getInfo($username_logged,'FA_Phone_Number');
        $website=getInfo($username_logged,'FA_Website');


        $head_param="<script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.3.min.js'></script>";
        $head_param.="<script src='/user/show_partitions.js'></script>";

        ob_start();
        require_once("settings_content.html");
        $content=ob_get_clean();

        include("../user_template.html");
    }
    else
        {
            header("Location: ../index.php");
        }

?>
