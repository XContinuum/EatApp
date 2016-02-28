<?php
    require("../requests/receive_information.php");
    $panel=setPanel();

    $head_param="<script src='hintBox.js'></script>";
    $head_param.="<script src='js/jQueryRotate.js'></script>";
    $head_param.="<script src='http://maps.google.com/maps/api/js?sensor=true'></script>";
    $head_param.="<script src='js/ajaxSearch.js'></script>";

    $content=file_get_contents("search_form.html");
    $content=str_replace("%search_query%", $_GET["q"], $content);

    include("../user_template.html");
 ?>
