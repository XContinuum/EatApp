<?php
require_once("requests/receive_information.php");
require("panel/admin/lines.php");

//Save stats+++
	$save=countLines("proj");
	print_r($save);
//Save stats---
?>