<?php

namespace Logsite;

use HipChat\HipChat as Hipchat;

class player {

  public function addNewPlayer($names) {
    $sql = "INSERT INTO ls_players
    (name, timestamp, lastupdated) VALUES 
    (:name, NOW(), NOW())";
    global $dbh;
    $player = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));

    //TODO: Make $names into an array by default
    


    if (is_array($names)) {
      $i = 0;
      foreach($names as $name) {
          try {
            //$player->execute(array(':name'=>$name));
          } catch(PDOException $e) {
            echo "<div class='alert alert-danger'>
            ".$e->getMessage().'</div>';
          }
          $i++;
        }
        
      $site = new site();
      $site->logEvent("BA","Bulk added $i players");
      
      if (constant('HIPCHAT_TOKEN')) {
        $hc = new HipChat(HIPCHAT_TOKEN);
        $hc->message_room(HIPCHAT_ROOM, SITE_NAME, $_SESSION['username']." bulk added $i players", false, 'gray');
      }
      return $i;

    } else {
      $player->execute(array(
        ':name'=>$names
      ));
      echo "<div class='alert alert-success'>Added ".$names."</div>";
      if (constant('HIPCHAT_TOKEN')) {
        $hc = new HipChat(HIPCHAT_TOKEN);
        $hc->message_room(HIPCHAT_ROOM, SITE_NAME, $_SESSION['username']." added ".$names, false, 'gray');
      }
    }
  }
  public function countPlayers() {
    $sql = "SELECT count(*) AS players FROM ls_players";
    global $dbh;
    $count = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $count->execute();
    $count = $count->fetch();
    $count = $count->players;
    return $count;
  }

  public function getPlayerList() {
    $sql = "SELECT id, name FROM ls_players";
    global $dbh;
    $list = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $list->execute();
    $list = $list->fetchAll();
    return $list;
  }
  public function getName($id) {
    $sql = "SELECT name FROM ls_players WHERE id = :id";
    global $dbh;
    $name = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $name->execute(array(':id'=>$id));
    $name = $name->fetch();
    return $name->name;
  }

  public function getId($name) {
    $sql = "SELECT * FROM ls_players WHERE name = :name";
    global $dbh;
    $id = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $id->execute(array(':name'=>$name));
    $id = $id->fetch();
    return $id->id;
  }

  public function getPlayer($player) {
    $sql = "SELECT ls_players.*, ls_playermeta.* FROM ls_players LEFT JOIN ls_playermeta ON ls_players.id = ls_playermeta.player WHERE ls_players.id = :player";
    global $dbh;
    $data = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $data->execute(array(':player'=>$player));
    return $data->fetch();
  }

  public function getPlayerMeta($player) {
    $sql = "SELECT ls_players.name,
            ls_playermeta.key,
            ls_playermeta.player,
            CASE WHEN ls_playermeta.key = 'IP'
            THEN sha1(ls_playermeta.value)
            WHEN ls_playermeta.key = 'email'
            THEN sha1(ls_playermeta.value)
            ELSE ls_playermeta.value
            END AS value
            FROM ls_players
            LEFT JOIN ls_playermeta ON ls_players.id = ls_playermeta.player
            WHERE ls_players.id = :player";
    global $dbh;
    $meta = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $meta->execute(array(':player'=>$player));
    return $meta->fetchAll();            
  }

  public function getPlayerStats($player) {
    $sql = "SELECT
            CASE WHEN ls_reports.perma = 1 AND ls_reports.type = 'B'
            THEN 'P'
            WHEN ls_reports.perma = 0 AND ls_reports.type = 'B'
            THEN 'B'
            ELSE ls_reports.type 
            END AS type,
            COUNT(*) as num
            FROM ls_reports
            WHERE ls_reports.player = :player
            GROUP BY ls_reports.player, ls_reports.type, ls_reports.perma";
    global $dbh;
    $data = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $data->execute(array(':player'=>$player));
    return $data->fetchAll();
  }

  public function getPlayerReports($player,$offset=0,$limit=30) {
    $sql = "SELECT ls_reports.*,
    ls_players.*,
    ls_user.username,
    ls_reports.id AS reportid,
    ls_user.email,
    ls_user.rank,
    ls_reports.timestamp AS date,
    CASE WHEN ls_reports.perma = 1
              THEN 'P'
              ELSE ls_reports.type END AS type
    FROM ls_reports
    LEFT JOIN ls_players ON ls_reports.player = ls_players.id
    LEFT JOIN ls_user ON ls_reports.user = ls_user.id
    WHERE ls_players.id = :player
    ORDER BY ls_reports.timestamp DESC
    LIMIT $offset, $limit";

    global $dbh;
    $reports = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $reports->execute(array(':player'=>$player));
    return $reports->fetchAll();
  }

  public function banPlayer($player,$perma) {
    $sql = "UPDATE ls_players SET status = :status, expiration = NOW() + INTERVAL 1 DAY, lastupdated = NOW() WHERE ls_players.id = :player";
    global $dbh;
    $ban = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $ban->execute(array(
      ':status'=>($perma === true ? 'P':'B'),
      ':player'=>$player
    ));
    if ($perma === true) {
      echo "<div class='alert alert-permanent'>This is a permanent ban.</div>";
    }
  }

  public function addPlayerMeta($player,$meta) {
    $sql = "INSERT INTO ls_playermeta 
    (ls_playermeta.key, ls_playermeta.value, player)
    VALUES (:key, :value, :player)";
    global $dbh;
    $data = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    foreach ($meta as $key => $value) {
      $data->execute(array(
        ':key'=>$key,
        ':value'=>$value,
        ':player'=>$player
      ));
    }
  }

}
