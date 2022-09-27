<?php
    /**
     * This file is part of WIMM project created as part of Belvo API test.
     *
     * @author (c) Diogo Carvalho <dgo.eng@gmail.com>
     * @version 1.0.0
     *
     * PHP Code that integrate Account web page with the API response
     *
     *
     *
     *
     */

require_once '../global.php';
require_once 'DatabaseClass.php';

session_start();
$db = new DatabaseClass ();
$db->update_alldata($belvoAPI, $_SESSION['link'], $_SESSION['user_id']);

header('location:../index.php?msg=dataUpdated');

?>