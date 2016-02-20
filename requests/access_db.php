<?php
class Db 
{
    protected static $connection;

    /**
     * Connect to the database
     */

    public function connect() 
    {    
        // Try and connect to the database
        if(!isset(self::$connection)) 
        {
            //Load configuration as an array. Use the actual location of your configuration file
            $config = parse_ini_file('settings.ini'); 
            self::$connection = new mysqli($config['servername'],$config['username'],$config['password'],$config['dbname']);
        }

        //If connection was not successful, handle the error
        if(self::$connection === false) 
        {
            //Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
        }
        return self::$connection;
    }

    /**
     * Query the database
     */

    public function query($query) 
    {
        //Connect to the database
        $connection=$this->connect();

        //Query the database
        $result=$connection->query($query);

        return $result;
    }



    /**
     * Fetch rows from the database (SELECT query)
     */

    public function select($query) 
    {
        $rows = array();
        $result = $this -> query($query);
        
        if($result === false) 
        {
            echo "err";
            return false;
        }

        while ($row = $result -> fetch_assoc()) 
        {
            $rows[] = $row;
        }
        return $rows;
    }


    public function fetch($query,$info)
    {
        $result=$this->select($query);
        return (count($result)>0) ? $result[0][$info] : -1;
    }

    public function countRows($query)
    {
         $result=$this->query($query);
         return $result->num_rows;
    }

    /**
     * Fetch the last error from the database
     */
    public function error() 
    {
        $connection = $this -> connect();
        return $connection -> error;
    }

    /**
     * Quote and escape value for use in a database query
     */
    public function quote($value) 
    {
        $connection=$this -> connect();
        return "'" . $connection -> real_escape_string($value) . "'";
    }
}

?>