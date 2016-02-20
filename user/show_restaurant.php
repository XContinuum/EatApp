<?php
    if (checkChainStatus($CHAIN_Link)==1 && (checkIfValidated($CHAIN_Link)==1 || isAdminLogged()==1))
    {
        //If the restaurant exists and is validated or the admin is logged
        $restaurant_name=getInfo($CHAIN_Link,'Restaurant_Name');

        $search=array("%image_src%","%restaurant_name%","%restaurant_list%");
        $replace=array("../images/none.png",$restaurant_name,"");

        $content=file_get_contents("restaurant_view.html");
        $content=str_replace($search,$replace,$content);
    }
    else
    {
        $content=file_get_contents("not_found.html");
        $content=str_replace("%link%",$CHAIN_Link,$content);
    }
?>
