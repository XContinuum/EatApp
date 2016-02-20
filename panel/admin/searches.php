<?php
require_once("../../requests/receive_information.php");
require_once("../../requests/access_db.php");


if (isAdminLogged()==1) //Admin logged
{
	$panel=setPanel();

	$content=file_get_contents("main_content.html");
	$content=str_replace("%content%",topSearches().loadSearches(),$content);
   
	include("../../user_template.html");
}
else
{
	//Admin not logged
    header("Location: ../../index.php");
}


function topSearches()
{
  $db=new Db();

  $sql="SELECT input, COUNT(*) AS occurrences FROM SEARCHES GROUP BY input ORDER BY occurrences DESC LIMIT 5";
  $result=$db->query($sql);
  $output="<tr><td colspan='2' align='center'>Top 5 searches</td></tr>";
  $output.="<tr style='background-color:#f9f8f8;'><td>Search</td><td width='100px' align='center'>Occurrences</td></tr>";

  while ($row = $result -> fetch_assoc())  
  {
    $output.="<tr><td>".$row["input"]."</td><td align='center'>".$row["occurrences"]."</td></tr>";
  }
  $output="<table width='100%' id='styled_table'>".$output."</table>";

  return $output;
}

function loadSearches()
{
	$db=new Db();

	$sql="SELECT * FROM SEARCHES ORDER BY Date_search DESC LIMIT 100";
	$result=$db->query($sql);
   
  $compile="";
  $search=array("%input%","%ip_address%","%results%","%date%");

  $source=file_get_contents("searches_structure.html");
  $templates=explode("##", $source);

  while ($row = $result -> fetch_assoc())  
  {
    $input="<a target='_blank' href='../../search/index.php?q=".str_replace(" ","%20",$row["Input"])."'>".$row["Input"]."</a>";
    $replace=array($input,str_replace("::1","<i>localhost</i>",$row["IP_Address"]),$row["Results_Found"],$row["Date_search"]);
    $compile.=str_replace($search,$replace,$templates[1]);
  }
  
  $compile=str_replace("%list%",$compile,$templates[0]);

  return $compile;
}

?>