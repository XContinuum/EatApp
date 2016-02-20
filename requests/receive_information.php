<?php
require_once("access_db.php");

//get restautrant username if logged
function getChainLink() //get_restaurant_username
{
    $db=new Db();

    if(!isset($_SESSION))
        session_start();
        
    if (isset($_SESSION['chain_owner_token']))
    {
        $sql="SELECT Link FROM CHAIN_OWNER WHERE Token='".$_SESSION['chain_owner_token']."'";
        
        return $db->fetch($sql,"Link");
    }
    else
    {
        return -1; //not logged
    }
}

//get Chain ID if logged
function getChainId() //get_restaurant_id
{
     $db=new Db();
        
    if(!isset($_SESSION))
        session_start();
        
    if (isset($_SESSION['chain_owner_token']))
    {
        $sql="SELECT ID FROM CHAIN_OWNER WHERE Token='".$_SESSION['chain_owner_token']."'";
            
        return $db->fetch($sql,"ID");
    }
    else
    {
        return -1; //not logged
    }
}

function getMenuOwnerID($menu_name)
{ 
    $db=new Db();
        
    $sql="SELECT OWNER_ID FROM MENUS WHERE Name='$menu_name'";
    
    return $db->fetch($sql,"OWNER_ID");
}

/*
    fetch different infos about the chain based on the link
*/
function getInfo($link,$info)
{
    $db=new Db();
   
    $sql="SELECT ID,Email,Restaurant_Name,Hash,Picture,Website FROM CHAIN_OWNER WHERE Link='$link'";
    
    return $db->fetch($sql,$info);
}

//fetch different infos about the chain based on the id
function getInfoFromID($chain_id,$info)
{
    $db=new Db();
   
    $sql="SELECT ID,Email,Hash,Picture,Website,Link FROM CHAIN_OWNER WHERE ID='$chain_id'";
   
    return $db->fetch($sql,$info);
}


//check if username is in the database
function checkChainStatus($link_name)
{
    $db=new Db();
       
    $sql="SELECT Link FROM CHAIN_OWNER WHERE Link='$link_name'";
  
    return ($db->countRows($sql)>0) ? 1 : 0;
}
    
//set a global path for files that are in different directories
function setLink($string)
{
    $config=parse_ini_file("settings.ini");
    
    echo "'".$config['path'].$string."'";
}

function setLinkMute($string)
{
    $config=parse_ini_file("settings.ini");
    
    return "'".$config['path'].$string."'";
}

//Check if Chain validated
function checkIfValidated($link_name) //checkIfValidated($username_)
{
    $db=new Db();

    $sql="SELECT Validated FROM CHAIN_OWNER WHERE Link='$link_name'";
   
    return $db->fetch($sql,"Validated");
}

//Check if email validated
function checkIfEmailValidated($link_name)
{
    $db=new Db();
    
    $sql="SELECT Active FROM CHAIN_OWNER WHERE Link='$link_name'";

    return $db->fetch($sql,"Active");
}

//Check if user logged
function isOwnerLogged()//isUserLogged
{
    if(!isset($_SESSION))
        session_start();
        
    return isset($_SESSION['chain_owner_token']);
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
        
    return isset($_SESSION['admin_token']);
}

//Get admin username
function getAdminUsername()
{
    if(!isset($_SESSION))
        session_start();
        
    if (isset($_SESSION['admin_token']))
    {
        $db=new Db();
        
        $sql="SELECT Username FROM ADMIN_PANEL WHERE Token='".$_SESSION['admin_token']."'";
        
        return $db->fetch($sql,"Username"); //logged
    }
    else
    {
        return -1; //not logged
    }
}

//LogOut
function LogOut()
{  
    $db=new Db();

    if(!isset($_SESSION))
        session_start();

    if (isset($_SESSION['chain_owner_token']))
    {
        $sql="UPDATE CHAIN_OWNER SET Token='0' WHERE Token='" . $_SESSION['chain_owner_token'] . "'";
        $db->query($sql);
    
        session_unset(); //remove all session variables
        session_destroy(); //destroy the session
    }
    else
        if (isset($_SESSION['admin_token']))
        {
            $sql="UPDATE ADMIN_PANEL SET Token='0' WHERE Token='" . $_SESSION['admin_token'] . "'";
            $db->query($sql);
    
            session_unset(); //remove all session variables
            session_destroy(); //destroy the session
        }
}


