<?php
    $content="<br><div id='logo'>LOGO</div><br><div id='desc'>Search a restaurant near you:<input type='text' value=''></input></div>";

    session_start();
    if (isset($_SESSION['token']))
    {
        include("template_logged.html");
    }
    else
    {
        include("template.html");
    }
?>
