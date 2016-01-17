<?php
    if (isset($_POST["sign_up_button"]))
    {
    require("hash_algorithm.php");
    require("server_connection.php");
    require("receive_information.php");

    //Collect data
    $FA_Email=$_POST['FA_Email'];
    $FA_Username=$_POST['FA_Username'];
    $FA_Password=$_POST['FA_Password'];
    $FA_Confirm_Password=$_POST['FA_Confirm_Password'];
    $FA_Restaurant_Name=$_POST['FA_Restaurant_Name'];

    $FA_Country=$_POST['FA_Country'];
    $FA_State_Province=$_POST['FA_State_Province'];
    $FA_City=$_POST['FA_City'];
    $FA_Address=$_POST['FA_Address'];
    $FA_Postal_Code=$_POST['FA_Postal_Code'];


    $FA_Phone_Number=$_POST['FA_Phone_number'];
    $FA_Website=$_POST['FA_Website'];


    $schedule=[];
    $schedule[]=$_POST['monday_start'].":".$_POST['monday_end'];
    $schedule[]=$_POST['tuesday_start'].":".$_POST['tuesday_end'];
    $schedule[]=$_POST['wednesday_start'].":".$_POST['wednesday_end'];
    $schedule[]=$_POST['thursday_start'].":".$_POST['thursday_end'];
    $schedule[]=$_POST['friday_start'].":".$_POST['friday_end'];
    $schedule[]=$_POST['saturday_start'].":".$_POST['saturday_end'];
    $schedule[]=$_POST['sunday_start'].":".$_POST['sunday_end'];


    $open=array($_POST['monday_open'],$_POST['tuesday_open'],$_POST['wednesday_open'],$_POST['thursday_open'],$_POST['friday_open'],$_POST['saturday_open'],$_POST['sunday_open']);


    if ($_POST["always_open"]==false)
    {
    for ($i=0;$i<count($open);$i++)
    {
        if ($open[$i]==false)
            $schedule[$i]="none";
    }
    }
    else
    {
        for ($j=0;$j<count($schedule);$j++)
        {
            $schedule[$j]="open";
        }
    }

    $FA_Hash = md5(rand(0,1000));
    $FA_Username=strtolower($FA_Username);


    $register=checkValidity($FA_Password,$FA_Confirm_Password);

    //Coordinates+++
    $coord=array();
    $coord=getCoordinates($FA_Postal_Code);


    if ($register==true)
    {
        $picture_name=saveImage();

        $password_hash=PassHash::hash($FA_Password);

        $sql="INSERT INTO FA_RESTORANTS (FA_Email,FA_Pass,FA_Username,FA_Restaurant_Name, FA_Country,FA_State_Province,FA_City,FA_Address,FA_Postal_Code,FA_Hash,";
        $sql.="FA_Phone_Number, FA_Website, Schedule_Monday, Schedule_Tuesday,Schedule_Wednesday,Schedule_Thursday,Schedule_Friday,Schedule_Saturday,Schedule_Sunday,FA_Pic,Longitude,Latitude) ";
        $sql.="VALUES ('$FA_Email','$password_hash','$FA_Username','$FA_Restaurant_Name','$FA_Country','$FA_State_Province','$FA_City','$FA_Address','$FA_Postal_Code','$FA_Hash',";
        $sql.="'$FA_Phone_Number','$FA_Website','$schedule[0]','$schedule[1]','$schedule[2]','$schedule[3]','$schedule[4]','$schedule[5]','$schedule[6]','$picture_name','$coord[0]','$coord[1]')";

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

            $sql = "UPDATE FA_RESTORANTS SET FA_Token='$token' WHERE FA_Email='$FA_Email'";
            mysqli_query($conn,$sql);

            session_start();

            $_SESSION['token']=$token;
            $_SESSION['time']=$t;

            sendEmail($FA_Email,$FA_Username,$FA_Hash);
            header("Location: ../user/setup_menu.php?verify=1");
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



    function getCoordinates($address)
    {
      $prepAddr = str_replace(' ','+',$address);
      $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
      $output= json_decode($geocode);
      $latitude = $output->results[0]->geometry->location->lat;
      $longitude = $output->results[0]->geometry->location->lng;

      $coordinates=array();
      $coordinates[]=$longitude;
      $coordinates[]=$latitude;

      return $coordinates;
    }
?>
