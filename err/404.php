<?php
	ob_start();
    require_once('404_format.html');
    $content = ob_get_clean();

    include ("template.html");

    function setLink($string)
    {
				$ini_array=parse_ini_file("../requests/settings.ini", true); // MOD 2017

				$path=$ini_array['server']['path']; // MOD 2017
        // $path="http://localhost:8888/"; //MOD 2017
        echo "'".$path.$string."'";
    }
?>
