<?php

require_once('inc/config.php');

$session = new Logsite\session();
session_set_save_handler($session, true);

$user = new Logsite\user();
$player = new Logsite\player();

require_once('header.php');

//echo showColors();



$passwords = array();

$i=0;
while ($i < 6){
  $words = array_rand($PGPWordList,5);
  $sep = array_rand($greekAlphabet);
  $passwords[$i] = $PGPWordList[$words[0]]."\\".$greekAlphabet[$sep]."/".$PGPWordList[$words[1]]."$".$PGPWordList[$words[2]]."@".$PGPWordList[$words[3]]."^".$PGPWordList[$words[4]];
  $i++;
}

//print_r($passwords);

$message = "<p><strong>".SITE_NAME."</strong> has automatically picked a password for you. One of the passwords below has been tied to your account. The website will tell you which one to use.";

$i = 1;
foreach ($passwords as $password) {
  $message.= "<h1>Password ".$i."</h1>". "\r\n";
  $message.= $password. "\r\n";
  $i++;
}

echo $message;
$to      = 'nick@nfreader.net';
$subject = 'the subject';

$headers = 'From: webmaster@example.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

//mail($to, $subject, $message, $headers);

// list rooms
//foreach ($hc->get_rooms() as $room) {
//  echo " - $room->room_id = $room->name\n";
//}

// send a message to the 'Development' room from 'API'


require_once 'footer.php';