

<?php
    /*
    * This file is part of WIMM project created as part of Belvo API test.
    *
    * @author (c) Diogo Carvalho <dgo.eng@gmail.com>
    * @version 1.0.0
    *
    */
    
    require_once 'vendor/autoload.php';
    include("php/BelvoAPIClass.php");

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $belvoAPI = new BelvoAPI($_ENV['SECRET_ID'], $_ENV['SECRET_PASSWD'], $_ENV['API_URL']);
?>