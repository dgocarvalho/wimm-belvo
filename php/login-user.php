<?php 

    /*
    * This file is part of WIMM project created as part of Belvo API test.
    *
    * @author (c) Diogo Carvalho <dgo.eng@gmail.com>
    * @version 1.0.0
    *
    */

    require_once '../global.php';
    require_once 'DatabaseClass.php';

    if (isset($_POST['InputEmail']) && isset($_POST['InputPassword'])) {
        
        $db = new DatabaseClass ();
        $email = $_POST['InputEmail'];


        echo $email;
        $user = $db->get_user_by_email($email);
    
        var_dump($user);

        if (empty($user)) {
            header('location:../login.php?error=userNotFound');   
        } else {

            $pwd_peppered = hash_hmac("sha256", $_POST['InputPassword'], $_ENV["PEPPER"]);

            echo $_POST['InputPassword']. "<br>";
            echo $user[0]["user_password"] . "<br>";
            echo $pwd_peppered . "<br>";

            if ($user[0]["user_password"] == $pwd_peppered) {

                if ($user[0]["user_link_id"] == '' || is_null($user[0]["user_link_id"])) {
                    header('location:../login-belvo.php?user_id=' . strval(urlencode(base64_encode($user[0]["user_id"]))));

                } else {
                    session_start();                  

                    $_SESSION['link'] = $user[0]["user_link_id"];
                    $_SESSION['institution'] = $user[0]["user_institution_name"];
                    $_SESSION['last_accessed_at'] = date("Y-m-d H:i:s");
                    $_SESSION['isLogged'] = TRUE;
            
                    $_SESSION['user_name'] = $user[0]["user_name"];
                    $_SESSION['user_id'] = $user[0]["user_id"];
                    $_SESSION['user_email'] = $user[0]["user_email"];
            
                    $_SESSION['firstAccess'] = TRUE;
                    
                    header('location:../index.php');
                }                  
            } else {
                header('location:../login.php?error=accessDenied');
            }
        } 

    }
    else {
        header('location:../login.php?error=formError');
    }


?>
