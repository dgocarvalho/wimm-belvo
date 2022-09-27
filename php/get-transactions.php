

<?php
    /**
     * This file is part of WIMM project created as part of Belvo API test.
     *
     * @author (c) Diogo Carvalho <dgo.eng@gmail.com>
     * @version 1.0.0
     *
     * PHP Code that integrate Transations web page with the API response
     *
     */

    require_once '../global.php';

    session_start();

    echo $belvoAPI->getTransactions($_SESSION['link']);

?>