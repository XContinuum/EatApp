<?php
    if (isset($_POST["sign_up_button"]))
    {
    require("hash_algorithm.php");
    require("server_connection.php");
    require("receive_information.php");

    //Collect data
    $DB_Email=$_POST['DB_Email'];
    $DB_Link_Name=$_POST['DB_Link_Name'];
    $DB_Password=$_POST['DB_Password'];
    $DB_Confirm_Password=$_POST['DB_Confirm_Password'];
    $DB_Restaurant_Name=$_POST['DB_Restaurant_Name'];

    $DB_Website=$_POST['DB_Website'];

    $DB_Hash = md5(rand(0,1000));
    $DB_Link_Name=strtolower($DB_Link_Name);


    $register=checkValidity($DB_Password,$DB_Confirm_Password);


    if ($register==true)
    {
        $picture_name=saveImage();

        $password_hash=PassHash::hash($FA_Password);

        $sql="INSERT INTO CHAIN_OWNER (Email,Password,Link,Restaurant_Name,Website,Picture,Hash) ";
        $sql.="VALUES ('$DB_Email','$password_hash','$DB_Link_Name','$DB_Restaurant_Name','$DB_Website','$picture_name','$DB_Hash')";

        if (mysqli_query($conn, $sql))
        {
            //Logged in!
            $salt=rand(403,600)."P20x".rand(760,930);
            $string=time().$salt.$FA_Email.$salt;
            $token=sha1($string);

            $t=3600; //set time of destruction

            if (isset($_POST["remember_me"]))
            {
                $t=3600*24*365; //set time of destruction
            }

            $sql = "UPDATE CHAIN_OWNER SET Token='$token' WHERE Email='$DB_Email'";
            mysqli_query($conn,$sql);

            session_start();

            $_SESSION['chain_owner_token']=$token;
            $_SESSION['time']=$t;

            sendEmail($DB_Email,$DB_Link_Name,$Hash);
            header("Location: ../user/setup/index.php");
            //header("Location: ../user/setup_menu.php?verify=1");
        }
        else
            {
                //error
                header("Location: ../sign_up/index.php?connection=failed");
            }
    }


        if ($register==false)
        {
            header("Location: ../sign_up/index.php"); //error
        }

        mysqli_close($conn);
    }
    else
    {
        header("Location: ../sign_up/index.php");
    }


    function checkValidity($password,$conf_password)
    {
        $valid=true;

        if ($password!=$conf_password)
        {
            $valid=false; //Passwords are not the same
        }
        else
            if (strlen($password)<6)
            {
                $valid=false; //Password has to be at least 6 characters
            }

        return $valid;
    }


function saveImage()
{
    require_once('ImageManipulator.php');
    $p_name="default";
    $maxSize=1024; //1Mb

    if ($_FILES['imageToUpload']['error'] > 0)
    {
        //No pictures
        //echo "Error: " . $_FILES['imageToUpload']['error'] . "<br />";
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
