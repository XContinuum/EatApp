<?php
    session_start();
    if (isset($_SESSION['token']))
    {
    require ("server_connection.php");

    $sql = "UPDATE FA_RESTORANTS SET FA_Token='0' WHERE FA_Token='" . $_SESSION['token'] . "'";
    mysqli_query($conn,$sql);


    // remove all session variables
    session_unset();

    // destroy the session
    session_destroy();
    }
    header("Location: ../index.php");
?>
