<?php
require_once("../../requests/receive_information.php");

if (isAdminLogged()==1) //Admin logged
{
	require("lines.php");

	$panel=setPanel();

	$template="<table align='center' id='lines_table'>%info%</table>";
	
	
	$file=file_get_contents("main_content.html");
	$table=str_replace("%info%",DirLineCounter('../..'),$template);
	$content=str_replace("%content%",$table,$file);

	//Save stats+++
	$save=countLines("../..");

	$db=new Db();

	$sql="INSERT INTO PROJECT_STATS (files_total,lines_total,js_lines,js_files,php_lines,php_files,html_lines,html_files)";
	$sql.=" VALUES ('".$save['files_count']."','".$save['lines_count']."','".$save['js_lines']."','".$save['js_files']."','".$save['php_lines']."','".$save['php_files']."','".$save['html_lines']."','".$save['html_files']."')";

	$db->query($sql);
	//Save stats---

	include("../../user_template.html");
}
else
{
	//Admin not logged
    header("Location: ../../index.php");
}
?>