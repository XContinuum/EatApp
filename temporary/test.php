<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require("../requests/hash_algorithm.php");
require("../requests/access_db.php");

$db=new Db();
$email="lmaolmao@gmail.com";
$str="lmaolmao";

$chain_data=$db->select("SELECT Email,Password FROM CHAIN_OWNER WHERE Email='$email'")[0];
    
echo $chain_data["Email"]."<br/>";
echo $chain_data["Password"]."<br/>";
echo PassHash::check_password($chain_data["Password"], "lmaolmao")?"<br/>true":"<br/>false";
//echo PassHash::check_password(PassHash::hash($str), $str)?"<br/>true":"<br/>false";


?>
