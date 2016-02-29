<?php
require_once("receive_information.php");
/*
    Loads all restaurants owned by a food chain
    <edit/>
*/
function loadRestaurantList($template)
{
    $db=new Db();

    $owner_id=getChainId();
    $chainLink=getChainLink();

    $sql="SELECT R_Order,Link,Address,Postal_Code,Phone_Number,Menu_Name,Country,State_Province,City,Schedule_Name ";
    $sql.="FROM RESTAURANTS WHERE OWNER_ID='$owner_id' ORDER BY R_Order ASC";
    $result=$db->query($sql);

    $output="";
    $search=array('%chain_link%', '%link_name%', '%phone_number%', '%address%','%postal_code%','%country%','%state_province%','%city%');

    while ($row = $result -> fetch_assoc())
    {
        $replace=array($chainLink,$row["Link"],$row["Phone_Number"],$row["Address"],$row["Postal_Code"],$row["Country"],$row["State_Province"],$row["City"]);

        $sequence=str_replace($search,$replace,$template);

        $output.=$sequence;
    }

    return $output;
}

?>
