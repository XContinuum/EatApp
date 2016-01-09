<?php
	if (isset($_POST['search_query']))
	{
    $search_query=$_POST['search_query'];
    $search_query=strtolower($search_query);

    require("../requests/server_connection.php");

    //Tag+++
    $json_data=json_decode(file_get_contents("../user/content_list.txt"),true);
    $tags=array();
    $emoji=array();

    foreach ($json_data["List"] as $item)
    {
      if (substr(strtolower($item["Type"]), 0, strlen($search_query)) === $search_query)
      {
        $tags[]=$item["Type"];
        $emoji[]=$item["Emoji"];
      }
    }
    //Tag---

    //Restaurant item+++
    $sql="SELECT FA_MENUS.FA_Product_Name from FA_MENUS INNER JOIN FA_RESTORANTS on FA_MENUS.RESTAURANT_ID=FA_RESTORANTS.ID WHERE (lower(FA_MENUS.FA_Product_Name) LIKE '".$search_query."%') AND FA_RESTORANTS.FA_Validated=1";
    $result=mysqli_query($conn,$sql);
    $output="";

    while ($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
      $output.="<div class='dropdown'><span class='cleanDropdown'>".$row["FA_Product_Name"]."</span></div>";
    }
    //Restaurant item---

    //Restaurant name+++
    $sql="SELECT * from FA_RESTORANTS WHERE (lower(FA_Restaurant_Name) LIKE '".$search_query."%') AND FA_Validated=1;";
    $result=mysqli_query($conn,$sql);

    while ($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
      $output.="<div class='dropdown'><span class='cleanDropdown'>".$row["FA_Restaurant_Name"]."</span></div>";
    }

    mysqli_close($conn);
    //Restaurant name---

    for ($i=0;$i<count($tags);$i++)
    {
      $output.="<div class='dropdown'>".$emoji[$i]." <span class='cleanDropdown'>".$tags[$i]."</span></div>";
    }

    //output the response
    echo $output;
  }
?>
