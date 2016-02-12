<?php
    function LoadMenu($restaurant_id)
    {
        require ("server_connection.php");

        $res_username=getInfoFromID($restaurant_id,"FA_Username");

        $sql = "SELECT FA_Order,FA_Product_Name,FA_Price,FA_Desc,FA_Contents,FA_Section,FA_Pic ";
        $sql.= "FROM FA_MENUS WHERE RESTAURANT_ID='$restaurant_id' ORDER BY FA_Order ASC";
        $result = mysqli_query($conn,$sql);

        $menu="";
        $count=0;

        $old_section_name="none";

        while($row = mysqli_fetch_array($result))
        {
            $count++;
            $colspan_img="";

            //Section+++
            if ($old_section_name!=$row['FA_Section'])
            {
                $section_name=$row['FA_Section'];
                $menu.="<tr><td colspan='4' align='center' class='menu_section_title'>$section_name</td></tr>";
                $old_section_name=$row['FA_Section'];
            }

            $menu.="<tr>";
            //Section---

            //PICTURE+++
            if ($row['FA_Pic']!="none")
            {
                $pic=$row['FA_Pic'];
                $menu.="<td width='100px'><img src='../restaurant_data/Pictures/$res_username/$pic' style='width:50px;border-radius:50%;box-shadow: 0px 0px 2px #999999;' /></td>";
            }
            else
            {
                //$menu.="<td width='100px'></td>";
                $colspan_img="colspan='2'";
            }
            //PICTURE---

            $menu.="<td width='50%' $colspan_img>".$row['FA_Product_Name']."</td>";
            $menu.="<td width='50%' align='right'>".$row['FA_Price']."$</td>";


            //CONTENTS+++
            $tag_width="160px";

            if ($contents!="none" && $contents!="")
            {
            $contents=explode(".",$row['FA_Contents']);
            array_pop($contents);

            $reslt="";
                for ($i=0;$i<count($contents);$i++)
                {
                    $reslt.="<div class='tags'>#".str_ireplace("_"," ",$contents[$i])."</div> ";
                }

            $reslt="<div class='wrap'>".$reslt."</div>";
            }
            else
            {
                $reslt="";
                $tag_width="0%";
            }

            $menu.="<td width='$tag_width' align='right'>".$reslt."</td>";

            //CONTENTS---

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

        $sql = "SELECT FA_Order,FA_Product_Name,FA_Price,FA_Desc,FA_Contents,FA_Section,FA_Pic FROM FA_MENUS WHERE RESTAURANT_ID='$restaurant_id' ORDER BY FA_Order ASC";
        $result = mysqli_query($conn,$sql);

        $menu="";
        $count=0;

        //Sections
        $previous_section="";
        $section_count=0;

        while($row=mysqli_fetch_array($result))
        {
            $count++;

            if ($row['FA_Section']!=$previous_section && $row['FA_Section']!="none")
            {
                $section_count++;
                $menu.=insertSection($section_count,$row['FA_Section']);
                $previous_section=$row['FA_Section'];
            }

            $menu.=fillTable($row['FA_Order'],$row['FA_Product_Name'],$row['FA_Price'],$row['FA_Desc'],$row['FA_Contents'],$row['FA_Pic']);
        }

        mysqli_close($conn);

        if ($count==0)
            $menu=fillTable('1','','','','','none');


        return $menu;
    }

    function fillTable($order,$product,$price,$description,$food_content,$pic)
    {
            $row="";
            $row.="<tr>";
            $row.="<td>$order</td>";

            //PICTURE
            $res_username=get_restaurant_username();

            $row.="<td>";

            if ($pic!="none")
            {
                //show picture
                $row.="<label><input name='food_images_$order' style='display:none;' type='file' value='Select picture'></input>";
                $row.="<img src='../restaurant_data/Pictures/$res_username/$pic' style='width:50px;border-radius:50%;' align='center' id='display_image_$order'></img></label>";
            }
            else
            {
                //no pictures
                $row.="<label><input name='food_images_$order' style='width:110px;display:none;' type='file' value='Select picture'></input>";
                $row.="<img src='images/upload_picture.png' id='display_image_$order'></img></label>";
            }

            $row.="<input type='hidden' name='crop_info_$order'></input>";
            $row.="<input type='hidden' name='picture_url_$order' value='$pic'></input>";

            $row.="</td>";
            //PICTURE


            $row.="<td><input type='text' name='Product_name_$order' value='$product' placeholder='Meal'></input></td>";
            $row.="<td><input type='text' style='width:40px;' name='Price_$order' value='$price' placeholder='Price'></input></td>";
            $row.="<td><textarea name='Description_$order' placeholder='Description'>$description</textarea></td>";
            $row.="<td class='food_contents'>$food_content</td>";
            $row.="<td><a class='up'><img src='images/up_arrow.png'></a> ";
            $row.="<a class='down'><img src='images/down_arrow.png'></a> ";
            $row.="<a class='delete_row'><img src='images/delete.png'></a></td>";
            $row.="</tr>";

            return $row;
    }

    function insertSection($order,$section_name)
    {
        $row="<tr align='center'><td colspan='7'>";
        $row.="<input type='text' placeholder='Section ".$order."' name='Section_".($order-1)."' value='$section_name'></input>";
        $row.="<input type='button' value='Delete' class='remove_section delete_button'></input></td></tr>";

        return $row;
    }



    function getLastModified($restaurant_id)
    {
        require ("server_connection.php");

        $sql = "SELECT FA_Last_Modified FROM FA_MENUS WHERE RESTAURANT_ID='$restaurant_id' ORDER BY FA_Order ASC";
        $result = mysqli_query($conn,$sql);
        $final_result= mysqli_fetch_assoc($result);

        $time = strtotime($final_result["FA_Last_Modified"]);

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
