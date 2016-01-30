<?php
    if(isset($_POST["link"]))
    {
        if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
        {
            die();
        }
        $ini_array=parse_ini_file("settings.ini", true);

        $servername=$ini_array['server']['servername'];
        $username=$ini_array['server']['username'];
        $password=$ini_array['server']['password'];
        $dbname=$ini_array['server']['dbname'];


        $mysqli = new mysqli($servername, $username, $password, $dbname);

        if ($mysqli->connect_error)
        {
            die('Could not connect to database!');
        }

        $username = filter_var($_POST["link"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

        $statement = $mysqli->prepare("SELECT Link FROM CHAIN_OWNER WHERE Link=?");
        $statement->bind_param('s', $username);
        $statement->execute();
        $statement->bind_result($username);

        if($statement->fetch())
        {
            die("0"); //Not available
        }
        else
        {
            die("1"); //Available
        }
    }
?>