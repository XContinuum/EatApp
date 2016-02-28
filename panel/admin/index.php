<?php
require_once("../../requests/receive_information.php");

/*Admin logged*/
if (isAdminLogged())
{
    $Admin=getAdminUsername();
    $panel=setPanel();

    $content=file_get_contents("main_content.html");
    $form=file_get_contents("index_form.html");

    $search=array("%admin%","%output1%","%output2%");
    $replace=array($Admin,getChains(1),getChains(0));

    $form=str_replace($search, $replace, $form);
    $content=str_replace("%content%", $form, $content);

    $head_param="<script src='js/settings.js'></script>";
    include("../../user_template.html");
}
else
/*Admin not logged*/
{
    header("Location: ../../index.php");
}


function getChains($val)
{
    $db=new Db();

    $structure=file_get_contents("restaurant_structure.html");
    $templates=explode("##",$structure);
    $search=array("%path%","%link%","%restaurant_name%","%email%","%active%","%color%","%button_value%","%ID%","%menu_num%");
    $button_value=($val ? "unvalidate" : "validate");
    $compile="";

    /* Database */
    $sql="SELECT ID,Link,Restaurant_Name,Email,Active FROM CHAIN_OWNER WHERE Validated='$val' ORDER BY Dat_Reg ASC LIMIT 10";
    $result=$db->query($sql);

    while ($row = $result -> fetch_assoc())
    {
        $menu_num=getMenuNumber($row["ID"]);
        $color=($row["Active"] ? "background-color:#66ff99;" : "background-color:#ff9980;");
        $replace=array(setLinkMute("/".$row["Link"]),$row["Link"],$row["Restaurant_Name"],$row["Email"],($row["Active"]?"yes":"no"),$color,$button_value,$row["ID"],$menu_num);
        $compile.=str_replace($search,$replace,$templates[1]);
    }
    $final=str_replace("%list%", $compile, $templates[0]);


    return $final;
}

function getMenuNumber($Chain_ID)
{
    $db=new Db();

    return $db->fetch("SELECT COUNT(Distinct(Name)) as TotalMenus FROM MENUS WHERE OWNER_ID='$Chain_ID'","TotalMenus");
}
?>
