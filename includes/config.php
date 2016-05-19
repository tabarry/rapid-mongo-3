<?php

define('VERSION', '3');
$version = "Rapid Mongo " . VERSION; //If this is changed, please also change config.php in sulata/includes folder
$debug = TRUE;
if(isset($_POST['folder'])) {
    $sitePath = '../' . $_POST['folder'] . '/sulata/';
    $appPath = '../' . $_POST['folder'] . '/';
    $backupPath = '../' . $_POST['folder'] . '/backup/';
}

/* --COMMON SETTINGS */
define('SITE_TITLE', $version);
define('TAG_LINE', 'RAD Tool for PHP and MongoDB Development with Bootstrap');
define('SITE_FOOTER', 'Rapid Mongo is a product of <a href="http://www.sulata.com.pk" target="_blank">Sulata iSoft</a>.');
?>