<?php
    if (checkUsernameStatus($username)==1)
    {
        $content="<div align='center'><br>";
        $content.=getInfo($username,'FA_Restaurant_Name');

        $content.="<br><br><table>".LoadMenu(getInfo($username,'ID'))."</table>";
        $content.="</div>";
    }
    else
    {
        $content="Restaurant <b>".$username."</b> not found";
    }
?>