//Get full global link to profile picture
function getPicLink($link_name)
{
    $src=getInfo($link_name,"Picture");
    
    return ($src=="default") ? setLinkMute("/images/default.png") : setLinkMute("/restaurant_data/Profile/".$src);
}

/*
    Panel
*/

function getRelativePath($from,$to)
{
    //some compatibility fixes for Windows paths
    $from=is_dir($from) ? rtrim($from, '\/') . '/' : $from;
    $to=is_dir($to)   ? rtrim($to, '\/') . '/'   : $to;
    $from=str_replace('\\', '/', $from);
    $to=str_replace('\\', '/', $to);

    $from=explode('/', $from);
    $to=explode('/', $to);
    $relPath=$to;

    foreach($from as $depth => $dir) 
    {
        // find first non-matching dir
        if($dir === $to[$depth]) 
        {
            //ignore this directory
            array_shift($relPath);
        } 
        else 
        {
            // get number of remaining dirs to $from
            $remaining = count($from) - $depth;
            if($remaining > 1) {
                // add traversals up to first matching dir
                $padLength = (count($relPath) + $remaining - 1) * -1;
                $relPath = array_pad($relPath, $padLength, '..');
                break;
            } 
            else 
            {
                $relPath[0] = './' . $relPath[0];
            }
        }
    }
    return implode('/', $relPath);
}


function setPanel()
{
    $relative=getRelativePath($_SERVER["PHP_SELF"],"/requests/panel_struct.html");
    $structure=file_get_contents($relative);
    $structure=explode("##", $structure);

    if (isOwnerLogged())
    {
        $LinkName=getChainLink();
        $image_src=getPicLink($LinkName);
            
        $search=array("%link_name%","%image_src%","%triangle%","%profile%","%settings%","%log_out%");
        $replace=array($LinkName,$image_src,setLinkMute("/images/triangle.png"),setLinkMute("/".$LinkName),setLinkMute("/settings/index.php"),setLinkMute("/requests/log_out_request.php"));
       
        $panel=str_replace($search,$replace,$structure[0]);
    }
    else
        if (isAdminLogged())
        {
            $search=array("%username%","%path_1%","%path_2%");
            $replace=array(getAdminUsername(),setLinkMute("/panel/admin/index.php"),setLinkMute("/requests/log_out_request.php"));

            $panel=str_replace($search,$replace,$structure[1]);
        }
        else
        {
            //Restaurant not logged
            $search=array("%path_1%","%path_2%");
            $replace=array(setLinkMute("/sign_up/index.php"),setLinkMute("/login/index.php"));

            $panel=str_replace($search,$replace,$structure[2]);
        }

    return $panel;
}


//Function to get the client IP address
function get_client_ip() 
{
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else 
        if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else 
        if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else 
        if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else 
        if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else 
        if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


function createQuery($data)
{
    return "('".implode("','",$data)."')";;
}


/*
    Save search in the database
*/
function saveSearch($query,$count)
{
    $db=new Db();

    $ip_address=get_client_ip();
    $chain_id=(getChainId()==-1) ? 'null' : getChainId();
    
    $sql="INSERT INTO SEARCHES (IP_Address, Input, Chain_id, Results_Found)";
    $sql.=" VALUES ('$ip_address','$query',$chain_id,'$count')";
    
    $db->query($sql);
}


function readableTime($time)
{
    $time=time()-$time; //to get the time since that moment
    $time=($time<1)? 1 : $time;
    $tokens=array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text)
    {
        if ($time < $unit) continue;
            
        $numberOfUnits= floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }
}

/*
    Get today's week day
*/
function getWeekDay()
{
    $jd=cal_to_jd(CAL_GREGORIAN,date("m"),date("d"),date("Y"));
    return jddayofweek($jd,1);
}



function getToken($item)
{
    $salt=rand(403,600) . "P20x" . rand(760,930);
    $string=time() . $salt . $item . $salt;
            
    return sha1($string);
}


function getCoordinates($address) //[0]=> Longitude, [1]=> Latitude
{
    $prepAddr=str_replace(' ','+',$address);
    $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
    $output=json_decode($geocode);

    $coordinates=array();
    $coordinates[]=$output->results[0]->geometry->location->lng;
    $coordinates[]=$output->results[0]->geometry->location->lat;

    return $coordinates;
}

?>