<?php
    // Start the session
    session_start();

    //Include useful functions
    include_once 'tools/useful_fonctions.php';

    /**
     * If user is not connected we open the login page 
     * else we open the charging entries
     */
    if(!user_logged()){
        header('Location: login.php');
    }else{
        header('Location: charging.php');
    }
?>
