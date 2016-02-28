<?php
require_once("../../../requests/receive_information.php");

if (isAdminLogged()==1) //Admin logged
{
	$Admin="<a href='../index.php'>".getAdminUsername()."</a>";
	include("OCR_html.html");
}
else
{
    //Admin not logged
    header("Location: ../../index.php");
}
?>
