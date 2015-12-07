<?php
    $ini_array=parse_ini_file("settings.ini", true);

    $servername=$ini_array['server']['servername'];
    $username=$ini_array['server']['username'];
    $password=$ini_array['server']['password'];
    $dbname=$ini_array['server']['dbname'];
    $path=$ini_array['server']['path'];

    //Create connection
    $conn=mysqli_connect($servername, $username, $password, $dbname);

    //Check connection
    if (!$conn)
    {
        die("Connection failed: " . mysqli_connect_error());
    }
?>
