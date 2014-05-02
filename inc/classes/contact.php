<?php

namespace Logsite;

use HipChat\HipChat as Hipchat;

class contact {
  public function newReport($player, $type, $notes, $perma) {
    $sql = "INSERT INTO ls_reports 
    (player, type, notes, perma, user, timestamp, eventid)
    VALUES (:player, :type, :notes, :perma, :user, NOW(), :eventid)";
    global $dbh;
    $time = time(); //If there's more than 1000ms of delay on this, the sha1
    //being used for the eventid will be invalid. That's bad.
    $eventid = substr(sha1($player.$type.$time),0,9);
    $report = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $report->execute(array(
      ':player'=>$player,
      ':type'=>$type,
      ':notes'=>$notes,
      ':perma'=>$perma,
      ':user'=>$_SESSION['userid'],
      ':eventid'=>$eventid
    ));
    $target = new player();
    $name = $target->getName($player);
    if ($type === 'C') {
      $data = "Contacted ".$name;
      $color = 'green';
      $type = 'contacted';
    } elseif ($type === 'W') {
      $data = "Warned ".$name;
      $color = 'yellow';
      $type = 'warned';
    } elseif (($type === 'B') && ($perma === true)) {
      $data = "Permanently banned ".$name;
      $target->banPlayer($player, true);
      $color = 'purple';
      $type = 'permanently banned';
    } elseif (($type === 'B') && ($perma === false)) {
      $data = "Banned ".$name;
      $target->banPlayer($player, false);
      $color = 'red';
      $type = 'banned';
    }

    $site = new site();
    $site->logEvent($type, $data, substr(sha1($player.$type.$time),0,9));
    echo "<div class='alert alert-success'>New report added: ".$data.". ";
    echo "<a href='?action=viewReport&report=".$eventid."'>Click to view event</a></div>";
    $url = SITE_URL."/index.php";

    $data = $_SESSION['username']." ".$type;
    $data.= " <a href='".$url."?action=viewPlayer&player=".$player."'>".$name."</a> with notes:<br><em>".$notes."</em><br>";
    $data.= "Event ID: <a href='".$url."?action=viewReport&report=".$eventid."'>#";
    $data.= $eventid."</a>";

    echo $data;

    if (constant('HIPCHAT_TOKEN')) {
      $hc = new HipChat(HIPCHAT_TOKEN);
      //$hc->message_room(HIPCHAT_ROOM, SITE_NAME, $data, true, $color);
    }
  }
}