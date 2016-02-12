<?php
    //get restautrant username if logged
    function getChainLink() //get_restaurant_username
    {
        require("server_connection.php");
          
        if(!isset($_SESSION))
        session_start();
        
        if (isset($_SESSION['chain_owner_token']))
        {
            $sql="SELECT Link FROM CHAIN_OWNER WHERE Token='".$_SESSION['chain_owner_token']."'";
            $result=mysqli_query($conn,$sql);
            $row=mysqli_fetch_assoc($result);

            return strtolower($row['Link']);
        }
        else
        {
            return -1; //not logged
        }
    }

    //get Chain ID if logged
    function getChainId() //get_restaurant_id
    {
        require('server_connection.php');
        
        if(!isset($_SESSION))
        session_start();
        
        if (isset($_SESSION['chain_owner_token']))
        {
            $sql = "SELECT ID FROM CHAIN_OWNER WHERE Token='".$_SESSION['chain_owner_token']."'";
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($result);
        
            return $row['ID'];
        }
        else
        {
            return -1; //not logged
        }
    }

    function getMenuOwnerID($menu_name)
    { 
        require('server_connection.php');
        
        $sql="SELECT OWNER_ID FROM MENUS WHERE Name='$menu_name'";
        $result=mysqli_query($conn,$sql);
        $row=mysqli_fetch_assoc($result);
        $row_num=mysql_num_rows($result);
        
        if ($row_num==0)
        {
            return -1;
        }
        else
        {
            return $row['OWNER_ID'];
        }
    }

    //fetch different infos about the restaurant based on the username
    function getInfo($link_,$info)
    {
        require("server_connection.php");
  
        $sql = "SELECT ID,Email,Restaurant_Name,Hash,Picture,Website FROM CHAIN_OWNER WHERE Link='$link_'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        mysqli_close($conn);

        return $row[$info];
    }

     //fetch different infos about the chain based on the id
    function getInfoFromID($chain_id,$info)
    {
        require("server_connection.php");
  
        $sql = "SELECT ID,Email,Hash,Picture,Website,Link FROM CHAIN_OWNER WHERE ID='$chain_id'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
        mysqli_close($conn);

        return $row[$info];
    }


    //check if username is in the database
    function checkChainStatus($username_)
    {
        require("server_connection.php");
       
        $sql="SELECT Link FROM CHAIN_OWNER WHERE Link='$username_'";
        $result=mysqli_query($conn,$sql);
        
        if (mysqli_num_rows($result)>0)
        {
            return 1; //chain found
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

    //Check if Chain validated
    function checkIfValidated($username_)
    {
        require("server_connection.php");
       
        $sql="SELECT Validated FROM CHAIN_OWNER WHERE Link='$username_'";
        $result=mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
      
        mysqli_close($conn);
        
        return $row['Validated'];
    }

    //Check if email validated
    function checkIfEmailValidated($username_)
    {
        require("server_connection.php");
       
        $sql="SELECT Active FROM CHAIN_OWNER WHERE Link='$username_'";
        $result=mysqli_query($conn,$sql);
        $row = mysqli_fetch_assoc($result);
      
        mysqli_close($conn);
        
        return $row['Active'];
    }

    //Check if user logged
    function isOwnerLogged()//isUserLogged
    {
        if(!isset($_SESSION))
        session_start();
        
        if (isset($_SESSION['chain_owner_token']))
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
        $subject='Signup | Verification'; 
        $path="http://".$_SERVER['HTTP_HOST'];

        $content=file_get_contents("email_template.html");
        
        $search=array('%link_name%','%path%','%email_address%','%$hash%');
        $data=array($username,$path,$email_address,$hash);
        $message=str_replace($search,$data,$content);

        $headers='From:noreply@eatapp.ca'."\r\n";
        mail($email_address, $subject, $message, $headers);
    }

    //Check if admin logged
     function isAdminLogged()
     {
        if(!isset($_SESSION))
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
        if(!isset($_SESSION))
        session_start();
        
        if (isset($_SESSION['admin_token']))
        {
            require("server_connection.php");
        
            $sql = "SELECT Username FROM ADMIN_PANEL WHERE Token='".$_SESSION['admin_token']."'";
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($result);

            return $row['Username']; //logged
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
    
    if(!isset($_SESSION))
    session_start();

    if (isset($_SESSION['chain_owner_token']))
    {
        $sql = "UPDATE CHAIN_OWNER SET Token='0' WHERE Token='" . $_SESSION['chain_owner_token'] . "'";
        mysqli_query($conn,$sql);
    
        session_unset(); //remove all session variables
        session_destroy(); //destroy the session
    }
    else
        if (isset($_SESSION['admin_token']))
        {
            $sql = "UPDATE ADMIN_PANEL SET Token='0' WHERE Token='" . $_SESSION['admin_token'] . "'";
            mysqli_query($conn,$sql);
    
            session_unset(); //remove all session variables
            session_destroy(); //destroy the session
        }
    }


    //Get full global link to profile picture
    function getPicLink($username_)
    {
        $src=getInfo($username_,"Picture");
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
        if (isOwnerLogged()==1)
        {
            //Restaurant logged
            $LinkName=getChainLink();
            
            $image_src=getPicLink($LinkName);
    
            $panel="<div id='user_top_panel'>";
            $panel.="<div id='username_bar'><a href=".setLinkMute("/".$LinkName).">".$LinkName."</a></div>";
            $panel.="<div id='profile_picture'><img src=$image_src id='image_circle'/></div>";
            $panel.="</div>";

            $panel.="<div id='drop_down_panel'>";
            $panel.="<div style='margin-left:136px;width:8px;' align='right'><img style='display:block;' src=".setLinkMute("/images/triangle.png")." /></div>";

            $panel.="<div style='background-color:white;'>";
            $panel.="<a href=".setLinkMute("/".$LinkName).">";
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
    function get_client_ip() 
    {
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


    function createQuery($data)
    {
        $query="";

        for ($i=0;$i<count($data);$i++)
        {
            $data[$i]="'".$data[$i]."'";
        }
        $query=implode(",",$data);
        $query="($query)";

        return $query;
    }
?>