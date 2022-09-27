<?php
    /*
    * This file is part of WIMM project created as part of Belvo API test.
    *
    * @author (c) Diogo Carvalho <dgo.eng@gmail.com>
    * @version 1.0.0
    *
    * PHP Code to Logout and end the session
    *
    *
    *
    *
    */
    session_start();
    
    unset($_SESSION['link']);
    unset($_SESSION['institution']);
    unset($_SESSION['last_accessed_at']);
    unset($_SESSION['isLogged']);

    unset($_SESSION['user_name']);
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);

    unset($_SESSION['firstAccess']);
    
    header('location:../login.php');
  
?>