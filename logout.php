<?php
    session_start();

    $_SESSION['user_id'] = null;

    // remove all session variables
    session_unset(); 

    // destroy the session 
    session_destroy(); 

    //Redirect to the login page
    header('Location: login.php');
    //end of the script
    exit();
?>