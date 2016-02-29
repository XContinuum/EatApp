<?php
require_once("../../requests/receive_information.php");

$structure=file_get_contents("menu_row_structure.html");
$pieces=explode("##", $structure);

/*
    Loads a menu based on its name, just for display
*/
function LoadMenu($menu_name)
{
    $db=new Db();

    $sql="SELECT Product_Name,Price,Description,Contents,Section,Picture,Currency ";
    $sql.="FROM MENUS WHERE Name='$menu_name'";
    $result=$db->query($sql);

    $menu="";
    $count=0;

    $old_section_name="none";

    while ($row = $result -> fetch_assoc())
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
            $pic_url="/restaurant_data/Pictures/$res_username/$menu_name/".$row['Picture'];
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
    $db=new Db();

    $sql="SELECT Product_Name,Price,Description,Contents,Section,Picture FROM MENUS WHERE Name='$menu_name'";
    $result=$db->query($sql);

    $count=0;
    $data=array('1','0','none','none','','','','');

    //Sections
    $menu="";
    $previous_section="";
    $section_count=0;

    while ($row = $result -> fetch_assoc())
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
        $menu.=fillRow($data,$menu_name);
    }

    if ($count==0)
        $menu=fillRow($data, $menu_name); // MOD 2017 $menu_name was a missing argument

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
    $pic="images/upload_picture.png";

    if ($data_[2]!="none")
    {
        $pic="../../restaurant_data/Pictures/$chain_link/$menu_name/".$data_[2];
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
    $db=new Db();

    $sql="SELECT Last_Modified FROM MENUS WHERE Name='$menu_name'";
    $time=strtotime($db->fetch($sql,"Last_Modified"));

    return "Last updated ".readableTime($time)." ago";
}

?>
