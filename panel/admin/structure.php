<?php
require_once("../../requests/receive_information.php");

if (isAdminLogged()==1) //Admin logged
{
	require("lines.php");

	$panel=setPanel();

	$template="<table align='center' id='lines_table'>%info%</table>";
	
	$file=file_get_contents("main_content.html");
	$table=str_replace("%info%",DirLineCounter('../..'),$template);
	$content=str_replace("%content%","<input type='button' id='saveStats' value='save' class='btn-default'/>".$table,$file);

	$head_param="<script src='js/saveStats.js'></script>";

	include("../../user_template.html");
}
else
{
	//Admin not logged
    header("Location: ../../index.php");
}

?>