<?php

require_once('inc/config.php');

$session = new Logsite\session();
session_set_save_handler($session, true);

$user = new Logsite\user();
$player = new Logsite\player();

require_once('header.php');

//echo showColors();

$string = preg_replace("+(\#\w{9})+",'<a href="?action=viewReport&report=$1">$1</a>', "Tests auto-linking event IDs: #8ee5a1d84
#b0bcbd1c7
#4d31d3a0a
#6917c2441
#0ae74c8b8
#f50bceeec
#47bc39dbf
#3cc79efc5
#4f7a2c13d
#466251356
#ce2923962
#1acdb6f49
#7674336f0");
$string = str_replace('&report=#', '&report=', $string);
echo $string;

// list rooms
//foreach ($hc->get_rooms() as $room) {
//  echo " - $room->room_id = $room->name\n";
//}

// send a message to the 'Development' room from 'API'


require_once 'footer.php';