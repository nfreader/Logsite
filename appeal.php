<?php

require_once('inc/config.php');

$session = new Logsite\session();
session_set_save_handler($session, true);

$user = new Logsite\user();
$player = new Logsite\player();

require_once('header.php');

$meta = $player->getMetaByIP($_SERVER['REMOTE_ADDR']);

if (isset($_GET['action'])) {
  if ($_GET['action'] === 'appealReport') {
    include 'views/appealReport.php';
  }
  if ($_GET['action'] === 'viewReport') {
      if ($user->isLoggedIn() || isset($_SESSION['player'])) {
        include 'views/viewReport.php';
    }
  }
} else {

  echo "<h1>".SITE_NAME." appeals</h1>";
  if ($meta) {
    echo "<div class='alert alert-success'>Your IP address is currently attached  to a player in our system.</div>";
      $_SESSION['player'] = $meta->player;
      $_SESSION['IP'] = $meta->IP;
      $reports = $player->getAppealingReports($meta->player);
  } else {
    echo "<div class='alert alert-info'>Your IP address does not match a player in our system.</div>";
  }
  
  if ($reports) {
    echo "<div class='alert alert-success'>You have bans that you may appeal at this time.</div>";
    echo tableHeader(array('Event', 'When','Type'));
    foreach ($reports as $report) {
      $format = eventTypeFormatter($report->type);
      echo "<tr class='".$format['class']."'><td>";
      echo "<a href='?action=appealReport&report=".$report->eventid."'>#".$report->eventid."</a>";
      echo "</td><td>";
      echo relativeTime($report->timestamp);
      echo "</td><td>";
      echo $format['type'];
      echo "</td></tr>";
    } echo "</tbody></table>";
  } else {
    echo "<div class='alert alert-info'>There are no bans eligible for appeal at this time.</div>";
  }
}
echo "<p>Your IP print:<div class='fingerprint'>".hashprint(sha1($_SERVER['REMOTE_ADDR']));
echo "</div></p>";

require_once 'footer.php';