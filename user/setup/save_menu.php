<?php

require_once("../../requests/receive_information.php");
        
$db=new Db();

//DATA+++
$food_images=$_FILES["Food_Images"];
$crop_info=$_POST["Crop_Info"];
$picture_url=$_POST["Picture_URL"];
$product_name=$_POST["Product_name"];
$price=$_POST["Price"];
$description=$_POST["Description"];
$contents=$_POST["Food_Contents"];
        
$section_name=$_POST["Sections"];
$sections=explode(':', $_POST["section_index"]);
       
$currency=$_POST["currency"];
$menu_name=$_POST["menu_name"];
//DATA---

$final_query="";

$OWNER_ID=getChainId();

$intermediate_q=array(); //intermediate array

for ($i=0;$i<count($product_name);$i++)
{
    //Save images
    $new_name=saveImage($food_images,$i,$crop_info[$i],$menu_name);       
    $pic_name=($new_name=="none") ? $picture_url[$i] : $new_name;
    
    //Divide into sections+++
    $section="none";

    if (count($sections)>0 && count($section_name)>0)
    {
        $stop=0;
    
        for ($j=count($sections)-1;$j>=0;$j--)
        {   
            if ($i>=($sections[$j]) && $stop==0)
            {
                $section=$section_name[$j];
                $stop=1;
            }
        }
    }
    //Divide into sections---
                
    $filters="";
    
    if (isset($contents[$i]) && is_array($contents[$i]) || is_object($contents[$i]))
    {
        $filters=implode(".",$contents[$i]);
    }
    
    $data=array($menu_name,$OWNER_ID,$product_name[$i],$price[$i],$description[$i],$filters,$section,$pic_name,$currency);
    $intermediate_q[]=createQuery($data);
}
$final_query=implode(",", $intermediate_q);
$db->query("DELETE FROM MENUS WHERE Name='$menu_name'"); //DELETE PREVIOUS MENU

$sql="INSERT INTO MENUS (Name,OWNER_ID,Product_Name,Price,Description,Contents,Section,Picture,Currency) VALUES $final_query";
        
$db->query($sql);

cleanPictures($menu_name);

echo "Saved!";


function saveImage($upload_image,$entry,$crop_info,$menu_name)
{
    require_once('ImageManipulator.php');
    $img_name="none";

    if(!empty($upload_image['tmp_name'][$entry]))
    {
        $maxSize=1024*10; //10Mb
        $file_size=$upload_image['size'][$entry]/1024; //in Kb

        $validExtensions=array('.jpg', '.jpeg', '.gif', '.png'); //array of valid extensions
        $fileExtension=strrchr($upload_image['name'][$entry], "."); //get extension of the uploaded file


        //check if file Extension is on the list of allowed ones
        if ($file_size<$maxSize)
        {
            if (in_array($fileExtension, $validExtensions)) 
            {
                $imagename=$upload_image['name'][$entry]; //Stores the filename as it was on the client computer.
                $imagetype=$upload_image['type'][$entry]; //Stores the filetype e.g image/jpeg
                $imageerror=$upload_image['error'][$entry]; //Stores any error codes from the upload
                $imagetemp=$upload_image['tmp_name'][$entry]; //Stores the tempname as it is given by the host when uploaded

                $ext=pathinfo($imagename, PATHINFO_EXTENSION);

                //Make directory+++
                $link_name=getChainLink();
                $path="../../restaurant_data/Pictures/$link_name/$menu_name";

                if (!is_dir($path)) 
                {
                    mkdir($path);
                }

                $path=$path."/";
                //Make directiry---

           
                //To avoid collisions+++
                do 
                {
                    $random_image_name=uniqid();
                } 
                while(file_exists($path.$random_image_name.".".$ext));
                //To avoid collisions---

                if(is_uploaded_file($imagetemp))
                {
                    //Crop image+++
                    $info=explode(":", $crop_info);

                    $manipulator=new ImageManipulator($upload_image['tmp_name'][$entry]);
                    $x1=$info[0];
                    $y1=$info[1];
         
                    $x2=$info[0]+$info[2];
                    $y2=$info[1]+$info[3];
 
                    $img=$manipulator->resample($info[4],$info[5],true);
                    $newImage=$manipulator->crop($x1, $y1, $x2, $y2);
           
                    //saving file to profile folder
                    $manipulator->save($path.$random_image_name.".".$ext);
                    $img_name=$random_image_name.".".$ext;
                }
            }
        }
    }
        
    return $img_name;
}


function cleanPictures($menu_name)
{
    $db=new Db();
    $link_name=getChainLink();
    $path="../../restaurant_data/Pictures/$link_name/$menu_name";
    $files=array_diff(scandir($path), array('..', '.','.DS_Store')); 
    $files=array_values($files);

   
    $sql="SELECT Picture FROM MENUS WHERE Name='$menu_name'";
    $result=$db->query($sql);
    
    $db_images=array();

    while ($row = $result -> fetch_assoc()) 
    {
        $db_images[]=$row["Picture"];
    }
    
    $delete_files=array_merge(array_diff($db_images, $files), array_diff($files, $db_images)); //exclusion of the two arrays
    $delete_files=array_values($delete_files);

    
    for ($j=0;$j<count($delete_files);$j++)
    {
        if (file_exists($path."/".$delete_files[$j]))
        unlink($path."/".$delete_files[$j]); 
    }
}
?>