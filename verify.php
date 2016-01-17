<?php
	if (isset($_GET['email']) && isset($_GET['hash']))
	{
 	  $FA_Email=mysql_escape_string($_GET['email']);
    $FA_Hash=mysql_escape_string($_GET['hash']);

    require("requests/server_connection.php");
    require("requests/receive_information.php");

    $sql="SELECT FA_Hash FROM FA_RESTORANTS WHERE FA_Email='$FA_Email'";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_assoc($result);

    $logged=isUserLogged();
    $username=get_restaurant_username();

    //IF HASHES ARE EQUAL
    if ($row['FA_Hash']==$FA_Hash)
    {
    	$content="<br><br><div align='center'>Your account has been activated!<br>";

		$sql="UPDATE FA_RESTORANTS SET FA_Active='1' WHERE FA_Email='$FA_Email' AND FA_Hash='$FA_Hash'";
    	$result=mysqli_query($conn,$sql);

    	if ($logged==1)
    		$content.="<a href='/$username'>Go to profile</a></div>";
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

  mysqli_close($conn);

  include("template.html");
  }
  else
  {
		header("Location: ../index.php");
  }
?>
