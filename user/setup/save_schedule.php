<?php
require_once("../../requests/receive_information.php");

$db=new Db();
//Data from form
$DB_Schedule[]=$_POST["monday_start"]; 
$DB_Schedule[]=$_POST["monday_end"]; 
$DB_Schedule[]=$_POST["tuesday_start"]; 
$DB_Schedule[]=$_POST["tuesday_end"]; 
$DB_Schedule[]=$_POST["wednesday_start"]; 
$DB_Schedule[]=$_POST["wednesday_end"]; 
$DB_Schedule[]=$_POST["thursday_start"]; 
$DB_Schedule[]=$_POST["thursday_end"]; 
$DB_Schedule[]=$_POST["friday_start"]; 
$DB_Schedule[]=$_POST["friday_end"]; 
$DB_Schedule[]=$_POST["saturday_start"]; 
$DB_Schedule[]=$_POST["saturday_end"]; 
$DB_Schedule[]=$_POST["sunday_start"]; 
$DB_Schedule[]=$_POST["sunday_end"]; 
 
$owner_id=getChainId();
$chainLink=getChainLink();

$bAdd=true;

//Compiles data into a string
for ($i=0;$i<count($DB_Schedule[0]);$i++)
{
	$data=array($chainLink."_sch_".($i+1), $owner_id,$DB_Schedule[0][$i],$DB_Schedule[1][$i],$DB_Schedule[2][$i],$DB_Schedule[3][$i],$DB_Schedule[4][$i],$DB_Schedule[5][$i],$DB_Schedule[6][$i],$DB_Schedule[7][$i],$DB_Schedule[8][$i],$DB_Schedule[9][$i],$DB_Schedule[10][$i],$DB_Schedule[11][$i],$DB_Schedule[12][$i],$DB_Schedule[13][$i]);

	$intermediate_q[]=createQuery($data);
}
$query=implode(",", $intermediate_q);
     
$sql="INSERT INTO SCHEDULE (Name,OWNER_ID,Monday_Start,Monday_End,Tuesday_Start,Tuesday_End,Wednesday_Start,Wednesday_End,Thursday_Start,Thursday_End,Friday_Start,Friday_End,Saturday_Start,Saturday_End,Sunday_Start,Sunday_End) ";
$sql.="VALUES $query ON DUPLICATE KEY UPDATE ";
$sql.="OWNER_ID=VALUES(OWNER_ID),Monday_Start=VALUES(Monday_Start),Monday_End=VALUES(Monday_End),Tuesday_Start=VALUES(Tuesday_Start),Tuesday_End=VALUES(Tuesday_End),";
$sql.="Wednesday_Start=VALUES(Wednesday_Start),Wednesday_End=VALUES(Wednesday_End),Thursday_Start=VALUES(Thursday_Start),";
$sql.="Thursday_End=VALUES(Thursday_End),Friday_Start=VALUES(Friday_Start),Friday_End=VALUES(Friday_End),";
$sql.="Saturday_Start=VALUES(Saturday_Start),Saturday_End=VALUES(Saturday_End),Sunday_Start=VALUES(Sunday_Start),Sunday_End=VALUES(Sunday_End)";
    
//DELETE+++
$rowNum=$db->fetch("SELECT COUNT(*) as total FROM SCHEDULE WHERE OWNER_ID=$owner_id","total");
   
if (count($DB_Schedule[0])<$rowNum)
{
	$delete=$rowNum-count($DB_Schedule[0]);
	$tmp="DELETE FROM SCHEDULE WHERE OWNER_ID=$owner_id ORDER BY ID DESC LIMIT $delete";
	$db->query($tmp);
}
//DELETE---
    
if ($db->query($sql))
{
	echo "success";
}

?>