<?php
    $content=file_get_contents("user_content.html");
    $restaurant_edit=file_get_contents("res_struct.html");

    $rest_count=0;
    $list=loadRestaurantList($rest_count,$restaurant_edit);

    $search=array('%image_src%','%restaurant_name%','%website%','%restaurant_list%');
    $data=array('../images/none.png',getInfo($CHAIN_Logged,'Restaurant_Name'),'',$list);
    $content=str_replace($search,$data,$content);
?>
