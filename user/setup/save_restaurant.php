<?php
    require("../../requests/server_connection.php");
    require("../../requests/receive_information.php");

    //Data from form
    $DB_Restaurant_Link=$_POST['link_name'];
    $DB_Address=$_POST['address'];
    $DB_Postal_Code=$_POST['postal_code'];
    $DB_Phone_Number=$_POST['phone_number'];
    $DB_Menu_Name=$_POST['menu_name'];

    $query="";
    $owner_id=getChainId();
    $bAdd=true;

    $intermediate_q=array(); //intermediate array

    //Compiles data into a string
    for ($i=0;$i<count($DB_Restaurant_Link);$i++)
    {
        $link=strtolower($DB_Restaurant_Link[$i]);
        $coord=getCoordinates($DB_Address[$i]);
        $data=array($i+1,$owner_id,$link,$DB_Address[$i],$DB_Postal_Code[$i],$DB_Phone_Number[$i],$DB_Menu_Name[$i],$coord[0],$coord[1]);

        array_push($intermediate_q, createQuery($data));

        if ($link=="" || !isset($link))
        {
             $bAdd=false;
        }
    }
    $query=implode(",", $intermediate_q);

    $sql="INSERT INTO RESTAURANTS (R_Order,OWNER_ID,Link,Address,Postal_Code,Phone_Number,Menu_Name,Longitude,Latitude) ";
    $sql.="VALUES $query ON DUPLICATE KEY UPDATE ";
    $sql.="Link=VALUES(Link),Address=VALUES(Address),Postal_Code=VALUES(Postal_Code),";
    $sql.="Phone_Number=VALUES(Phone_Number),Menu_Name=VALUES(Menu_Name),Longitude=VALUES(Longitude),Latitude=VALUES(Latitude);";

    //DELETE+++
    $result=mysqli_query($conn,"SELECT COUNT(*) as total FROM RESTAURANTS WHERE OWNER_ID=$owner_id");
    $rowNum=mysqli_fetch_assoc($result);

    if (count($DB_Restaurant_Link)<$rowNum['total'])
    {
        $less_rows=count($DB_Restaurant_Link);
        $tmp="DELETE FROM RESTAURANTS WHERE OWNER_ID=$owner_id and R_Order>$less_rows;";
        mysqli_query($conn, $tmp);
    }
    //DELETE---

    if ($bAdd) //do not add if link is not set
    {
        if (mysqli_query($conn, $sql))
        {
            echo "success";
        }
    }
    mysqli_close($conn);


    function getCoordinates($address)
    {
        $prepAddr=str_replace(' ','+',$address);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output=json_decode($geocode);

        $coordinates=array();
        $coordinates[]=$output->results[0]->geometry->location->lng;
        $coordinates[]=$output->results[0]->geometry->location->lat;

        return $coordinates;
    }
?>
