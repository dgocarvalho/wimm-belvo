<?php
    
    /*
    * This file is part of WIMM project created as part of Belvo API test.
    *
    * @author (c) Diogo Carvalho <dgo.eng@gmail.com>
    * @version 1.0.0
    *
    * Finishing the login process setting the session and register or 
    *   update the user info after authentication
    *
    *
    *
    */

    require_once '../global.php';
    require_once 'DatabaseClass.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $link = $_POST['link'];
        $institution = $_POST['institution'];
        $user_id = base64_decode(urldecode($_POST['user_id']));

        if ( empty($link) || empty($institution) ) {
            $data = ['status' => 'error', 'data' => 'link and/or institution not defined'];
            
            unset($_SESSION['link']);
            unset($_SESSION['institution']);
            unset($_SESSION['last_accessed_at']);
            unset($_SESSION['isLogged']);
            unset($_SESSION['authentication_key']);
            unset($_SESSION['firstAccess']);

        } else {

            session_start();
            $db = new DatabaseClass ();

            if (!is_null($user_id) && $db->update_user_link_id($user_id, $link) ) {
            
                $user = $db->get_user_by_id($user_id);
                
                If (!empty($user)) {
                    $_SESSION['link'] = $link;
                    $_SESSION['institution'] = $institution;
                    $_SESSION['last_accessed_at'] = date("Y-m-d H:i:s");
                    $_SESSION['isLogged'] = TRUE;
    
                    $_SESSION['user_name'] = $user[0]["user_name"];
                    $_SESSION['user_id'] = $user[0]["user_id"];
                    $_SESSION['user_email'] = $user[0]["user_email"];
                    $_SESSION['firstAccess'] = TRUE;

                    $data = ['status' => 'success', 'data' => $link];
                    echo json_encode($data);

                } else {
                    $data = ['status' => 'user_not_found', 'data' => "link = $link - user_id = $user_id"];
                    echo json_encode($data);
                }
    
            } else {
                $data = ['status' => 'user_error', 'data' => "link = $link - user_id = $user_id"];
                echo json_encode($data);    

            }
        }
    }
?>