<?php
    require_once("../requests/receive_information.php");

    if (isOwnerLogged()==1)
    {
        $chain_link=getChainLink(); //get the username from the session
        $panel=setPanel();

        $image_src="../restaurant_data/Profile/".getInfo($chain_link,"Picture");

        $search=array("%restaurant_name%","%email%","%link_name%","%website%","%image_src%");
        $replace=array(getInfo($chain_link,"Restaurant_Name"),getInfo($chain_link,"Email"),$chain_link,getInfo($chain_link,"Website"),getPicLink(getChainLink()));

        $content=file_get_contents("settings_content.html");
        $content=str_replace($search, $replace, $content);

        include("../user_template.html");
    }
    else
        {
            header("Location: ../index.php");
        }

?>
