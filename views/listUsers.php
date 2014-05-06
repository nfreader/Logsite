<?php
$user = new Logsite\user();

$users = $user->listUsers();

//print_r($users);

echo tableHeader(array('ID','Name','Registered','Rank','Status','Reports'));

foreach ($users as $user) {
  switch ($user->status) {
    case 1:
    $status = 'Active';
    break;

    case 0:
      if ($_SESSION['rank']=== 'A') {
        $status = 'Inactive (<a href="?action=activateUser&user='.$user->id.'">Activate?</a>)';
      } else {
        $status = "Inactive";
      } 

    break;
  }

  switch ($user->rank) {
    case 'U':
    $rank = 'User';
    break;

    case 'A':
    $rank = 'Administrator';
    break;
  }

  echo "<tr><td>";
  echo $user->id;
  echo "</td><td>";
  echo $user->username;
  echo "</td><td>";
  echo "<span class='rollover' data-toggle='tooltip' title='".$user->timestamp."'>".relativeTime($user->timestamp)."</span>";
  echo "</td><td>";
  echo $rank;
  echo "</td><td>";
  echo $status;
  echo "</td><td>";
  echo $user->reports;
  echo "</td></tr>";
}

echo "</tbody></table>";