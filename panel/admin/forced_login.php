<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
require_once("../../requests/receive_information.php");

if (isAdminLogged())
{
	$db=new Db();
	$chain_id=$_GET["id"];

	//+++
	if(!isset($_SESSION))
        session_start();

	if (isset($_SESSION['admin_token']))
	{
		$sql="UPDATE ADMIN_PANEL SET Token='0' WHERE Token='" . $_SESSION['admin_token'] . "'";
		$db->query($sql);

		session_unset(); //remove all session variables
	}
	//---

	$token=getToken(getInfoFromID($chain_id,"Email"));
    saveSession($token,3600*24*365,"chain");

    $db->query("UPDATE CHAIN_OWNER SET Token='$token' WHERE ID='$chain_id'");
  	//++++
}

header("Location: ../../index.php");


function saveSession($token,$time,$type)
{
    if(!isset($_SESSION))
        session_start();

    if($type=="chain")
    {
        $_SESSION['chain_owner_token']=$token;
        $_SESSION['time']=$time;
    }
    else if ($type=="admin")
    {
        $_SESSION['admin_token']=$token;
        $_SESSION['time']=$time;
    }
}
?>
