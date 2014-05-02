<?php
$player = new Logsite\player();
$listAll = $player->listPlayers();
//print_r($listAll);

echo tableHeader(array('Name','Status','Reports','Contacted','Warned','Banned', 'Perma'));

foreach ($listAll as $player) {
  $format = eventTypeFormatter($player->status);
  echo "<tr class='".$format['class']."'><td>";
  echo "<a href='?action=viewPlayer&player=".$player->id."'>".$player->name;
  echo "</a></td><td>";
  echo $format['type'];
  echo "</td><td>";
  echo $player->reports;
  echo "</td><td>";
  echo $player->contacted;
  echo "</td><td>";
  echo $player->warned;
  echo "</td><td>";
  echo $player->banned;
  echo "</td><td>";
  echo $player->perma;
  echo "</td></tr>";
}
echo "</tbody></table>";
?>