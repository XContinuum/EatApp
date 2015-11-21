<?php
    function LoadMenu($restaurant_id)
    {
        require ("server_connection.php");

        $sql = "SELECT FA_Order,FA_Product_Name,FA_Price,FA_Desc,FA_Contents,FA_Section ";
        $sql.= "FROM FA_MENUS WHERE RESTAURANT_ID='$restaurant_id' ORDER BY FA_Order ASC";
        $result = mysqli_query($conn,$sql);

        $menu="";
        $count=0;

        while($row = mysqli_fetch_array($result))
        {
            $count++;

            $menu.="<tr>";
            $menu.="<td></td>";
            $menu.="<td>".$row['FA_Product_Name']."</td>";
            $menu.="<td>".$row['FA_Price']."$</td>";
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

        if ($count==0)
        {
            $menu="0";
        }

        return $menu;
    }

     function FillMenuBlanks($restaurant_id)
    {
        require ("server_connection.php");

        $sql = "SELECT FA_Order,FA_Product_Name,FA_Price,FA_Desc,FA_Contents FROM FA_MENUS WHERE RESTAURANT_ID='$restaurant_id' ORDER BY FA_Order ASC";
        $result = mysqli_query($conn,$sql);

        $menu="";
        $count=0;

        while($row = mysqli_fetch_array($result))
        {
            $count++;
            $order=$row['FA_Order'];
            $menu.="<tr>";
            $menu.="<td>".$order."</td>";
            $menu.="<td><img src='images/upload_picture.png'></td>";
            $menu.="<td><input type='text' name='Product_name_".$order."' value='".$row['FA_Product_Name']."'></input></td>";
            $menu.="<td><input type='text' style='width:40px;' name='Price_".$order."' value='".$row['FA_Price']."'></input></td>";
            $menu.="<td><input type='text' name='Description_".$order."' value='".$row['FA_Desc']."'></input></td>";


            $contents=explode(".",$row['FA_Contents']);

            $reslt="";
            for ($i=0;$i<count($contents);$i++)
            {
            $reslt.=$contents[$i]." ";
            }

            $menu.="<td id='food_contents'>".$reslt."</td>";
            $menu.="<td><a class='up' href='#'><img src='images/up_arrow.png'></a> <a class='down' href='#'><img src='images/down_arrow.png'></a></td>";

            $menu.="</tr>";
        }

        mysqli_close($conn);

        if ($count==0)
        {
            $menu="<tr><td>1</td>";
            $menu.="<td><img src='images/upload_picture.png'></td>";
            $menu.="<td><input type='text' name='Product_name_1'></input></td>";
            $menu.="<td><input type='text' style='width:60px;' name='Price_1'></input></td>";
            $menu.="<td><input type='text' name='Description_1'></input></td>";
            $menu.="<td id='food_contents'></td>";
            $menu.="<td><a class='up' href='#'><img src='images/up_arrow.png'></a> <a class='down' href='#'><img src='images/down_arrow.png'></a></td></tr>";
        }

        return $menu;
    }


    function getLastModified($restaurant_id)
    {
        require ("server_connection.php");

        $sql = "SELECT FA_Last_Modified FROM FA_MENUS WHERE RESTAURANT_ID='$restaurant_id' ORDER BY FA_Order ASC";
        $result = mysqli_query($conn,$sql);
        $final_result= mysqli_fetch_assoc($result);

        $time = date_default_timezone_set($final_result["FA_Last_Modified"]); // strtotime changed MOD 2017

        return "Last updated ".humanTiming($time)." ago";
    }

    function humanTiming($time)
    {
        $time = time() - $time;//to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text)
        {
            if ($time < $unit) continue;

            $numberOfUnits= floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }

    }
?>
