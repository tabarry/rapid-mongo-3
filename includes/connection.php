<?php

/* --CONNECTION SETTINGS */
define('DB_HOST', 'localhost');
define('DB_NAME', 'test');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');

/* --CONNECT */
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME) or die(mysqli_connect_error());
mysqli_query($link,"SET NAMES utf8");