<?php
    require("receive_information.php");

    $CHAIN_link=getChainLink();
    $CHAIN_email=getInfo($CHAIN_link,'Email');
    $CHAIN_hash=getInfo($CHAIN_link,'Hash');

    sendEmail($CHAIN_hash,$CHAIN_link,$CHAIN_hash);
?>
