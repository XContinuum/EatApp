<?php
  session_start();
  if (!isset($_SESSION['token']))
  {
    header("Location: index.php");
  }
  else
  {
    require('requests/load_menu.php');
    require('requests/get_restaurant_id.php');
    $restaurant_id=get_restaurant_id();
    $menu=LoadMenu($restaurant_id);        
    ob_start();
    require_once('profile_content.html');
    $content = ob_get_clean();
    include("template_logged.html");
  }
?>
