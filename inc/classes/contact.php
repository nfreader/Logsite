<?php class contact {
  public function newReport($player, $type, $notes, $perma) {
    $sql = "INSERT INTO ls_reports 
    (player, type, notes, perma, user, timestamp, eventid)
    VALUES (:player, :type, :notes, :perma, :user, NOW(), :eventid)";
    global $dbh;
    $time = time(); //If there's more than 1000ms of delay on this, the sha1
    //being used for the eventid will be invalid. That's bad.
    $report = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $report->execute(array(
      ':player'=>$player,
      ':type'=>$type,
      ':notes'=>$notes,
      ':perma'=>$perma,
      ':user'=>$_SESSION['userid'],
      ':eventid'=>substr(sha1($player.$type.$time),0,9)
    ));
    $target = new player();
    if ($type === 'C') {
      $data = "Contacted ".$target->getName($player);
      $color = 'green';
    } elseif ($type === 'W') {
      $data = "Warned ".$target->getName($player);
      $color = 'yellow';
    } elseif (($type === 'B') && ($perma === true)) {
      $data = "Permanently banned ".$target->getName($player);
      $target->banPlayer($player, true);
      $color = 'purple';
    } elseif (($type === 'B') && ($perma === false)) {
      $data = "Banned ".$target->getName($player);
      $target->banPlayer($player, false);
      $color = 'red';
    }

    $site = new site();
    $site->logEvent($type, $data, substr(sha1($player.$type.$time),0,9));
    echo "<div class='alert alert-success'>New report added: ".$data.".";
    echo "<a href='?action=viewReport&report=".substr(sha1($player.$type.$time),0,9)."'>Click to view event</a></div>";
    $hc = new HipChat\HipChat(HIPCHAT_TOKEN);
    $hc->message_room()
  }
}