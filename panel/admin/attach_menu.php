<?php
require_once("../../requests/receive_information.php");
$db=new Db();

$xml_file=getXML("DB_Menu",createDir(getAdminUsername())); //Load XML on server
$chain_ID=$_POST["chain_ID"];
$menu_name=returnMenuName($chain_ID);

if (file_exists($xml_file)) 
{
    $xml=simplexml_load_file($xml_file);
   
    $intermediate=array();
   
    for ($i=0;$i<count($xml->product);$i++)
    {
        $data=array();
        $data[]=$menu_name;
        $data[]=$chain_ID;
        $data[]=$xml->product[$i]->name;
        $data[]=$xml->product[$i]->price;
        $data[]=$xml->product[$i]->description;
        $data[]=$xml->product[$i]->tags;        
        $data[]=(strlen($xml->product[$i]['section'])==0) ? "none" : $xml->product[$i]['section'];


        $data[]=(strlen($xml->product[$i]->picture)==0) ? "none" : $xml->product[$i]->picture;
        $data[]=$xml->product[$i]->price['currency'];

        $intermediate[]=createQuery($data);
    }
    $query=implode(",", $intermediate);
    $sql="INSERT INTO MENUS (Name,OWNER_ID,Product_Name,Price,Description,Contents,Section,Picture,Currency) VALUES $query";    

    if ($db->query($sql))
    {
        echo "Success";
    }
    
    rrmdir(getAdminUsername()); //delete directory with XML
} 
else 
{
    exit('Failed to open XML file.');
}

function getXML($field,$dir="")
{
    $file_tmp=$_FILES[$field]['tmp_name'];
    $file_name=$_FILES[$field]['name'];
    $file_size=$_FILES[$field]['size'];
    $ext=explode( ".", $file_name);
    $ext=$ext[1];

    if (is_uploaded_file($file_tmp)) 
    {
        move_uploaded_file($file_tmp,$dir.$file_name);
    } 

    return $dir."/".$file_name;
}


function returnMenuName($Chain_ID)
{
	$db=new Db();

	$names=$db->select("SELECT Distinct(Name) FROM MENUS WHERE OWNER_ID='$Chain_ID'");
	$chain_link=getInfoFromID($Chain_ID,"Link");

	$data=array();
	for ($i=0;$i<count($names);$i++)
	{
		$data[]=str_replace($chain_link."_menu_","",$names[$i]["Name"]);
	}

	$next=1;
	while(in_array($next,$data))
	{
		$next++;
	}

	return $chain_link."_menu_".$next;
}
?>