<?php
if (isset($_GET['email']) && isset($_GET['hash']))
{
  require("requests/receive_information.php");

  $Email=mysql_escape_string($_GET['email']);
  $Hash=mysql_escape_string($_GET['hash']);

  $db=new Db();

  $sql="SELECT Hash FROM CHAIN_OWNER WHERE Email='$Email'";
  $db_hash=$db->fetch($sql,"Hash");

  $logged=isOwnerLogged();
  $link_name=getChainLink();

  //IF HASHES ARE EQUAL
  if ($db_hash==$Hash)
  {
    $content="<br><br><div align='center'>Your account has been activated!<br>";

    $sql="UPDATE CHAIN_OWNER SET Active='1' WHERE Email='$Email' AND Hash='$Hash'";
    $result=$db->query($sql);

    if ($logged==1)
    		$content.="<a href='/$getChainLink'>Go to profile</a></div>";
    else
    		$content.="<a href='login/index.php'>Log in</a></div>";
	}
	else
	{
		//IF HASHES ARE NOT EQUAL
		$content="<br><br><div align='center'>Activation code has expired.";

		if ($logged==1)
			$content.=" <a href='requests/resend_email.php'>Resend email verification.</a></div>";
		else
			$content.=" <a href='login/index.php?resend=true'>Login and resend email verification.</a></div>";
	}


    include("user_template.html");
}
else
   	{
		header("Location: ../index.php");
   	}
?>
