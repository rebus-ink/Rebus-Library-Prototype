<?php
// Set TZ
date_default_timezone_set ( "UTC" );

// SET SOME VARIABLES ---------------------------------------

// Shortcut for Document Root.
define ('_WR', $_SERVER['DOCUMENT_ROOT']);
// Define Application Environment
if(!defined('_AE')) {
    if(FALSE === stripos($_SERVER['SERVER_NAME'], '.local')) {
        define('_AE', 'prod');
        error_reporting(0);
    } else {
        define('_AE', 'dev');
        error_reporting(E_ALL);
    }
}

// Required stuffs ------------------------------------------
require_once 'functions.php';
?>