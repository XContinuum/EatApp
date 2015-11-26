<?php
    session_start();

    if (isset($_SESSION['token']))
    {
        header("Location: ../index.php");
    }
    else
    {
        $error="";

        if ($_GET["error"]=="1")
        {
            $error="<div id='error_box' style='top:0px;'>Login or password incorrect!</div>";
        }

        ob_start();
        require_once('login_box.html');
        $content = ob_get_clean();

        include("../template.html");
    }


    function setLink($string)
    {
        $ini_array=parse_ini_file("../requests/settings.ini", true); // MOD 2017

        $path=$ini_array['server']['path']; // MOD 2017
        //$path="http://localhost:8888/";
        echo "'".$path.$string."'";
    }
?>
