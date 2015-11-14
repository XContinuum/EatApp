<?php
    session_start();
    if (isset($_SESSION['token']))
    {
        include("index_template_logged.html");
    }
    else
    {
        include("index_template.html");
    }
?>
