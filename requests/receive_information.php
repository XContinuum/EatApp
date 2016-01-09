<?php
    //get restautrant username if logged
    function get_restaurant_username()
    {
        require("server_connection.php");
          
        session_start();
        
        $sql = "SELECT FA_Username FROM FA_RESTORANTS WHERE FA_Token='".$_SESSION['token']."'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        
        if (isset($_SESSION['token']))
        {
            return strtolower($row['FA_Username']);
        }
        else
        {
            return -1; //not logged
        }
    }

    //get restaurant's ID if logged
    function get_restaurant_id()
    {
        require('server_connection.php');
        
        session_start();
        $sql = "SELECT ID FROM FA_RESTORANTS WHERE FA_Token='".$_SESSION['token']."'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        
        if (isset($_SESSION['token']))
        {
            return $row['ID'];
        }
        else
        {
            return -1; //not logged
        }
    }

    //fetch different infos about the restaurant based on the username
    function getInfo($username_,$info)
    {
        require("server_connection.php");
  
        $sql = "SELECT ID,FA_Restaurant_Name,FA_Postal_Code,FA_Address,FA_Email,FA_Hash,FA_Pic,FA_Phone_Number,FA_Website FROM FA_RESTORANTS WHERE FA_Username='$username_'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        mysqli_close($conn);

        return $row[$info];
    }

     //fetch different infos about the restaurant based on the id
    function getInfoFromID($restaurant_id,$info)
    {
        require("server_connection.php");
  
        $sql = "SELECT FA_Username,FA_Restaurant_Name,FA_Postal_Code,FA_Address FROM FA_RESTORANTS WHERE ID='$restaurant_id'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        mysqli_close($conn);

        return $row[$info];
    }


    //check if username is in the database
    function checkUsernameStatus($username_)
    {
        require("server_connection.php");
       
        $sql="SELECT FA_Username FROM FA_RESTORANTS WHERE FA_Username='$username_'";
        $result=mysqli_query($conn,$sql);
        
        if (mysqli_num_rows($result)>0)
        {
            return 1; //user found
        }
        else
        {
            return 0; //no user
        }
       
        mysqli_close($conn);
    }
    
    //set a global path for files that are in different directories
    function setLink($string)
    {
        $ini_array=parse_ini_file("settings.ini", true);
    
        $path=$ini_array['server']['path'];
        echo "'".$path.$string."'";
    }

    function setLinkMute($string)
    {
        $ini_array=parse_ini_file("settings.ini", true);
    
        $path=$ini_array['server']['path'];
        return "'".$path.$string."'";
    }

    //Check if user validated
    function checkIfValidated($username_)
    {
        require("server_connection.php");
       
        $sql="SELECT FA_Validated FROM FA_RESTORANTS WHERE FA_Username='$username_'";
        $result=mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
      
        mysqli_close($conn);
        
        return $row['FA_Validated'];
    }

    //Check if email validated
    function checkIfEmailValidated($username_)
    {
        require("server_connection.php");
       
        $sql="SELECT FA_Active FROM FA_RESTORANTS WHERE FA_Username='$username_'";
        $result=mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
      
        mysqli_close($conn);
        
        return $row['FA_Active'];
    }

    //Check if user logged
    function isUserLogged()
    {
        session_start();
        
        if (isset($_SESSION['token']))
        {
            return 1; //restaurant user logged
        }
        else
        {
            return 0; //restaurant not logged
        }
    }

    //Send an email
    function sendEmail($email_address,$username,$hash)
    {
        $ini_array=parse_ini_file("settings.ini", true);
   
        $to=$email_address; // Send email to our user
        $subject='Signup | Verification'; // Give the email a subject 
        $path=$ini_array['server']['path'];

        $message = '
 
        Thanks for signing up!
        Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
 
        ------------------------
        Username: '.$username.'
        ------------------------
 
        Please click this link to activate your account:
        '.$path.'verify.php?email='.$email_address.'&hash='.$hash.'
 
        ';

        $headers='From:noreply@eatapp.ca'."\r\n"; // Set from headers
        mail($to, $subject, $message, $headers); // Send our email
    }

    //Check if admin logged
     function isAdminLogged()
     {
        session_start();
        
        if (isset($_SESSION['admin_token']))
        {
            return 1; //logged
        }
        else
        {
            return 0; //not logged
        }
    }

    //Get admin username
    function getAdminUsername()
    {
        session_start();
        
        if (isset($_SESSION['admin_token']))
        {
            require("server_connection.php");
        
            $sql = "SELECT FA_Username FROM FA_ADMIN_PANEL WHERE FA_Token='".$_SESSION['admin_token']."'";
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($result);

            return $row['FA_Username']; //logged
        }
        else
        {
            return -1; //not logged
        }
    }

    //LogOut
    function LogOut()
    {  
    require ("server_connection.php");
    
    session_start();

    if (isset($_SESSION['token']))
    {
        $sql = "UPDATE FA_RESTORANTS SET FA_Token='0' WHERE FA_Token='" . $_SESSION['token'] . "'";
        mysqli_query($conn,$sql);
    
        session_unset(); //remove all session variables
        session_destroy(); //destroy the session
    }
    else
        if (isset($_SESSION['admin_token']))
        {
            $sql = "UPDATE FA_ADMIN_PANEL SET FA_Token='0' WHERE FA_Token='" . $_SESSION['admin_token'] . "'";
            mysqli_query($conn,$sql);
    
            session_unset(); //remove all session variables
            session_destroy(); //destroy the session
        }
    }
    ?>