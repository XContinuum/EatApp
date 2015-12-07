<?php
    require("../../requests/receive_information.php");

    $username=getAdminUsername();

    if (isAdminLogged()==1)
    {
        //Admin logged
        $content="<div align='center'>Welcome back, ".$username."</div>";
        $content.="<br><br><div align='center'>".getUnvalidatedUsers()."</div>";
    }
    else
    {
        //Admin not logged
        header("Location: ../../index.php");
    }

    $head_param ="<script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.3.min.js'></script>";
    $head_param.="<script src='settings.js'></script>";

    include("../../template.html");


    function getUnvalidatedUsers()
    {
        $list="<table style='width:700px;border:solid 1px black;background-color:white;padding:5px;'>";
        $list.="<tr><td><b>Username</b></td><td><b>Restaurant Name</b></td><td><b>Email</b></td><td><b>Validate</b></td><td><b>Ban</b></td><td><b>Registered</b></td></tr>";

        require("server_connection.php");

        $sql = "SELECT ID,FA_Username,FA_Restaurant_Name,FA_Email FROM FA_RESTORANTS WHERE FA_Validated='0' LIMIT 10";
        $result = mysqli_query($conn,$sql);



        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
        {
            $list.="<tr><td><a href='$path/".$row['FA_Username']."'>".$row['FA_Username']."</a></td><td>".$row['FA_Restaurant_Name']."</td><td>".$row['FA_Email']."<td><input type='button' value='validate' onClick=\"validate('".$row['ID']."');\"></input></td>";
            $list.="<td><input type='button' value='ban' onClick=\"ban('".$row['ID']."');\"></input></td></tr>";
        }

        $list.="</table>";

        return $list;
    }
?>
