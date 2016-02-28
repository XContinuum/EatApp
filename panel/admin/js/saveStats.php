<?php
require_once("../../../requests/receive_information.php");
require_once("../lines.php");

if (isAdminLogged()==1)
{
	$save=countLines("../../..");

	$db=new Db();

	$data=array($save['files_count'],$save['lines_count'],$save['js_lines'],$save['js_files'],$save['php_lines'],$save['php_files'],$save['html_lines'],$save['html_files']);
	$tmp="('".implode("','",$data)."')";

	$sql="INSERT INTO PROJECT_STATS (files_total,lines_total,js_lines,js_files,php_lines,php_files,html_lines,html_files) VALUES $tmp";

	$db->query($sql);
}
?>