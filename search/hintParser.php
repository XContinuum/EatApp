<?php
if (isset($_POST['search_query']))
{
    require("../requests/access_db.php");

    $db=new Db();

    $search_query=$_POST['search_query'];
    $search_query=strtolower($search_query);
    $json=array();

    getTagsList($json,$search_query);

    //Restaurant item+++
    $sql="SELECT MENUS.Product_Name from MENUS INNER JOIN CHAIN_OWNER on MENUS.OWNER_ID=CHAIN_OWNER.ID WHERE ";
    $sql.="(lower(MENUS.Product_Name) LIKE '".$search_query."%') AND CHAIN_OWNER.Validated=1 LIMIT 10";
    $result=$db->query($sql);

    while ($row = $result -> fetch_assoc())
    {
          $json[]= array(
            'icon' => "",
            'item' => $row["Product_Name"]
            );
    }
    //Restaurant item---

    //Restaurant name+++
    $sql="SELECT Restaurant_Name from CHAIN_OWNER WHERE (lower(Restaurant_Name) LIKE '".$search_query."%') AND Validated=1 LIMIT 10";
    $result=$db->query($sql);

    while ($row = $result -> fetch_assoc())
    {
         $json[]= array(
            'icon' => "",
            'item' => $row["Restaurant_Name"]
            );
    }

    //Restaurant name---

    //output the response
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function getTagsList(&$json_,$q_srch)
{
    $json_data=json_decode(file_get_contents("../user/setup/filters.txt"),true);
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
