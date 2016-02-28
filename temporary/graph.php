<?php  

    //Set content-type header
    header("Content-type: image/png");

    //Include phpMyGraph5.0.php
    include_once('phpMyGraph5.0.php');
    require_once("../requests/receive_information.php");

   
    $db=new Db();
   
    $result=$db->query("SELECT * FROM PROJECT_STATS LIMIT 100");

    while ($row = $result -> fetch_assoc()) 
    {
        if (($timestamp = strtotime($row["Date"])) !== false)
        {
            $timestamp = strtotime($row["Date"]);
             $php_date = getdate($timestamp);
             $date = date("d", $timestamp);
            $data[$date]=$row["lines_total"];
        }

        $data_php[$date]=floatval($row["php_lines"]);
        $data_js[$date]=floatval($row["js_lines"]);
    }


    //Set config directives
    $cfg['title'] = 'Lines of code';
    $cfg['width'] = 1000;
    $cfg['height'] = 500;
    
    //Set data

    //Create phpMyGraph instance
    $graph = new phpMyGraph();
    $graph->parseVerticalPolygonGraph($data, $cfg);

    //$graph = new verticalLineGraph(); 
    //$graph->parseCompare($data_php, $data_js, $cfg); 
  
?>  