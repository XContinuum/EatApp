<?php
  session_start();
  if (isset($_SESSION['token']))
  {
    header("Location: index.php");
  }
  else
  {
    ob_start();
    require_once('sign_up_form.html');
    $content = ob_get_clean();
    $head_param .= "<script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.3.min.js'></script>";
    $head_param.= "<script src='jquery_functions.js'></script>";
    include("template.html");
  }
?>
