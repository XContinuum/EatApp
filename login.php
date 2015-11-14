<?php
    session_start();
    if (isset($_SESSION['token']))
    {
      header("Location: index.php");
    }
    else
    {
        $error="";

        if ($_GET["error"]=="1")
        {
            $error="<div style='position:fixed;top:0;left:0;background-color:#fa6d65;color:white;width:100%;'>Login or password incorrect!</div>";
        }
        
        ob_start();
        require_once('login_box.html');
        $content = ob_get_clean();

        include("n_template.html");
    }
?>
