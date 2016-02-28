<?php
require_once("../../requests/receive_information.php");
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
    $search=array('%chain_link%', '%link_name%', '%phone_number%', '%address%','%postal_code%','%options%','%country%','%state_province%','%city%','%schedule%');

    while ($row = $result -> fetch_assoc())
    {
        $opt=loadMenuOptions($row["Menu_Name"]);
        $sch=loadScheduleOptions($row["Schedule_Name"]);
        $replace=array($chainLink,$row["Link"],$row["Phone_Number"],$row["Address"],$row["Postal_Code"],$opt,$row["Country"],$row["State_Province"],$row["City"],$sch);

        $sequence=str_replace($search,$replace,$template);

        $output.=$sequence;
    }

    return $output;
}

/*
    Loads all menus (names) owned by a food chain
*/
function loadMenuList()
{
    $db=new Db();

    $owner_id=getChainId();

    $sql="SELECT Menu_Name,PIC_NUM,PROD_NUM,Mean FROM (SELECT Distinct(Name) as T1, COUNT(Name) as PIC_NUM FROM MENUS WHERE Picture!='none' AND OWNER_ID='$owner_id' GROUP BY Name) as A";
    $sql.=" right join (SELECT Distinct(Name) as Menu_Name, COUNT(Product_Name) as PROD_NUM, ROUND(AVG(Price),2) as Mean FROM MENUS WHERE OWNER_ID='$owner_id' GROUP BY Name) as B on A.T1=B.Menu_Name";
    /*
    MOD 2017 v (Last_Modified removed)
    $sql="SELECT Menu_Name,PIC_NUM,PROD_NUM,Mean,Last_Modified FROM (SELECT Distinct(Name) as T1, COUNT(Name) as PIC_NUM FROM MENUS WHERE Picture!='none' AND OWNER_ID='$owner_id' GROUP BY Name) as A";
    $sql.=" right join (SELECT Distinct(Name) as Menu_Name, COUNT(Product_Name) as PROD_NUM, ROUND(AVG(Price),2) as Mean, Last_Modified FROM MENUS WHERE OWNER_ID='$owner_id' GROUP BY Name) as B on A.T1=B.Menu_Name";
    */
    $result=$db->query($sql);

    $output="";

    $template=file_get_contents("table_template.html");
    $template=explode("##",$template);
    $search=array('%menu_name%','%mean%','%pictures%','%items%','%last_modified%');

    echo $db->error();

    while ($row = $result -> fetch_assoc())
    {
        $time=readableTime(date_default_timezone_set($row["Last_Modified"])); //strtotime MOD 2017
        $pic_num=($row["PIC_NUM"]==null) ? "0": $row["PIC_NUM"];
        $replace=array($row["Menu_Name"],"Average Price: ".$row["Mean"]." $",$pic_num." pictures",$row["PROD_NUM"]." meals","Modified ".$time." ago");
        $sequence=str_replace($search,$replace,$template[1]);

        $output.=$sequence;
    }

    return $output;
}

/*
    Loads all menus as options
*/
function loadMenuOptions($selected_menu)
{
    $db=new Db();

    $owner_id=getChainId();

    $sql="SELECT DISTINCT(Name) AS menu_name FROM MENUS WHERE OWNER_ID='$owner_id'";
    $result=$db->query($sql);

    $output="<option value='none'>none</option>";

    while ($row = $result -> fetch_assoc())
    {
        $sel=($row["menu_name"]==$selected_menu) ? "selected" : "";
        $output.="<option value='".$row["menu_name"]."' $sel>".$row["menu_name"]."</option>";
    }

    return $output;
}


/*
    Get name of menu selected by restaurant
*/
function getMenuOwnerName($restaurant_link)
{
    $db=new Db();

    $sql="SELECT Menu_Name FROM RESTAURANTS WHERE Link='$restaurant_link'";
    return $db->fetch($sql,"Menu_Name");
}


/*
    Get shedule list of the logged chain
*/
function loadScheduleList()
{
    $db=new Db();

    $owner_id=getChainId();
    $chainLink=getChainLink();

    $sql="SELECT * FROM SCHEDULE WHERE OWNER_ID='$owner_id'";
    $result=$db->query($sql);
    $structure=explode("##",file_get_contents("table_template.html"))[2];
    $search=array("%name%", "%monday_start%", "%monday_end%", "%tuesday_start%", "%tuesday_end%", "%wednesday_start%", "%wednesday_end%", "%thursday_start%", "%thursday_end%", "%friday_start%", "%friday_end%", "%saturday_start%", "%saturday_end%", "%sunday_start%", "%sunday_end%");

    $output="";
    $count=0;

    while ($row = $result -> fetch_assoc())
    {
        $count++;
        $replace=array($chainLink."_sch_".$count,$row["Monday_Start"],$row["Monday_End"],$row["Tuesday_Start"],$row["Tuesday_End"],$row["Wednesday_Start"],$row["Wednesday_End"],$row["Thursday_Start"],$row["Thursday_End"],$row["Friday_Start"],$row["Friday_End"],$row["Saturday_Start"],$row["Saturday_End"],$row["Sunday_Start"],$row["Sunday_End"]);
        $output.=str_replace($search, $replace, $structure);
    }

    return $output;
}

function loadScheduleOptions($selected_sch)
{
    $db=new Db();

    $owner_id=getChainId();

    $sql="SELECT Name FROM SCHEDULE WHERE OWNER_ID='$owner_id'";
    $result=$db->query($sql);

    $output="<option value='none'>none</option>";

    while ($row = $result -> fetch_assoc())
    {
        $sel=($row["Name"]==$selected_sch) ? "selected" : "";
        $output.="<option value='".$row["Name"]."' $sel>".$row["Name"]."</option>";
    }

    return $output;
}
?>
