<?php
function connect()
{
	static $connection;

	if(!isset($connection)) 
	{
		$config=parse_ini_file("settings.ini");
		$connection=mysqli_connect($config['servername'],$config['username'], $config['password'], $config['dbname']);
	}

	if ($connection->connect_error) 
	{
		die("Connection failed: " . $connection->connect_error);
	}

	return $connection;
}
?>