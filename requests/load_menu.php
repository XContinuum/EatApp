<?php
    function LoadMenu($restaurant_id)
    {
        require ("server_connection.php");

        $sql = "SELECT FA_Order,FA_Product_Name,FA_Price,FA_Desc,FA_Contents FROM FA_MENUS WHERE RESTAURANT_ID='$restaurant_id' ORDER BY FA_Order ASC";
        $result = mysqli_query($conn,$sql);

        $menu="";

        while($row = mysqli_fetch_array($result))
        {
            $menu.="<tr>";
            $menu.="<td>".$row['FA_Order']."</td>";
            $menu.="<td></td>";
            $menu.="<td>".$row['FA_Product_Name']."</td>";
            $menu.="<td>".$row['FA_Price']." CAD</td>";
            $menu.="<td>".$row['FA_Desc']."</td>";


            $contents=explode(".",$row['FA_Contents']);

            $reslt="";
            for ($i=0;$i<count($contents);$i++)
            {
            $reslt.=$contents[$i]." ";
            }

            $menu.="<td>".$reslt."</td>";


            $menu.="</tr>";
        }

        mysqli_close($conn);

        return $menu;
    }
?>
