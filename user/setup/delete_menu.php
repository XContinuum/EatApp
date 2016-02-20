<?php
	require("../../requests/receive_information.php");
  	
	$delete_name=$_POST["menu_name"];
	$owner_id=getChainId();
	$owner_name=getChainLink();
	$db=new Db();

	$sql="DELETE FROM MENUS WHERE OWNER_ID=$owner_id AND Name='$delete_name'";

	if ($db->query($sql))
	{
		$path="../../restaurant_data/Pictures/$owner_name/$delete_name";
		rrmdir($path);
		echo "success";
	}
	else
	{
		echo "fail";
	}


/*
	Deletes a non-empty directory
*/
function rrmdir($dir) 
{ 
	if (is_dir($dir)) 
	{ 
		$objects = scandir($dir); 
     		
		foreach ($objects as $object) 
		{ 
			if ($object != "." && $object != "..") 
			{ 
				if (filetype($dir."/".$object) == "dir") 
         			rrmdir($dir."/".$object); 
         		else 
         			unlink($dir."/".$object); 
       		} 
     	}

    reset($objects); 
    rmdir($dir); 
   } 
} 

?>