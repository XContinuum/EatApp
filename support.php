<?php
  session_start();
  ob_start();
  require_once('support_content.html');
  $content = ob_get_clean();

  if (isset($_SESSION['token']))
  {
    include("template_logged.html");
  }
  else
  {
    include("template.html");
  }
?>
