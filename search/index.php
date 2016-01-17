<?php
    require("../requests/receive_information.php");
    $panel=setPanel();

    $search_query=$_GET["q"];
    $head_param="<script src='hintBox.js'></script>";
    $head_param.="<script src='http://maps.google.com/maps/api/js?sensor=true'></script>";
    $head_param.="<script src='js/ajaxSearch.js'></script>";

    ob_start();
    require_once("search_form.html");
    $content=ob_get_clean();

    include("../user_template.html");
?>
