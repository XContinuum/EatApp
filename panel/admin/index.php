<?php
    require("../../requests/receive_information.php");

    if (isAdminLogged()==1)
    {
        //Admin logged
        $username=getAdminUsername();


        $content="<div align='center'>Welcome back, ".$username."</div>";
        $content.="<br><br>";

        $content.="<div align='center'><input type='button' value='unvalidated' onClick='show(0);'></input>";
        $content.="<input type='button' value='validated' onClick='show(1);''></input></div>";

        $content.="<br><div align='center' id='validated'>".getUsers(1)."</div>";
        $content.="<div align='center' id='not_validated'>".getUsers(0)."</div>";
    }
    else
    {
        //Admin not logged
        header("Location: ../../index.php");
    }

    $head_param ="<script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.3.min.js'></script>";
    $head_param.="<script src='settings.js'></script>";

    include("../../template.html");


    function getUsers($val)
    {
        $list="<table id='admin_table'>";
        $list.="<tr><td><b>Username</b></td>";
        $list.="<td><b>Restaurant Name</b></td>";
        $list.="<td><b>Email</b></td>";
        $list.="<td><b>Email activated</b></td>";
        $list.="<td><b>Validate</b></td>";
        $list.="<td><b>Ban</b></td>";

        require("server_connection.php");

        $sql = "SELECT ID,FA_Username,FA_Restaurant_Name,FA_Email,FA_Active FROM FA_RESTORANTS WHERE FA_Validated='$val' ORDER BY FA_Dat_Reg ASC LIMIT 10 ";
        $result = mysqli_query($conn,$sql);

        $button_value='validate';

        if ($val==1)
        $button_value='unvalidate';

        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
        {
            $active="<span style='color:red;'>no</span>";

            if ($row['FA_Active']==1)
                $active="<span style='color:green;'>yes</span>";


            $list.="<tr><td><a href='$path/".$row['FA_Username']."'>".$row['FA_Username']."</a></td>";
            $list.="<td>".$row['FA_Restaurant_Name']."</td>";
            $list.="<td>".$row['FA_Email']."</td>";
            $list.="<td>".$active."</td>";
            $list.="<td><input type='button' value='$button_value' onClick=\"$button_value('".$row['ID']."');\"></input></td>";
            $list.="<td><input type='button' value='ban' onClick=\"ban('".$row['ID']."');\"></input></td></tr>";
        }

        $list.="</table>";

        return $list;
    }
?>
