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
      else if (isset($_SESSION['admin_token']))
      {
          $sql = "UPDATE FA_ADMIN_PANEL SET FA_Token='0' WHERE FA_Token='" . $_SESSION['admin_token'] . "'";
          mysqli_query($conn,$sql);

          session_unset(); //remove all session variables
          session_destroy(); //destroy the session
      }
    }


    //Get full global link to profile picture
    function getPicLink($username_)
    {
        $src=getInfo($username_,"FA_Pic");
        $image_link=setLinkMute("/restaurant_data/Profile/".$src);

        if ($src=="none" || $src==0)
        {
            $image_link=setLinkMute("/images/default.png");
        }

        return $image_link;
    }

    //Panel
    function setPanel()
    {
        if (isUserLogged()==1)
        {
            //Restaurant logged
            $username=get_restaurant_username();

            $image_src=getPicLink($username);

            $panel="<div id='user_top_panel'>";
            $panel.="<div id='username_bar'><a href=".setLinkMute("/".$username).">".$username."</a></div>";
            $panel.="<div id='profile_picture'><img src=$image_src id='image_circle'/></div>";
            $panel.="</div>";

            $panel.="<div id='drop_down_panel'>";
            $panel.="<div style='margin-left:136px;width:8px;' align='right'><img style='display:block;' src=".setLinkMute("/images/triangle.png")." /></div>";

            $panel.="<div style='background-color:white;'>";
            $panel.="<a href=".setLinkMute("/".$username).">";
            $panel.="<div class='drop_down_items'>profile</div></a>";
            $panel.="<a href=".setLinkMute("/settings/index.php").">";
            $panel.="<div class='drop_down_items'>settings</div></a>";
            $panel.="<a href=".setLinkMute("/requests/log_out_request.php").">";
            $panel.="<div class='drop_down_items'>log out</div></a>";
            $panel.="</div></div>";
        }
        else
            if (isAdminLogged()==1)
            {
                $username=getAdminUsername();

                $panel="<div id='top_sign_in'>";
                $panel.="<div class='abc'><a href=".setLinkMute("/panel/admin/index.php").">".$username."</a></div>";
                $panel.="<div class='abc'><a href=".setLinkMute("requests/log_out_request.php").">Logout</a></div>";
                $panel.="</div>";
            }
            else
            {
                //Restaurant not logged
                $panel="<div id='top_sign_in'>";
                $panel.="<div class='abc'><a href=".setLinkMute("/sign_up/index.php").">Sign up</a></div>";
                $panel.="<div class='abc'><a href=".setLinkMute("/login/index.php").">Login</a></div>";
                $panel.="</div>";
             }


        return $panel;
    }


    // Function to get the client IP address
    function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
    }
?>
