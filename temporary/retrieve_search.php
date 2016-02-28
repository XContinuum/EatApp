<?php
if (isset($_POST['search_query']))
{
    require("../../requests/server_connection.php");

    $search_query=$_POST['search_query'];
    $longitude=$_POST['longitude'];
    $latitude=$_POST['latitude'];
    $offset=$_POST['offset']*5;
    $max_price="10";



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

        for ($i=0;$i<$items_count-1;$i++)
        {
            //Assemble the items and compare them with the contents of each product
            $like_query.="(lower(FA_MENUS.FA_Contents) LIKE '%".$input[$i]."%') OR ";
        }
        $like_query.="(lower(FA_MENUS.FA_Contents) LIKE '%".$input[$items_count-1]."%')";
        $like_query="(".$like_query.") AND";

        //COUNT+++
        $sql="SELECT COUNT(*) as total from FA_MENUS INNER JOIN FA_RESTORANTS on FA_MENUS.RESTAURANT_ID=FA_RESTORANTS.ID";
        $sql.=" WHERE $like_query (FA_RESTORANTS.FA_Validated=1 AND FA_MENUS.FA_Price<=$price)";
        $sql.=" ORDER BY FA_MENUS.FA_Price ASC LIMIT 5";
        $result=mysqli_query($conn,$sql);
        $data=mysqli_fetch_assoc($result);
        $results=$data['total'];

        //ACTUAL QUERY+++
        $new_query="FA_MENUS.FA_Product_Name,FA_MENUS.FA_Price,FA_MENUS.FA_Pic,FA_RESTORANTS.FA_Username,FA_MENUS.FA_Contents";

        //Distance+++
        $isDistance=false;

        if ($address!=null && strlen($address)>0 && $address!="")
        {
            $isDistance=true;
            $new_query.=",eatappth_DB_EATAPP.getDistance(FA_RESTORANTS.Latitude,FA_RESTORANTS.Longitude,$coord[1],$coord[0]) as distance";
        }
        //Distance---

        $sql=str_replace("COUNT(*) as total",$new_query,$sql);
        $sql.=" OFFSET $offset";
        $result=mysqli_query($conn,$sql);

        while ($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            if ($row["FA_Pic"]!="none")
            $img_src="../restaurant_data/Pictures/".$row["FA_Username"]."/".$row["FA_Pic"];
            else
                $img_src="../images/none.png";

            $distance_output="";

            if ($isDistance==true)
                $distance_output=$row["distance"];

            $json[]= array(
            'image' => $img_src,
            'product_name' => $row["FA_Product_Name"],
            'username' => $row["FA_Username"],
            'price' => $row["FA_Price"],
            'distance' => $distance_output
            );
        }

        mysqli_close($conn);
    }
    else
    /*
        Interval:
        [item] from [min] to [max]
        [item] between [min] and [max]
    */
        //case 1
    if (in_array("from",$input) && isInArray("to",$input))
    {

    }
    else
    //case 2
        if (in_array("between",$input) && isInArray("and",$input))
        {

        }
    /*
        Just tags/names (*last resort*):
        [item]
    */
    else
    {
        //items
        $like_query="";

        for ($i=0;$i<count($input);$i++)
        {
            //Assemble the items and compare them with the contents of each product
            $like_query.="(lower(FA_MENUS.FA_Contents) LIKE '%".$input[$i]."%') OR ";
            $like_query.="(lower(FA_MENUS.FA_Product_Name) LIKE '%".$input[$i]."%') OR ";
        }
        $like_query.="(lower(FA_MENUS.FA_Contents) LIKE '%".$input[count($input)-1]."%') OR";
        $like_query.="(lower(FA_MENUS.FA_Product_Name) LIKE '%".$input[count($input)-1]."%')";
        $like_query="(".$like_query.") AND";

        //COUNT+++
        $sql="SELECT COUNT(*) as total from FA_MENUS INNER JOIN FA_RESTORANTS on FA_MENUS.RESTAURANT_ID=FA_RESTORANTS.ID";
        $sql.=" WHERE $like_query (FA_RESTORANTS.FA_Validated=1 AND FA_MENUS.FA_Price<=$price)";
        $sql.=" ORDER BY FA_MENUS.FA_Price ASC LIMIT 5";
        $result=mysqli_query($conn,$sql);
        $data=mysqli_fetch_assoc($result);
        $results=$data['total'];

        //ACTUAL QUERY+++
        $new_query="FA_MENUS.FA_Product_Name,FA_MENUS.FA_Price,FA_MENUS.FA_Pic,FA_RESTORANTS.FA_Username,FA_MENUS.FA_Contents";

        //Distance+++
        $isDistance=false;

        if ($address!=null && strlen($address)>0 && $address!="")
        {
            $isDistance=true;
            $new_query.=",eatappth_DB_EATAPP.getDistance(FA_RESTORANTS.Latitude,FA_RESTORANTS.Longitude,$coord[1],$coord[0]) as distance";
        }
        //Distance---

        $sql=str_replace("COUNT(*) as total",$new_query,$sql);
        $sql.=" OFFSET $offset";
        $result=mysqli_query($conn,$sql);

        while ($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            if ($row["FA_Pic"]!="none")
            $img_src="../restaurant_data/Pictures/".$row["FA_Username"]."/".$row["FA_Pic"];
            else
                $img_src="../images/none.png";

            $distance_output="";

            if ($isDistance==true)
                $distance_output=$row["distance"];

            $json[]= array(
            'image' => $img_src,
            'product_name' => $row["FA_Product_Name"],
            'username' => $row["FA_Username"],
            'price' => $distance_output
            );
        }

        mysqli_close($conn);
    }


    /*
        Output the results
    */
    saveSearch($_POST['search_query'],$results); //save search

    $output = array();
    $output[]= array(
       'results' => $results,
        'data' => $json
    );


    $jsonstring = json_encode($output);
    echo $jsonstring;
}


function saveSearch($query_,$results_count)
{
    require("../../requests/receive_information.php");
    require("../../requests/server_connection.php");

    $r_id=get_restaurant_id();
    $ip_address=get_client_ip();

    if ($r_id==-1)
    {
        $r_id="0";
    }

    $sql="INSERT INTO SEARCHES (IP_Address, Input, Restaurant_id, Results_Found)";
    $sql.="VALUES ('$ip_address','$query_','$r_id','$results_count')";

    mysqli_query($conn, $sql);
}


function distanceBetween()
{
    $from = "318 Berrigan drive";
    $to = "440 longfields dr";
    $from = urlencode($from);
    $to = urlencode($to);
    $data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
    $data = json_decode($data);
    $time = 0;
    $distance = 0;
    foreach($data->rows[0]->elements as $road)
    {
        $time += $road->duration->value;
        $distance += $road->distance->value;
    }
    $a="To: ".$data->destination_addresses[0];
    $b="From: ".$data->origin_addresses[0];
    $c="Time: ".$time." seconds";
    $d="Distance: ".$distance." meters";

    return $d;
}

?>
