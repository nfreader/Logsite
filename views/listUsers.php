<?php
$user = new Logsite\user();

$users = $user->listUsers();

//print_r($users);

echo tableHeader(array('ID','Name','Registered','Rank','Status','Reports'));

foreach ($users as $userlist) {
  switch ($userlist->status) {
    case 1:
      if ($user->isAdmin() && $_SESSION['userid'] != $userlist->id) {
        $status = 'Active (<a href="?action=deactivateUser&user='.$userlist->id.'">Deactivate?</a>)';
      } else {
        $status = "Active";
      } 
    break;

    case 0:
      if ($user->isAdmin()) {
        $status = 'Inactive (<a href="?action=activateUser&user='.$userlist->id.'">Activate?</a>)';
      } else {
        $status = "Inactive";
      } 

    break;
  }

  switch ($userlist->rank) {
    case 'U':
    $rank = 'User';
    break;

    case 'A':
    $rank = 'Administrator';
    break;
  }

  echo "<tr><td>";
  echo $userlist->id;
  echo "</td><td>";
  echo "<a href='?action=viewUser&user=".$userlist->id."'>
    ".$userlist->username."</a>";
  echo "</td><td>";
  echo "<span class='rollover' data-toggle='tooltip' title='".$userlist->timestamp."'>".relativeTime($userlist->timestamp)."</span>";
  echo "</td><td>";
  echo $rank;
  echo "</td><td>";
  echo $status;
  echo "</td><td>";
  echo $userlist->reports;
  echo "</td></tr>";
}

echo "</tbody></table>";