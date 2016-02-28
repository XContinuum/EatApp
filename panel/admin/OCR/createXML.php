<?php
require_once("../../../requests/receive_information.php");

$xml=new SimpleXMLElement('<xml/>');

$prices=$_REQUEST['arrPrices'];
$products=$_REQUEST['arrProducts'];

$name="file";
$path=createDir(getAdminUsername());

for ($i=0;$i<count($prices);$i++)
{
	if (strlen($prices[$i])>0 && strlen($products[$i])>0)
	{
		$product=$xml->addChild('product');
		$product->addChild('name', $products[$i]);

 		$price=$product->addChild('price', $prices[$i]);
 		$price->addAttribute('currency', 'CAD');

 		$product->addChild('section', 'none');
 	}
}

//Output headers
$dom=new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;

$dom->loadXML($xml->asXML());
$dom->save($path.$name.".xml");
?>