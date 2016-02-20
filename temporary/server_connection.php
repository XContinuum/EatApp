<?php
    $ini_array=parse_ini_file("settings.ini");

    //Create connection
    $conn=mysqli_connect($ini_array['servername'], $ini_array['username'], $ini_array['password'], $ini_array['dbname']);

    //Check connection
    if (!$conn)
    {
        die("Connection failed: " . mysqli_connect_error());
    }
?>
