<?php

    /*
    * This file is part of WIMM project created as part of Belvo API test.
    *
    * @author (c) Diogo Carvalho <dgo.eng@gmail.com>
    * @version 1.0.0
    *
    * Redirect page the callback page to integrate google auth with the web app
    *
    *
    *
    *
    */

    require_once '../global.php';
    require_once 'DatabaseClass.php';

    try {
        if (isset($_POST['InputFullName']) && isset($_POST['InputEmail']) && isset($_POST['InputPassword'])) {
            
            $db = new DatabaseClass ();
            $email = $_POST['InputEmail'];
            $name = $_POST['InputFullName'];

            $user = $db->get_user_by_email($email);
        
            if (empty($user)) {

                $pwd_peppered = hash_hmac("sha256", $_POST['InputPassword'], $_ENV["PEPPER"]);
        
                if ($db->insert_new_user($email, $name, "1", $pwd_peppered)) {
                    $user = $db->get_user_by_email($email);
                    
                    header('location:../login-belvo.php?user_id=' . strval(urlencode(base64_encode($user[0]["user_id"]))));
                }
            }
            else {
                header('location:../register.php?error=duplicatedEmail');        
            }            
        }
        else {
            header('location:../register.php?error=formError');
        }

    } catch(Exception $ex) {
        echo "Error: " . $ex->getMessage();
    }


?>
