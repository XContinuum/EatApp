<?php

require("../../requests/receive_information.php");
include("lines.php");
  

$template="<table align='center' style='border-collapse:collapse;'>%info%</table>";

$file=file_get_contents("str_html.html");
$table=str_replace("%info%",DirLineCounter('../..'),$template);
$content=str_replace("%content%",$table,$file);

include("../../user_template.html");
?>