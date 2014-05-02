<?php

require_once('inc/config.php');

$session = new session();
session_set_save_handler($session, true);

$user = new user();
$player = new player();

require_once('header.php');

//echo showColors();
if (constant('HIPCHAT_TOKEN')) {
  $hc = new HipChat/HipChat(HIPCHAT_TOKEN);
  $hc->message_room(SITE_NAME, "Test Message", false, 'green');
} else {
  echo "HipChat not enabled";
}
// list rooms
//foreach ($hc->get_rooms() as $room) {
//  echo " - $room->room_id = $room->name\n";
//}

// send a message to the 'Development' room from 'API'


require_once 'footer.php';