

<?php
    /*
    * This file is part of WIMM project created as part of Belvo API test.
    *
    * @author (c) Diogo Carvalho <dgo.eng@gmail.com>
    * @version 1.0.0
    *
    * Page responsable to set the stating configuration of belvo and 
    * get the token to start the widget
    *
    */

    require_once '../global.php';

    $request = new HTTP_Request2();

    //Setting parameters to get the Token
    $request->setUrl($_ENV['API_URL'] . '/api/token/');
    $request->setMethod(HTTP_Request2::METHOD_POST);
    
    $request->setConfig(array(
        'follow_redirects' => TRUE
    ));
    
    //Custom
    $request->addPostParameter(array(
        'id' => $_ENV['SECRET_ID'],
        'password' => $_ENV['SECRET_PASSWD'],
        'scopes' => 'read_institutions,write_links,read_links',
        'widget'=> ' {
            "branding": {
                "company_icon": "http://thecarvalho.com.br/wimm/src/img/WIMM-logo.svg",
                "company_logo": "http://thecarvalho.com.br/wimm/src/img/ATL-logo.svg",
                "company_name": "WIMM by ATL",
                "social_proof": true,
                "company_terms_url": "https://belvo.com/terms-service/",
                "company_benefit_header": "Smarter financial solution",
                "company_benefit_content": "Using WIMM you will have more control of your financial life.",
                "opportunity_loss": "Sometimes is complicated to follow the balance between different accounts banks."
            }
         }'
    ));

    try {
        $response = $request->send();
        if ($response->getStatus() == 200) {
            echo $response->getBody();
        }
        else {
            echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase();
        }
    }
    catch(HTTP_Request2_Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
?>