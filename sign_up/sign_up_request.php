<?php
if (isset($_POST["sign_up"]))
{
    require_once("../requests/hash_algorithm.php");
    require_once("../requests/receive_information.php");
    
    $db=new Db();

    //Collect data
    $DB_Email=$_POST['DB_Email'];
    $DB_Link_Name=strtolower($_POST['DB_Link_Name']);
    $DB_Password=$_POST['DB_Password'];
    $DB_Confirm_Password=$_POST['DB_Confirm_Password'];
    $DB_Restaurant_Name=$_POST['DB_Restaurant_Name'];
    
    $DB_Website=$_POST['DB_Website'];

    $DB_Hash=md5(rand(0,1000));
    
    $register=checkValidity($DB_Password,$DB_Confirm_Password);
  
    if ($register)
    {
        $picture_name=saveImage();
        $password_hash=PassHash::hash($DB_Password);
     
        $sql="INSERT INTO CHAIN_OWNER (Email,Password,Link,Restaurant_Name,Website,Picture,Hash) ";
        $sql.="VALUES ('$DB_Email','$password_hash','$DB_Link_Name','$DB_Restaurant_Name','$DB_Website','$picture_name','$DB_Hash')";
       
        if ($db->query($sql)) 
        {
            $token=getToken($DB_Email);
            $time=(isset($_POST["remember_me"])) ? 3600*24*365:3600; //set time of destruction

            $db->query("UPDATE CHAIN_OWNER SET Token='$token' WHERE Email='$DB_Email'");
        
            if(!isset($_SESSION))
            session_start();
        
            $_SESSION['chain_owner_token']=$token;
            $_SESSION['time']=$time;
        
            sendEmail($DB_Email,$DB_Link_Name,$Hash);
            header("Location: ../user/setup/index.php");
        }
        else
            {
                /* Connection failed */
                header("Location: ../sign_up/index.php?connection=failed");
            }
    }
    else
    {
        /* Invalid input */
        header("Location: ../sign_up/index.php");
    }
    
}
else
    {
        /* Calling script without the sign up */
        header("Location: ../sign_up/index.php");     
    }


function checkValidity($password,$conf_password)
{
    return ($password==$conf_password && strlen($password)>=6) ? true : false;
}
    

function saveImage()
{
    require_once('ImageManipulator.php');
    $p_name="default";
    $maxSize=1024; //1Mb

    if ($_FILES['imageToUpload']['error'] > 0) 
    {
        /*
            An error has occured/ No picture
            echo "Error: " . $_FILES['imageToUpload']['error'];
        */
    } 
    else 
        {
            $file_size=($_FILES['imageToUpload']['size']/1024); //in Kb

            $validExtensions = array('.jpg', '.jpeg', '.gif', '.png'); //array of valid extensions
            $fileExtension = strrchr($_FILES['imageToUpload']['name'], "."); //get extension of the uploaded file
            $path="../restaurant_data/Profile/";

            //check if file Extension is on the list of allowed ones
            if ($file_size<$maxSize)
            {
            if (in_array($fileExtension, $validExtensions)) 
            {
                $info=explode(":",$_POST['crop_info']);

                $manipulator=new ImageManipulator($_FILES['imageToUpload']['tmp_name']);
                $x1=$info[0];
                $y1=$info[1];
 
                $x2=$info[0]+$info[2];
                $y2=$info[1]+$info[3];
 
                $img=$manipulator->resample($info[4],$info[5],true);
                $newImage=$manipulator->crop($x1, $y1, $x2, $y2);

                //To avoid collisions+++
                do 
                {
                    $random_image_name=uniqid();
                } 
                while(file_exists($path.$random_image_name.$fileExtension));
                //To avoid collisions---
                
                //saving file to profile folder
                $manipulator->save($path.$random_image_name.$fileExtension);
                $p_name=$random_image_name.$fileExtension;
            } 
            else 
            {
                echo 'You must upload an image...';
            }
        }
        else
        {
            echo "file too big";
        }
        }

        return $p_name;
    }    

?>
