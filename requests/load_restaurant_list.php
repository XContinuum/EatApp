<?php

require_once("receive_information.php");


/*
    Loads all restaurants owned by a food chain
    <edit/>
*/
function loadRestaurantList(&$number_of_rows,$template)
{
    require("server_connection.php");

    $owner_id=getChainId();
    $chainLink=getChainLink();

    $sql="SELECT R_Order,Link,Address,Postal_Code,Phone_Number,Menu_Name ";
    $sql.="FROM RESTAURANTS WHERE OWNER_ID='$owner_id' ORDER BY R_Order ASC";
    $result=mysqli_query($conn,$sql);

    $count=0;

    $final_result="";
    $search=array('%chain_link%', '%link_name%', '%phone_number%', '%address%','%postal_code%','%options%');
    $opt="";

    while($row=mysqli_fetch_array($result))
    {
        $count++;
        $opt=loadMenuOptions($row["Menu_Name"]);
        $replace=array($chainLink,$row["Link"],$row["Phone_Number"],$row["Address"],$row["Postal_Code"],$opt);

        $sequence=str_replace($search,$replace,$template);

        $final_result.=$sequence;
    }
    $number_of_rows=$count;

    mysqli_close($conn);

    return $final_result;
}

/*
    Loads all menus owned by a food chain
*/
function loadMenuList()
{
    require("server_connection.php");

    $owner_id=getChainId();
    $chainLink=getChainLink();

    $sql="SELECT DISTINCT(Name) AS menu_name FROM MENUS WHERE OWNER_ID='$owner_id'";
    $result=mysqli_query($conn,$sql);

    $final_result="";

    $template=file_get_contents("table_template.html");
    $template=explode("##",$template);
    $search=array('%menu_name%');

    while($row=mysqli_fetch_array($result))
    {
        $replace=array($row["menu_name"]);
        $sequence=str_replace($search,$replace,$template[1]);

        $final_result.=$sequence;
    }

    return $final_result;
}

/*
    Loads all menus as options
*/
function loadMenuOptions($selected_menu)
{
    require("server_connection.php");

    $owner_id=getChainId();
    $chainLink=getChainLink();

    $sql="SELECT DISTINCT(Name) AS menu_name FROM MENUS WHERE OWNER_ID='$owner_id'";
    $result=mysqli_query($conn,$sql);

    $sel="";
    $final_result="<option value='none' $sel>none</option>";
    $count=0;

    while($row=mysqli_fetch_array($result))
    {
        $sel="";
        if ($row["menu_name"]==$selected_menu)
        {
            $sel="selected";
        }

        $final_result.="<option value='".$row["menu_name"]."' $sel>".$row["menu_name"]."</option>";
    }

    return $final_result;
}



/*
    Get name of menu selected by restaurant
*/
function getMenuOwnerName($restaurant_link)
{
    require("server_connection.php");

    $sql="SELECT Menu_Name FROM RESTAURANTS WHERE Link='$restaurant_link'";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_assoc($result);

    if (mysql_num_rows($result)==0)
    {
        return -1;
    }
    else
    {
        return $row['Menu_Name'];
    }
}

?>
