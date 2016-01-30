<?php

$structure=file_get_contents("menu_row_structure.html");
$pieces=explode("##", $structure);

/*
    Loads a menu based on its name, just for display
*/
function LoadMenu($menu_name)
{
    require ("../../requests/server_connection.php");

    $sql="SELECT Product_Name,Price,Description,Contents,Section,Picture ";
    $sql.="FROM MENUS WHERE Name='$menu_name'";
    $result=mysqli_query($conn,$sql);

    $menu="";
    $count=0;

    $old_section_name="none";

    while($row=mysqli_fetch_array($result))
    {
        $count++;
        $colspan_img="";

        //Section+++
        if ($old_section_name!=$row['Section'])
        {
            $section_name=$row['Section'];
            $menu.="<tr><td colspan='4' align='center' class='menu_section_title'>$section_name</td></tr>";
            $old_section_name=$row['Section'];
        }

        $menu.="<tr>";
        //Section---

        //PICTURE+++
        $pic_url="#";
        if ($row['Picture']!="none")
        {
            $pic_url="/restaurant_data/Pictures/$res_username/".$row['Picture'];
        }
        //PICTURE---


        //CONTENTS+++

        if ($contents!="none" && $contents!="")
        {
            $contents=explode(".",$row['Contents']);
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


        $data=array($pic_url,$row['Product_Name'],$row['Price'],$tag_width,$reslt);
        $search=array('%pic%','%Product_Name%','%Price%','%Tag_Width%','%Result%');
        $menu=str_replace($search,$data,$pieces[2]);
    }

    mysqli_close($conn);

    if ($count==0)
    {
        $menu="0";
    }

    return $menu;
}

/*
    Load menu from the menu_name and fill in an edit field
    via %structure% for editing
*/
function load_editMenu($menu_name)
{
    require("../../requests/server_connection.php");

    $sql="SELECT Product_Name,Price,Description,Contents,Section,Picture FROM MENUS WHERE Name='$menu_name'";
    $result=mysqli_query($conn,$sql);


    $count=0;

    $data=array('1','0','none','','','','','');

    //Sections
    $menu="";
    $previous_section="";
    $section_count=0;

    while($row=mysqli_fetch_array($result))
    {
        $count++;
        $section_name=$row['Section'];

        if ($section_name!=$previous_section && $section_name!="none")
        {
            $section_count++;
            $menu.=insertSection($section_count,$section_name);
            $previous_section=$section_name;
        }

        $data=array($count,$count-1,$row['Picture'],$row['Picture'],$row['Product_Name'],$row['Price'],$row['Description'],$row['Contents']);
        $menu.=fillRow($data);
    }

    mysqli_close($conn);

    if ($count==0)
        $menu=fillRow($data);

    return $menu;
}

/*
    Fill Row from html %structure% for editing
*/
function fillRow($data_,$menu_name)
{
    $struct=$GLOBALS['pieces'][1];

    //PICTURE
    $chain_link=getChainLink();
    $pic="../images/upload_picture.png";

    if ($data_[2]!="none")
    {
        $pic="../../restaurant_data/Pictures/".$chain_link."/".$menu_name."/".$pic_url;
    }

    $data_[2]=$pic;
    //PICTURE

    $search=array('%order%','%i%','%SRC%','%picture_url%','%product_name%','%price%','%description%','%food_content%');
    $final_row=str_replace($search,$data_,$struct);

   return $final_row;
}

/*
    Fill Section from html %structure% for editing
*/
function insertSection($order,$section_name)
{
    $struct=$GLOBALS['pieces'][0];

    $search=array('%section%','%section_name%');
    $replace=array($order,$section_name);
    $final_section=str_replace($search,$replace,$struct);

    return $final_section;
}

function getLastModified($menu_name)
{
    require("../../request/server_connection.php");

    $sql="SELECT Last_Modified FROM MENUS WHERE Name='$menu_name'";
    $result=mysqli_query($conn,$sql);
    $final_result=mysqli_fetch_assoc($result);

    $time=strtotime($final_result["Last_Modified"]);

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
