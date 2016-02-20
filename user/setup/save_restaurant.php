<?php
    require_once("../../requests/receive_information.php");

    $db=new Db();
    //Data from form
    $DB_Restaurant_Link=$_POST['link_name'];
    $DB_Address=$_POST['address'];
    $DB_Postal_Code=$_POST['postal_code'];
    $DB_Phone_Number=$_POST['phone_number'];
    $DB_Menu_Name=$_POST['menu_name'];
    $DB_Country=$_POST['country'];
    $DB_State_Province=$_POST['state_province'];
    $DB_City=$_POST['city'];
    $DB_Schedule=$_POST['schedule'];

    $owner_id=getChainId();
    $bAdd=true;

    $intermediate_q=array(); //intermediate array

    //Compiles data into a string
    for ($i=0;$i<count($DB_Restaurant_Link);$i++)
    {
        $link=strtolower($DB_Restaurant_Link[$i]);
        $coord=getCoordinates($DB_Address[$i]);
        $data=array($i+1,$owner_id,$link,$DB_Address[$i],$DB_Postal_Code[$i],$DB_Phone_Number[$i],$DB_Menu_Name[$i],$coord[0],$coord[1],$DB_Country[$i],$DB_State_Province[$i],$DB_City[$i],$DB_Schedule[$i]);

        $intermediate_q[]=createQuery($data);

        if ($link=="" || !isset($link))
        {
            $bAdd=false;
        }
    }

if ($bAdd) //do not add if link is not set
{
    $query=implode(",", $intermediate_q);

    $sql="INSERT INTO RESTAURANTS (R_Order,OWNER_ID,Link,Address,Postal_Code,Phone_Number,Menu_Name,Longitude,Latitude,Country,State_Province,City,Schedule_Name) ";
    $sql.="VALUES $query ON DUPLICATE KEY UPDATE ";
    $sql.="Link=VALUES(Link),Address=VALUES(Address),Postal_Code=VALUES(Postal_Code),";
    $sql.="Phone_Number=VALUES(Phone_Number),Menu_Name=VALUES(Menu_Name),Longitude=VALUES(Longitude),";
    $sql.="Latitude=VALUES(Latitude),Country=VALUES(Country),State_Province=VALUES(State_Province),City=VALUES(City),Schedule_Name=VALUES(Schedule_Name);";

    //DELETE+++
    $rowNum=$db->fetch("SELECT COUNT(*) as total FROM RESTAURANTS WHERE OWNER_ID=$owner_id","total");

    if (count($DB_Restaurant_Link)<$rowNum)
    {
        $less_rows=count($DB_Restaurant_Link);
        $tmp="DELETE FROM RESTAURANTS WHERE OWNER_ID=$owner_id and R_Order>$less_rows;";
        $db->query($tmp);
    }
    //DELETE---

    if ($db->query($sql))
    {
        echo "success";
    }
}
?>
