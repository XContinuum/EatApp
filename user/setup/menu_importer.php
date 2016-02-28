<?php
require_once("../../requests/receive_information.php");
$db=new Db();

//Make directory+++
$link_name=getChainLink();
$path="../../restaurant_data/XML/$link_name";

if (!is_dir($path)) 
{
    mkdir($path);
}

$path=$path."/";
//Make directiry---

$xml_file=getXML("import_menu",$path); //Load XML on server

if (file_exists($path."/".$xml_file)) 
{
    $xml=simplexml_load_file($path."/".$xml_file);
   
    $intermediate=array();
    $Owner_ID=getChainId();
    $name=$_POST["next_menu_name"];

    for ($i=0;$i<count($xml->product);$i++)
    {
        $data=array();
        $data[]=$name;
        $data[]=$Owner_ID;
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
    
    rrmdir("../../restaurant_data/XML/$link_name"); //delete directory with XML
} 
else 
{
    exit('Failed to open XML file.');
}


function getXML($field,$dir='')
{
    $file_tmp=$_FILES[$field]['tmp_name'];
    $file_name=$_FILES[$field]['name'];
    $file_size=$_FILES[$field]['size'];
    $ext=explode( ".", $file_name);
    $ext=$ext[1];

    if (is_uploaded_file($file_tmp)) 
    {
        move_uploaded_file($file_tmp,$dir.$file_name);
        //echo "file: $file_name size: $file_size done !";
    } 

    return $file_name;
}
?>