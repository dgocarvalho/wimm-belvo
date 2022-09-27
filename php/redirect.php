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
    
  // init configuration
  $clientID = $_ENV['GOOGLE_API_CLIENT_ID'];
  $clientSecret = $_ENV['GOOGLE_API_CLIENT_SECRET'];;
  $redirectUri = $_ENV['GOOGLE_URL_REDIRECT'];
    
  // create Client Request to access Google API
  $client = new Google_Client();
  $client->setClientId($clientID);
  $client->setClientSecret($clientSecret);
  $client->setRedirectUri($redirectUri);
  $client->addScope("email");
  $client->addScope("profile");
    
  // authenticate code from Google OAuth Flow
  if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);
    
    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email =  $google_account_info->email;
    $name =  $google_account_info->name;
    
    // now you can use this profile info to create account in your website and make user logged in.

    $db = new DatabaseClass ();
    $user = $db->get_user_by_email($email, $name);

    if (empty($user)) {

      if ($db->insert_new_user($email, $name, "1")) {
        $user = $db->get_user_by_email($email);
        header('location:../login-belvo.php?user_id=' . strval(urlencode(base64_encode($user[0]["user_id"]))));
      }

    } elseif (is_null($user[0]["user_link_id"])) {
      header('location:../login-belvo.php?user_id=' . strval(urlencode(base64_encode($user[0]["user_id"]))));

    } else {
      session_start();
                  
      $user = $db->get_user_by_id($user[0]["user_id"]);
          
      If (!empty($user)) {
          $_SESSION['link'] = $user[0]["user_link_id"];
          $_SESSION['institution'] = $user[0]["user_institution_name"];
          $_SESSION['last_accessed_at'] = date("Y-m-d H:i:s");
          $_SESSION['isLogged'] = TRUE;

          $_SESSION['user_name'] = $user[0]["user_name"];
          $_SESSION['user_id'] = $user[0]["user_id"];
          $_SESSION['user_email'] = $user[0]["user_email"];

          $_SESSION['firstAccess'] = TRUE;

          header('location:../index.php');
      } else {
        header('location:../login.php');
      }
    }
  }

?>