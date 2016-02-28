<?php
require_once("../../requests/receive_information.php");

if (isset($_POST['search_query']))
{
    /* Initial settings */
    $search_query=$_POST['search_query'];
    $offset=$_POST['pageOffset']*5;
    //$coord=getCoordinates($_POST['address']);//coordinates // COMMENTED OUT MOD 2017

    /* Default parameters */
    $data["max_distance"]=$_POST['maxRadius'];
    $data["min_distance"]=$_POST['minRadius'];
    $data["max_price"]=str_replace("$","",$_POST['maxPrice']);
    $data["min_price"]=str_replace("$","",$_POST['minPrice']);
    $data["address"]=$_POST['address'];
    $data["longitude_app"]=$_POST['longitude'];
    $data["latitude_app"]=$_POST['latitude'];
    $data["currency"]=$_POST['currency'];
    $data["longitude"]=$coord[0];
    $data["latitude"]=$coord[1];

    $input=cleanString($search_query);
    $numOfResults=0;

    /*
        Max price:
        [items] for/less/for less than/less than [price]
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
            else
                if ($items_count==0)
                {
                    $list[]=$input[$i];
                }
        }

        //price
        $price=floatval($input[$items_count+1]);
        $data["max_price"]=$price;

        $result=generateQuery($list,$numOfResults,$offset,$data);
    }
    else
    /*
        Interval:
        [item] from [min] to [max]
        [item] between [min] and [max]
    */
    if (in_array("from",$input) && in_array("to",$input))
    {

    }
    else
    /*
        Case 2
    */
        if (in_array("between",$input) && in_array("and",$input))
        {

        }
    /*
        Just tags/names (*last resort*):
        [item]
    */
    else
    {
        $result=generateQuery($input,$numOfResults,$offset,$data);
    }


    $json=array();

    if (isset($result))
    {
        while ($row = $result -> fetch_assoc())
        {
            $img_src="../../images/none.png";

            if ($row["Picture"]!="none")
            {
                $img_src="../../restaurant_data/Pictures/".$row["MainLink"]."/".$row["Name"]."/".$row["Picture"];
            }

            $distance_output=(isset($row["distance"])) ? $row["distance"] : "";

            $json[]=array(
            'image' => $img_src,
            'product_name' => $row["Product_Name"],
            'link' => $row["MainLink"]."/".$row["MinorLink"],
            'price' => $row["Price"],
            'restaurant_name' => $row["Restaurant_Name"],
            'distance' => $distance_output
            );
        }
    }

    /*
        Output the results
    */
    saveSearch($_POST['search_query'],$numOfResults); //save search

    $output=array(
       'results' => $numOfResults,
        'data' => $json
    );


    echo json_encode($output);
}

/*
    Cleans and splits search query
*/
function cleanString($string)
{
    $string=strtolower($string);

    $split=str_replace("$","",$string);
    $split=str_replace("for less than","less",$split);
    $split=str_replace("less than","less",$split);
    $split=str_replace("for","less",$split);

    $result=preg_split("/[\s,]+/", $split); //split string into array
    $result=array_map('trim', $result); //delete spaces
    $result=array_filter($result); //delete empty elements

    return array_values($result); //reindex array
}


function generateQuery($input,&$numOfResults,$offset,$data)
{
    $db=new Db();

    /* Assemble the items and compare them with the contents of each product */
    for ($i=0;$i<count($input);$i++)
    {
        $arr[]="(lower(MENUS.Contents) LIKE '%".$input[$i]."%')";
        $arr[]="(lower(MENUS.Product_Name) LIKE '%".$input[$i]."%')";
    }
    $intermediate="(".implode(" OR ",$arr).") AND";

    /* Restrictions */
    $restriction[]="CHAIN_OWNER.Validated=1";

    if ($data["max_price"]!=0)
    $restriction[]="MENUS.Price<=".$data["max_price"];

    if ($data["min_price"]!=0)
    $restriction[]="MENUS.Price<=".$data["min_price"];

    $rest=implode(" AND ",$restriction);

    /* Distance */
    $distance_query="";

    if ($data["address"]!=null && strlen($data["address"])>0 && $data["address"]!="")
    {
        $distance_query=",getDistance(RESTAURANTS.Latitude,RESTAURANTS.Longitude,".$data["latitude"].",".$data["longitude"].") as distance";
    }
    else
    /* APP distance */
    if ($data["longitude_app"]!=0 && $data["latitude_app"]!=0)
    {
        $distance_query=",getDistance(RESTAURANTS.Latitude,RESTAURANTS.Longitude,".$data["latitude_app"].",".$data["longitude_app"].") as distance";
    }

    /* Count number of results for the search*/
    $sql="SELECT COUNT(*) as total from MENUS INNER JOIN CHAIN_OWNER on MENUS.OWNER_ID=CHAIN_OWNER.ID INNER JOIN RESTAURANTS on MENUS.Name=RESTAURANTS.Menu_Name";
    $sql.=" WHERE $intermediate ($rest)";
    $sql.=" ORDER BY MENUS.Price ASC LIMIT 5";
    $numOfResults=$db->fetch($sql,"total");

    /* Retrieve actual search */
    $new_query="MENUS.Name,MENUS.Product_Name,MENUS.Price,MENUS.Picture,CHAIN_OWNER.Restaurant_Name,CHAIN_OWNER.Link as MainLink,RESTAURANTS.Link as MinorLink,MENUS.Contents";
    //$new_query.=$distance_query; // COMMENTED OUT MOD 2017

    $sql=str_replace("COUNT(*) as total",$new_query,$sql);
    $sql.=" OFFSET $offset";
    return $db->query($sql);
}
?>
