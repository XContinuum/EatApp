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
            $menu.="<td><input type='text' style='width:60px;' name='Price_".$order."' value='".$row['FA_Price']."'></input>";
            $menu.="</input><div class='select-style'><select><option value='CAD' select>CAD</option><option value='USD'>USD</option></select></td>";
            $menu.="<td> <input type='text' name='Description_".$order."' value='".$row['FA_Desc']."'></input></td>";


            $contents=explode(".",$row['FA_Contents']);

            $reslt="";
            for ($i=0;$i<count($contents);$i++)
            {
            $reslt.=$contents[$i]." ";
            }

            $menu.="<td id='food_contents'>".$reslt."</td>";
            $menu.="<td><a class='up' href='#'>Up</a> <a class='down' href='#'>Down</a></td>";

            $menu.="</tr>";
        }

        mysqli_close($conn);

        if ($count==0)
        {
            $menu="<tr><td>1</td><td><img src='images/upload_picture.png'></td><td><input type='text' name='Product_name_1'></input></td><td><input type='text' style='width:60px;' name='Price_1'></input><div class='select-style'><select><option value='CAD' select>CAD</option><option value='USD'>USD</option></select></div></td><td><input type='text' name='Description_1'></input></td><td id='food_contents'></td><td><a class='up' href='#'>Up</a> <a class='down' href='#'>Down</a></td></tr>";
        }

        return $menu;
    }


    function getLastModified($restaurant_id)
    {
        require ("server_connection.php");

        $sql = "SELECT FA_Last_Modified FROM FA_MENUS WHERE RESTAURANT_ID='$restaurant_id' ORDER BY FA_Order ASC";
        $result = mysqli_query($conn,$sql);
        $final_result= mysqli_fetch_assoc($result);

        $time = date_default_timezone_set($final_result["FA_Last_Modified"]);

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
