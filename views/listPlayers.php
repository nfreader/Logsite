<?php
$player = new Logsite\player();
$listAll = $player->listPlayers();
//print_r($listAll);

playerListTable($listAll);

?>