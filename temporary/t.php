<?php 
	//test receive_information
	require_once("../requests/receive_information.php");
    
	echo getChainLink()."<br/>";
    echo getChainId()."<br/>";
    echo getMenuOwnerID("mcdonalds_menu_2")."<br/>";
    echo getInfo("mcdonalds","Email")."<br/>";
    echo getInfoFromID("5","Email")."<br/>";
    echo checkChainStatus("mcdonalds")."<br/>";
    echo checkIfValidated("mcdonalds")."<br/>";
    echo checkIfEmailValidated("mcdonalds")."<br/>";
    echo isOwnerLogged()."<br/>";
    echo isAdminLogged()."<br/>";
    echo getAdminUsername()."<br/>";
    echo getPicLink("mcdonalds")."<br/>";
?>