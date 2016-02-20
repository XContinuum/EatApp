<?php
require_once("../../requests/access_db.php");
$db=new Db();

$name=$_GET["menu_name"];
    
//Populate XML document
$xml = new SimpleXMLElement('<xml/>');
        
$sql="SELECT Product_Name,Price,Description,Contents,Section,Picture,Currency ";
$sql.="FROM MENUS WHERE Name='$name'";
$result=$db->query($sql);
        
$menu="";
$count=0;
       
$old_section_name="none";

while ($row = $result -> fetch_assoc())
{
   $product=$xml->addChild('product');
   $product->addAttribute('section',$row['Section']);

   $product->addChild('picture', $row['Picture']);
   $product->addChild('name', $row['Product_Name']);
   
   $price=$product->addChild('price', $row['Price']);
   $price->addAttribute('currency', $row['Currency']);

   $product->addChild('description', $row['Description']);
   $product->addChild('tags', $row['Contents']);
}

//Output headers
header('Content-type: "text/xml"; charset="utf8"');
header("Content-disposition: attachment; filename=".$name);

$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;

$dom->loadXML($xml->asXML());
echo $dom->saveXML();
?>