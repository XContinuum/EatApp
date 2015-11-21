<?php
    session_start();
    if (!isset($_SESSION['token']))
    {
      header("Location: index.php");
    }
    else
    {
      //Load menu+++
      require('../requests/load_menu.php');
      //require('../requests/get_restaurant_id.php'); MOD 2017
      require('../requests/receive_information.php'); //sMOD 2017

      $restaurant_id=get_restaurant_id();
      $menu=FillMenuBlanks($restaurant_id);
      //LOad menu---

      ob_start();
      require_once('setup_menu_form.html');
      $content=ob_get_clean();

      $head_param ="<script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.3.min.js'></script>";
      $head_param.="<link href='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css' rel='stylesheet'/>";
      $head_param.="<script src='//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js'></script>";
      $head_param.="<script src='jquery_setup_menu.js'></script>";

      //include("../n_template_logged.html"); MOD 2017
      include("../template_logged.html"); //MOD 2017
    }

    /*
    MOD 2017
    function setLink($string)
    {
        $path="http://localhost:8888/";
        echo "'".$path.$string."'";
    }*/
?>
