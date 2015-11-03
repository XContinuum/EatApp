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
      $error="<tr><td align='center'><span style='color:#e5473c;font-size:14px;'>Login or password incorrect!</span></td></tr>";
    }

    ob_start();
    require_once('login_box.html');
    $content = ob_get_clean();
    include("template.html");
  }
?>
