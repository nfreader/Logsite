<?php

date_default_timezone_set('UTC');

define('SITE_DIR',$_SERVER['DOCUMENT_ROOT'].'/logsite');
define('SITE_URL','http://neffy.me/logsite');
define('SITE_NAME','Log Site');

define('DB_METHOD', 'mysql');   //Probably won't need to change
define('DB_NAME', 'logsite');
define('DB_USER', 'root');
define('DB_PASS', '123');
define('DB_HOST', 'localhost'); //Probably won't need to change

define('TBL_PREFIX', 'ls_'); //Probably won't need to change unless you want two or more parallel installations

define('PASSWD_SALT_LENGTH',16); //Salt length for hashing passwords

define('HIPCHAT_TOKEN','93f950ea19b7c22a638ea67d9144c2');
define('HIPCHAT_ROOM','546806');

error_reporting(-1);
ini_set('error_reporting', -1);
ini_set('display_errors', true);
ini_set('include_path',''.SITE_DIR.'');

//Option definitions
//TODO: Move into ls_options table

define('MIN_BAN_LENGTH',86400); //Length, in seconds, that players are banned

//Functions for information display. No need to reinclude this!
require_once('db.php');
require_once('autoload.php');
require_once('arrays.php');
require_once('functions.php');
require_once('vendor/autoload.php');

