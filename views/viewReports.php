<?php 
$site = new Logsite\site();
$limit = 30;
$totalreports = $site->countRows('ls_reports');
$pages = ceil($totalreports/$limit);

if (isset($_GET['user'])) {
  $user = $_GET['user'];
} else {
  $user = NULL;
}

if (isset($_GET['page'])) {
  $page = $_GET['page'];
  $offset = ($_GET['page']) * $limit;
  if ($offset == 0) {
    $offset = 0;
  }
  $reports = $site->viewReports($offset,$limit,$user);
} else {
  $page = 0;
  $reports = $site->viewReports(0,$limit, $user);
}

?>

<div class="row">
  <div class="col-md-12">
    <?php

    $i = 0;
    $nextpage = $page + 1;
    $prevpage = $page - 1;

    echo "<ul class='pagination pagination-lg'>";
    if ($prevpage < 0) {
      echo "<li class='disabled'><a href='#'>";
    } else {
      echo "<li><a href='?action=viewReports&page=".$prevpage."'>";
    }
    echo "&laquo;</a></li>";
    while ($i <= $pages - 1) {
      echo "<li><a href='?action=viewReports&page=".$i."'>".$i;
      echo "</a></li>";

      $i++;
    }
    if ($nextpage > $pages - 1) {
      echo "<li class='disabled'><a href='#'>";
    } else {  
      echo "<li><a href='?action=viewReports&page=".$nextpage."'>";
    }
    echo "&raquo;</a></li>";
    echo "</ul>";

    reportTable($reports);

    $i = 0;
    echo "<ul class='pagination'>";
    if ($prevpage < 0) {
      echo "<li class='disabled'><a href='#'>";
    } else {
      echo "<li><a href='?action=viewReports&page=".$prevpage."'>";
    }
    echo "&laquo;</a></li>";
    while ($i <= $pages - 1) {
      echo "<li><a href='?action=viewReports&page=".$i."'>".$i;
      echo "</a></li>";

      $i++;
    }
    if ($nextpage > $pages - 1) {
      echo "<li class='disabled'><a href='#'>";
    } else {  
      echo "<li><a href='?action=viewReports&page=".$nextpage."'>";
    }
    echo "&raquo;</a></li>";
    echo "</ul>";
    ?>

  </div>
</div>