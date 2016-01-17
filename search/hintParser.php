<?php
if (isset($_POST['search_query']))
{
    require("../requests/server_connection.php");

    $search_query=$_POST['search_query'];
    $search_query=strtolower($search_query);
    $json=array();

    getTagsList($json,$search_query);

    //Restaurant item+++
    $sql="SELECT FA_MENUS.FA_Product_Name from FA_MENUS INNER JOIN FA_RESTORANTS on FA_MENUS.RESTAURANT_ID=FA_RESTORANTS.ID WHERE ";
    $sql.="(lower(FA_MENUS.FA_Product_Name) LIKE '".$search_query."%') AND FA_RESTORANTS.FA_Validated=1 LIMIT 10";
    $result=mysqli_query($conn,$sql);

    while ($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
          $json[]= array(
            'icon' => "",
            'item' => $row["FA_Product_Name"]
            );
    }
    //Restaurant item---

    //Restaurant name+++
    $sql="SELECT FA_Restaurant_Name from FA_RESTORANTS WHERE (lower(FA_Restaurant_Name) LIKE '".$search_query."%') AND FA_Validated=1 LIMIT 10";
    $result=mysqli_query($conn,$sql);

    while ($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
         $json[]= array(
            'icon' => "",
            'item' => $row["FA_Restaurant_Name"]
            );
    }

    mysqli_close($conn);
    //Restaurant name---

    //output the response
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function getTagsList(&$json_,$q_srch)
{
    $json_data=json_decode(file_get_contents("../user/content_list.txt"),true);
    $tags=array();
    $emoji=array();

    foreach ($json_data["List"] as $item)
    {
      if (substr(strtolower($item["Type"]), 0, strlen($q_srch)) === $q_srch)
      {
        $tags[]=$item["Type"];
        $emoji[]=$item["Emoji"];
      }
    }

    for ($i=0;$i<count($tags);$i++)
    {
        $json_[]= array(
            'icon' => $emoji[$i],
            'item' => $tags[$i]
            );
    }
}

?>
