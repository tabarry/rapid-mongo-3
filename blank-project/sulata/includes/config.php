<?php

/*
 * SULATA FRAMEWORK
 * Version: #VERSION#
 * Date: March 1, 2016
 */
//Check the required PHP version 5.2 or greater
if (PHP_VERSION_ID < 50200) {
    exit('You require PHP version 5.2 or greater.');
}
//Include the language file
include('language.php');
//MISC SETTINGS
define('LOCAL_URL', 'http://localhost/#SITE_FOLDER#/');
define('WEB_URL', 'http://localhost/#SITE_FOLDER#/');
define('SESSION_PREFIX', '#SESSION_PREFIX#');
define('UID_LENGTH', 14);
define('OPENID_DOMAIN', $_SERVER['HTTP_HOST']);
define('GOOGLE_LOGOUT_URL', 'https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=');
define('COOKIE_EXPIRY_DAYS', '30');

//URLs and db settings
//Other settings are in sulata_settings table
//If local
if (!strstr($_SERVER['HTTP_HOST'], ".")) {
    //DEBUG
    define('DEBUG', TRUE);
    ini_set('display_errors', 1);
    //
    define('BASE_URL', LOCAL_URL);
    define('ADMIN_URL', BASE_URL . '_admin/');
    define('PING_URL', BASE_URL . 'static/ping.html');
    define('MAINTENANCE_URL', BASE_URL . 'maintenance/');
    define('NOSCRIPT_URL', BASE_URL . 'sulata/static/no-script.html');
    define('ACCESS_DENIED_URL', BASE_URL . 'sulata/static/access-denied.html');
    define('ADMIN_UPLOAD_PATH', '../files/');
    define('PUBLIC_UPLOAD_PATH', 'files/');
    define('LOCAL', TRUE);
    //MONGODB Settings
    define('DB_HOST', 'localhost');
    define('DB_NAME', '#DB_NAME#');
    define('DB_USER', '#DB_USER#');
    define('DB_PASSWORD', '#DB_PASSWORD#');
    define('DB_PORT', '27017');
    define('DB_CONNECTION_STRING', "mongodb://" . DB_HOST . ":" . DB_PORT);
} else {
    //DEBUG
    define('DEBUG', FALSE);
    ini_set('display_errors', 0);
    //
    define('BASE_URL', WEB_URL);
    define('ADMIN_URL', BASE_URL . '_admin/');
    define('PING_URL', BASE_URL . 'sulata/ping.html');
    define('MAINTENANCE_URL', BASE_URL . 'maintenance/');
    define('NOSCRIPT_URL', BASE_URL . 'sulata/static/no-script.html');
    define('ACCESS_DENIED_URL', BASE_URL . 'sulata/static/access-denied.html');
    define('ADMIN_UPLOAD_PATH', '../files/');
    define('PUBLIC_UPLOAD_PATH', 'files/');
    define('LOCAL', FALSE);
    //MONGODB Settings
    define('DB_HOST', 'localhost');
    define('DB_NAME', '#DB_NAME#');
    define('DB_USER', '#DB_USER#');
    define('DB_PASSWORD', '#DB_PASSWORD#');
    define('DB_PORT', '27017');
    define('DB_CONNECTION_STRING', "mongodb://" . DB_USER . ":" . DB_PASSWORD . "@" . DB_HOST . ":" . DB_PORT);
}
//Edit delete download access
$editAccess = TRUE;
$deleteAccess = TRUE;
$addAccess = TRUE;
$downloadAccessCSV = TRUE;
$downloadAccessPDF = TRUE;

