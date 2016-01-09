<?php
	if (isset($_POST['search_query']))
	{
    $search_query=$_POST['search_query'];
    $offset=$_POST['offset']*5;
    $search_query=strtolower($search_query);

    require("../requests/server_connection.php");


    $split=str_replace("$","",$search_query);
    $split=str_replace("less than","less",$split);
    $split=str_replace("for","less",$split);
    $input=preg_split("/[\s,]+/", $split); //explode(" ", $search_query);
    $output="";
    //$filters=loadFilters();

    /*
        Max price:
        [items] for [price]
    */
    if (in_array("less",$input))
    {
        $items_count=0;

        for ($i=0;$i<count($input);$i++)
        {
            if ($input[$i]=="less")
            {
                $items_count=$i;
            }
        }

        //price
        $price=floatval($input[$items_count+1]);

        //items
        $like_query="";

        for ($i=0;$i<$items_count-1;$i++)
        {
            //Assemble the items and compare them with the contents of each product
            $like_query.="(lower(FA_MENUS.FA_Contents) LIKE '%".$input[$i]."%') OR ";
        }
        $like_query.="(lower(FA_MENUS.FA_Contents) LIKE '%".$input[$items_count-1]."%') AND ";

        //COUNT+++
        $sql="SELECT COUNT(*) as total from FA_MENUS INNER JOIN FA_RESTORANTS on FA_MENUS.RESTAURANT_ID=FA_RESTORANTS.ID";
        $sql.=" WHERE $like_query FA_RESTORANTS.FA_Validated=1 AND FA_MENUS.FA_Price<=$price";
        $sql.=" ORDER BY FA_MENUS.FA_Price ASC LIMIT 5";
        $result=mysqli_query($conn,$sql);
        $data=mysqli_fetch_assoc($result);
        $pages=$data['total'];

        //ACTUAL QUERY+++
        $sql=str_replace("COUNT(*) as total","FA_MENUS.FA_Product_Name,FA_MENUS.FA_Price,FA_MENUS.FA_Pic,FA_RESTORANTS.FA_Username,FA_MENUS.FA_Contents",$sql);
        $sql.=" OFFSET $offset";
        $result=mysqli_query($conn,$sql);

        while ($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            if ($row["FA_Pic"]!="none")
            $img_src="../restaurant_data/Pictures/".$row["FA_Username"]."/".$row["FA_Pic"];
            else
                $img_src="../images/none.png";

            $output.="<div class='item_box'><table><tr>";
            $output.="<td class='img_td'><img class='item_img' src='$img_src' /></td>";
            $output.="<td valign='top'>".$row["FA_Product_Name"]."<br /><a href='/".$row["FA_Username"]."' target='_blank'>".$row["FA_Username"]."</a>";
            $output.="<br /><br />".$row["FA_Price"]."$";
            $output.="</td></tr></table></div>";
        }
    }

    /*
        Interval:
        [item] from [min] to [max]
    */
    if (in_array("from",$input) && isInArray("to",$input))
    {
        $output=$output;
    }


    //output the response

    //print_r($input);

    $json = array();
    $json[]= array(
       'pages' => $pages,
        'output' => $output
    );

    $jsonstring = json_encode($json);
    echo $jsonstring;

    //echo $pages."<br>".$output;
  }


  /*function loadFilters()
  {
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

    return $tags[];
  }*/
?>
