<?php
  ob_start();
  require_once('login_box.html');
  $content = ob_get_clean();
  include("template.html");
?>
