<?php

function getLines($file)
{
    $count=preg_split('/\n|\r/',file_get_contents($file));
    return count($count);
}


function DirLineCounter($dir , $result = array('lines_html' => false, 'files_count' => false, 'lines_count' => false ,'js_lines'=>false,'php_lines'=>false,'php_files'=>false,'js_files'=>false,'html_lines'=>false, 'html_files'=>false), $complete_table = true )
{
  $file_read=array('php', 'html', 'js', 'css');
  $dir_ignore=array("temporary","test");

  $scan_result=array_diff(scandir($dir), array('..', '.','.DS_Store'));
  $scan_result=array_values($scan_result);

  foreach ($scan_result as $key => $value) 
  {
      if (is_dir($dir.DIRECTORY_SEPARATOR.$value)) //Check if directory found
      { 
        if (in_array($value, $dir_ignore)) //if directory not ignored
        {
          continue;
        }      
        
        $result=DirLineCounter($dir.DIRECTORY_SEPARATOR.$value, $result, false);  //recurtion       
      }
      //When file is found
      else 
      {
        $type=explode('.', $value); 
        $type=array_reverse($type);
        
        if(!in_array($type[0], $file_read)) //if file type is accepted then continue
        {
          continue;
        }
              
        $lines=getLines($dir . DIRECTORY_SEPARATOR . $value); //get number of lines

        $dir_display=str_replace("../..","",$dir);

        $color="";

        switch($type[0])
        {
          case "js":
          $color="style='background-color:#b4caf0;'";
          $result['js_lines'] = $result['js_lines'] + $lines;
          $result['js_files'] = $result['js_files'] + 1;
          break;

         case "php":
          $color="style='background-color:#f0b4b5;'";
          $result['php_lines'] = $result['php_lines'] + $lines;
          $result['php_files'] = $result['php_files'] + 1;
          break;

          case "html":
          $color="style='background-color:#fcfdad;'";
          $result['html_lines'] = $result['html_lines'] + $lines;
          $result['html_files'] = $result['html_files'] + 1;
          break;
        }

        $result['lines_html'][] = '<tr '.$color.'><td>' . $dir_display . '</td><td><a target="blank" href="'.$dir.'/'.$value.'">' . $value . '</a></td><td>' . $lines . '</td></tr>'; 
        $result['lines_count'] = $result['lines_count'] + $lines;
        $result['files_count'] = $result['files_count'] + 1;                      
      }
    
  }
      
  if ($complete_table) 
  {
    $js_percent=round(($result['js_lines']/$result['lines_count'])*100, 2);
    $js_file_percent=round((($result['js_files']/$result['files_count'])*100),2);

    $php_percent=round(($result['php_lines']/$result['lines_count'])*100, 2);
    $php_file_percent=round((($result['php_files']/$result['files_count'])*100),2);

    $html_percent=round(($result['html_lines']/$result['lines_count'])*100, 2);
    $html_file_percent=round((($result['html_files']/$result['files_count'])*100),2);

    //+++
    $template="<tr><td colspan='2'>%cell1% (%percent1%%)</td><td>%cell2% (%percent2%%)</td></tr>";
    $search=array("%cell1%","%percent1%","%cell2%","%percent2%");

    $lines_html='<tr style="background-color:#f9f8f8;" ><td colspan="2">Files Total: ' . $result['files_count'] . '</td><td>Lines Total: ' . $result['lines_count'] . '</td></tr>';
    $lines_html.=str_replace($search,array("JS files: ".$result['js_files'],$js_file_percent,"JS lines: ".$result['js_lines'],$js_percent),$template);
    $lines_html.=str_replace($search,array("PHP files: ".$result['php_files'],$php_file_percent,"PHP lines: ".$result['php_lines'],$php_percent),$template);
    $lines_html.=str_replace($search,array("HTML files: ".$result['html_files'],$html_file_percent,"HTML lines: ".$result['html_lines'],$html_percent),$template);
    //+++
   
    $lines_html.="<tr style='background-color:#f9f8f8;'><td style='width:200px;'>Dir</td>";
    $lines_html.="<td style='width:200px;'>File</td>";
    $lines_html.="<td style='width:200px;'>Lines</td></tr>";
    $lines_html.=implode('', $result['lines_html']);
  
    return $lines_html; 
  }
  else 
  {
    return $result;
  }
}

?>