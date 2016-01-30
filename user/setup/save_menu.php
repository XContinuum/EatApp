<?php

require("../../requests/server_connection.php");
require("../../requests/receive_information.php");

//DATA+++
$food_images=$_POST["Food_Images"];
$crop_info=$_POST["Crop_info"];
$picture_url=$_POST["Picture_URL"];
$product_name=$_POST["Product_name"];
$price=$_POST["Price"];
$description=$_POST["Description"];
$contents=$_POST["Food_Contents"];

$section_name=$_POST["Sections"];
$sections=explode(':', $_POST["section_index"]);

$currency=$_POST["currency"];
//DATA---

$final_query="";

$menu_name=$_POST["menu_name"];
$OWNER_ID=getChainId();


for ($i=0;$i<count($product_name);$i++)
{
    $pic_name="none";
    //Save images
    /*$pic_name="none";
    $pic_name=saveImage($_FILES['food_images_'.($i+1)],$_POST['crop_info_'.($i+1)]);

    if ($pic_name=="none")
    {
        $pic_name=$_POST["picture_url_".($i+1)];
    }
    else
    {
    //Delete picture
        if ($_POST["picture_url_".($i+1)]!="none")
        {
            $old_file=$_POST["picture_url_".($i+1)];

            if (file_exists("../restaurant_data/Pictures/$res_username/$old_file"))
            {
                unlink("../restaurant_data/Pictures/$res_username/$old_file");
            }
        }
    }*/

    //Divide into sections+++
    $section="none";

    if (count($sections)>0)
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

    if ($i!=0)
        $final_query.=", ";

    $filters="";

    if (isset($contents[$i]) && is_array($contents[$i]) || is_object($contents[$i]))
    {
        $filters=implode(".",$contents[$i]);
    }

    $final_query.="('$menu_name','$OWNER_ID','".$product_name[$i]."','".$price[$i]."','".$description[$i]."','".$filters."','".$section."','".$pic_name."','".$currency."')";
}

mysqli_query($conn,"DELETE FROM MENUS WHERE Name='$menu_name'"); //DELETE PREVIOUS MENU

$sql="INSERT INTO MENUS (Name,OWNER_ID,Product_Name,Price,Description,Contents,Section,Picture,Currency) VALUES $final_query";

mysqli_query($conn,$sql);
mysqli_close($conn);

echo "Saved!";


function saveImage($upload_image,$crop_info)
{
        require_once('ImageManipulator.php');

        $img_name="none";

        if(!empty($upload_image['tmp_name']))
         {
            $maxSize=1024*10; //10Mb
            $file_size=$upload_image['size']/1024; //in Kb

            $validExtensions = array('.jpg', '.jpeg', '.gif', '.png'); //array of valid extensions
            $fileExtension = strrchr($upload_image['name'], "."); //get extension of the uploaded file

            //check if file Extension is on the list of allowed ones
            if ($file_size<$maxSize)
            {
                if (in_array($fileExtension, $validExtensions))
                {
                    $imagename=$upload_image['name']; //Stores the filename as it was on the client computer.
                    $imagetype=$upload_image['type']; //Stores the filetype e.g image/jpeg
                    $imageerror=$upload_image['error']; //Stores any error codes from the upload
                    $imagetemp=$upload_image['tmp_name']; //Stores the tempname as it is given by the host when uploaded

                    $ext=pathinfo($imagename, PATHINFO_EXTENSION);

                    //Make directory+++
                    $username=get_restaurant_username();
                    $path="../restaurant_data/Pictures/".$username;

                    if (!is_dir($path))
                    {
                        mkdir($path);
                    }

                    $path="../restaurant_data/Pictures/".$username."/";
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

                        $manipulator=new ImageManipulator($upload_image['tmp_name']);
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
?>
