<?php
require("../requests/hash_algorithm.php");
require("../requests/access_db.php");

$DB_Email=htmlspecialchars($_POST['Email']);
$DB_Password=htmlspecialchars($_POST['Password']);
    
$db=new Db();
$time=(isset($_POST["remember_me"])) ? 3600*24*365:3600; //set time of destruction

if ($DB_Password!="" && strlen($DB_Password)>=6)
{
    $chain_data=$db->select("SELECT Email, Password FROM CHAIN_OWNER WHERE Email='$DB_Email'");
    $admin_data=$db->select("SELECT Username, Password FROM ADMIN_PANEL WHERE Username='$DB_Email'");
    
    $pass=(count($chain_data)>0) ? $chain_data[0]["Password"] : "-";
    $a_pass=(count($admin_data)>0) ? $admin_data[0]["Password"] : "-";
    
    if (PassHash::check_password($pass, $DB_Password)) //Logged in!
    {
        $token=getToken($chain_data[0]["Email"]);
        saveSession($token,$time,"chain");
  
        $db->query("UPDATE CHAIN_OWNER SET Token='$token' WHERE Email='$DB_Email'");

        header("Location: ../index.php");
    }
    else 
        if (PassHash::check_password($a_pass, $DB_Password))
        {
            $token=getToken($admin_data[0]["Username"]);
            saveSession($token,$time,"admin");

            $db->query("UPDATE ADMIN_PANEL SET Token='$token' WHERE Username='$DB_Email'");
           
            header("Location: ../panel/admin/index.php");
        }
        else
            {
                header("Location: ../login/index.php?error=1");
            }
}
else
{
    header("Location: ../login/index.php?error=1");
}
        

function saveSession($token,$time,$type)
{
    if(!isset($_SESSION))
        session_start();

    if($type=="chain")
    {
        $_SESSION['chain_owner_token']=$token;
        $_SESSION['time']=$time;  
    }   
    else if ($type=="admin")
    {
        $_SESSION['admin_token']=$token;
        $_SESSION['time']=$time;
    }   
}

function getToken($item)
{
    $salt=rand(403,600) . "P20x" . rand(760,930);
    $string=time() . $salt . $item . $salt;
            
    return sha1($string);
}
?>