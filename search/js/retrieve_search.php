<?php
require_once("../../requests/receive_information.php");

if (isset($_POST['search_query']))
{
    $db=new Db();
    //INITIAL SETTINGS+++
    $search_query=$_POST['search_query'];
    $address=$_POST['address'];
    $offset=$_POST['offset']*5;
    $coord=getCoordinates($address);//coordinates

    $max_price="10";
    $min_price=$_POST['search_query'];
    $max_distance="20";
    $max_distance="0";
    $currency="CAD";


    $longitude=$_POST['longitude'];
    $latitude=$_POST['latitude'];
    //INITIAL SETTINGS---


    $search_query=strtolower($search_query);

    $split=str_replace("$","",$search_query);
    $split=str_replace("for less than","less",$split);
    $split=str_replace("less than","less",$split);
    $split=str_replace("for","less",$split);

    $input=preg_split("/[\s,]+/", $split); //split string into array
    $input=array_map('trim', $input); //delete spaces
    $input=array_filter($input); //delete empty elements
    $input=array_values($input); //reindex array

    $results=0;

    $json=array();


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

        $arr=array();
        for ($i=0;$i<$items_count;$i++)
        {
            //Assemble the items and compare them with the contents of each product
            $arr[]="(lower(MENUS.Contents) LIKE '%".$input[$i]."%')";
            $arr[]="(lower(MENUS.Product_Name) LIKE '%".$input[$i]."%')";
        }
        $like_query="(".implode(" OR ",$arr).") AND";

        //COUNT+++
        $sql="SELECT COUNT(*) as total from MENUS INNER JOIN CHAIN_OWNER on MENUS.OWNER_ID=CHAIN_OWNER.ID INNER JOIN RESTAURANTS on MENUS.Name=RESTAURANTS.Menu_Name";
        $sql.=" WHERE $like_query (CHAIN_OWNER.Validated=1 AND MENUS.Price<=$price)";
        $sql.=" ORDER BY MENUS.Price ASC LIMIT 5";
        $results=$db->fetch($sql,"total");

        //ACTUAL QUERY+++
        $new_query="MENUS.Name,MENUS.Product_Name,MENUS.Price,MENUS.Picture,CHAIN_OWNER.Restaurant_Name,CHAIN_OWNER.Link as MainLink,RESTAURANTS.Link as MinorLink,MENUS.Contents";

        //Distance+++
        $isDistance=false;

        if ($address!=null && strlen($address)>0 && $address!="")
        {
            $isDistance=true;
            $new_query.=",getDistance(RESTAURANTS.Latitude,RESTAURANTS.Longitude,$coord[1],$coord[0]) as distance";
        }

        //app
        if ($longitude!="" && $latitude!="" && $longitude!=null && $latitude!=null)
        {
            $isDistance=true;
            $new_query.=",getDistance(RESTAURANTS.Latitude,RESTAURANTS.Longitude,$latitude,$longitude) as distance";
        }
        //Distance---

        $sql=str_replace("COUNT(*) as total",$new_query,$sql);
        $sql.=" OFFSET $offset";
        $result=$db->query($sql);

        while ($row = $result -> fetch_assoc())
        {
            $img_src="../../images/none.png";

            if ($row["Picture"]!="none")
            {
                $img_src="../../restaurant_data/Pictures/".$row["MainLink"]."/".$row["Name"]."/".$row["Picture"];
            }

            $distance_output=($isDistance)?$row["distance"]:"";

            $json[]= array(
            'image' => $img_src,
            'product_name' => $row["Product_Name"],
            'link' => $row["MainLink"]."/".$row["MinorLink"],
            'price' => $row["Price"],
            'restaurant_name' => $row["Restaurant_Name"],
            'distance' => $distance_output
            );
        }

    }
    else
    /*
        Interval:
        [item] from [min] to [max]
        [item] between [min] and [max]
    */
        //case 1
    if (in_array("from",$input) && in_array("to",$input))
    {

    }
    else
    //case 2
        if (in_array("between",$input) && in_array("and",$input))
        {

        }
    /*
        Just tags/names (*last resort*):
        [item]
    */
    else
    {

    }

    /*
        Output the results
    */
    saveSearch($_POST['search_query'],$results); //save search

    $output=array(
       'results' => $results,
        'data' => $json
    );


    echo json_encode($output);
}


?>
