<?php
    require("receive_information.php");

    $res_username=get_restaurant_username();
    $res_email=getInfo($res_username,'FA_Email');
    $res_hash=getInfo($res_username,'FA_Hash');

    sendEmail($res_email,$res_username,$res_hash);
?>
