<?php

namespace Logsite;

class site {
  
  /**
  * Salt generator
  *
  * A quick tool to get a bunch of random data to use for hash salting
  * Thanks to @arplynn for the hints
  *
  * @return string
  *   A string of random characters
  *   with a length as defined by PASSWD_SALT_LENGTH (default: 16)
  */ 
     
  public function getSalt() {
    $saltSource = fopen('/dev/urandom', 'rb');
    $saltData = bin2hex(fread($saltSource, PASSWD_SALT_LENGTH));
    fclose($saltSource);
    return $saltData;
  }

  public function logEvent($type, $data=null, $eventid=null) {
    if ($data === null) {
      return false;
    }
    $sql = "INSERT INTO ls_events (who, what, data, timestamp, link) VALUES 
    (:who, :what, :data, NOW(), :link)";
    global $dbh;

    $event = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $event->execute(array(
      ':who'=>$_SESSION['userid'],
      ':what'=>$type,
      ':data'=>$data,
      ':link'=>$eventid
    ));
  }

  public function viewReports($offset=0,$num=30, $user) {
    $sql = "SELECT ls_reports.id,
            ls_reports.player AS playerid,
            ls_reports.notes,
            ls_reports.perma,
            ls_reports.user AS userid,
            ls_reports.timestamp,
            ls_reports.eventid,
            ls_players.name AS player,
            ls_user.username,
            ls_user.rank,
            md5(ls_user.email) AS email,
            CASE WHEN ls_reports.perma = 1
              THEN 'P'
              ELSE ls_reports.type END AS type
            FROM ls_reports
            LEFT JOIN ls_user ON ls_reports.user = ls_user.id
            LEFT JOIN ls_players ON ls_reports.player = ls_players.id
            ORDER BY ls_reports.timestamp DESC
            LIMIT $offset,$num";
    global $dbh;
    $reports = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $reports->execute();
    return $reports->fetchAll();
  }
  public function viewReport($id) {
    $sql = "SELECT ls_reports.id,
            ls_reports.player AS playerid,
            ls_reports.notes,
            ls_reports.perma,
            ls_reports.user AS userid,
            ls_reports.timestamp,
            ls_reports.eventid,
            ls_players.name AS player,
            ls_user.username,
            ls_user.rank,
            ls_reports.appeal,
            ls_reports.public,
            md5(ls_user.email) AS email,
            CASE WHEN ls_reports.perma = 1
              THEN 'P'
              ELSE ls_reports.type END AS type
            FROM ls_reports
            LEFT JOIN ls_user ON ls_reports.user = ls_user.id
            LEFT JOIN ls_players ON ls_reports.player = ls_players.id
            WHERE ls_reports.eventid = :id";
    global $dbh;
    $report = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $report->execute(array(
      ':id'=>$id
    ));
    return $report->fetch();
  }  
  public function getSiteStats() {
  $sql = "SELECT
          CASE WHEN ls_reports.perma = 1 AND ls_reports.type = 'B'
          THEN 'P'
          WHEN ls_reports.perma = 0 AND ls_reports.type = 'B'
          THEN 'B'
          ELSE ls_reports.type 
          END AS type,
          COUNT(*) as num
          FROM ls_reports
          GROUP BY ls_reports.type, ls_reports.perma";
  global $dbh;
  $data = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
  $data->execute();
  return $data->fetchAll();
  }

  public function countRows($table) {
    $sql = "SELECT COUNT(*) AS num FROM $table";
  global $dbh;
  $count = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
  $count->execute();
  $count = $count->fetch();
  return $count->num;
  }

  public function addReportComment($comment, $reportid) {
    $sql = "INSERT INTO ls_reportcomments
    (report, comment, guest, userid, timestamp) VALUES
    (:reportid, :comment, 0, :userid, NOW())";
    global $dbh;
    $addComment = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $addComment->execute(array(
      ':reportid'=>$reportid,
      ':comment'=>$comment,
      ':userid'=>$_SESSION['userid']
    ));
    echo "<div class='alert alert-success'>Comment added.</div>";
  }

  public function addReportCommentGuest($comment, $reportid, $ident) {
    $sql = "INSERT INTO ls_reportcomments
    (report, comment, guest, guestid, guestident, timestamp) VALUES
    (:reportid, :comment, 1, :guestid, :guestident, NOW())";
    global $dbh;
    $addComment = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $addComment->execute(array(
      ':reportid'=>$reportid,
      ':comment'=>$comment,
      ':guestid'=>$_SESSION['player'],
      ':guestident'=>$ident
    ));
    echo "<div class='alert alert-success'>Comment added.</div>";
  }

  public function getReportComments($reportid) {
    $sql = "SELECT ls_reportcomments.*,
            CASE WHEN ls_reportcomments.guest != 0
            THEN ls_players.name
            ELSE ls_user.username
            END AS username
            FROM ls_reportcomments
            LEFT JOIN ls_user ON ls_reportcomments.userid = ls_user.id
            LEFT JOIN ls_players ON ls_reportcomments.guestid = ls_players.id
            WHERE ls_reportcomments.report = :reportid
            ORDER BY ls_reportcomments.timestamp ASC";
    global $dbh;
    $comments = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $comments->execute(array(
      ':reportid'=>$reportid
    ));
    return $comments->fetchAll();
  }

}
