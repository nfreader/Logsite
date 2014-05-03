<?php

if (isset($_GET['report'])) {
  $site = new Logsite\site();
  $report = $site->viewReport($_GET['report']);
  if (!$report) {
    echo "<div class='alert alert-danger'>Report not found</div>";
  } else {
    include 'viewReport.php';
  }
}
