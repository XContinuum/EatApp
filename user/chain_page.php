<?php
    $content=file_get_contents("chain_content.html");
    $restaurant_edit=file_get_contents("res_struct.html");

    $rest_count=0;
    $list=loadRestaurantList($restaurant_edit);

    $search=array('%image_src%','%restaurant_name%','%website%','%restaurant_list%');
    $data=array(getPicLink(getChainLink()),getInfo($CHAIN_Logged,'Restaurant_Name'),'',$list);
    $content=str_replace($search,$data,$content);
?>
