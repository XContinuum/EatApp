<?php
require_once("../../../requests/receive_information.php");

$path=getAdminUsername()."/file.xml";
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$_GET['name'].".xml");
readfile($path);
rrmdir(getAdminUsername());
?>