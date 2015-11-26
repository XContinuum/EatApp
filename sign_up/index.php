<?php
    session_start();

    if (isset($_SESSION['token']))
    {
        header("Location: ../index.php");
    }
    else
    {
    $page='sign_up_form.html';

    ob_start();
    require_once($page);
    $content = ob_get_clean();

    $head_param= "<script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.3.min.js'></script>";
    $head_param.= "<script src='sign_up_script.js'></script>";

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
