<?php
    require("../requests/receive_information.php");

    if (isUserLogged()==1)
    {
        $username_logged=get_restaurant_username(); //get the username from the session
        $panel=setPanel();


        $image_src="../restaurant_data/Profile/".getInfo($username_logged,"FA_Pic");

        $address=getInfo($username_logged,'FA_Address');
        $restaurant_name=getInfo($username_logged,'FA_Restaurant_Name');
        $phone_number=getInfo($username_logged,'FA_Phone_Number');
        $website=getInfo($username_logged,'FA_Website');

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
