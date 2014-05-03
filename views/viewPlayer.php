<?php

if ((isset($_POST['contactType']))) {
  if (empty($_POST['permanent'])) {
    $perma = false;
  } elseif (isset($_POST['permanent'])) {
    $perma = true;
  }

  $contact = new Logsite\contact();
  $contact->newReport($_GET['player'],$_POST['contactType'],$_POST['notes'],
  $perma,$_POST['appeal']);
}

if (isset($_GET['player'])) {
  $data = $player->getPlayer($_GET['player']);
  $offset = 0;
  $limit = 30;
  $stats = $player->getPlayerStats($_GET['player']);
  $meta = $player->getPlayerMeta($_GET['player']);
  $totalReports = 0;
  foreach ($stats as $stat) {
    $totalReports = $totalReports + $stat->num;
  }
  $pages = ceil($totalReports/$limit);

if (isset($_GET['page'])) {
  
  $page = $_GET['page'];
  $offset = ($_GET['page']) * $limit;
  if ($offset == 0) {
    $offset = 0;
  }
  $reports = $player->getPlayerReports($_GET['player'],$offset,$limit);
} else {
  $page = 0;
  $reports = $player->getPlayerReports($_GET['player'],0,$limit);
}

  //$reports = $player->getPlayerReports($_GET['player'],$offset,$limit);
  if ($page == 0 || empty($page)) {
  ?>
  <div class="row">
    <div class="col-md-12">
    <div class="jumbotron">
      <h1><?php echo $data->name;?> 
      <small><?php echo $totalReports." ".singular($totalReports,'report','reports');?> since
      <?php echo $data->timestamp; ?></small></h1>
      <div class="center-block">
      <?php
      switch ($data->status) {
        case 'P':
          echo "<h1>
          <span class='label label-permanent'>PERMABANNED</span></h1>";
          break;
        case 'B':
          echo "<h1>
          <span class='label label-danger'>Banned<br>
          <small>Expires: ".$data->expiration."</small></span></h1>";
          break;
        case 'W':
          echo "<h1>
          <span class='label label-warning'>On notice</span></h1>";
          break;
      }
      ?>
      </div>
    </div>
    </div>
    </div>
    
    <div class="row">
    <?php
      include 'playerData.php'; 
      include 'playerReport.php';
    ?>   
  </div>
      <?php
    } 

    $i = 0;
    $nextpage = $page + 1;
    $prevpage = $page - 1;

    echo "<ul class='pagination pagination-lg'>";
    if ($prevpage < 0) {
      echo "<li class='disabled'><a href='#'>";
    } else {
      echo "<li><a href='?action=viewPlayer&player=".$data->id."&page=".$prevpage."'>";
    }
    echo "&laquo;</a></li>";
    while ($i <= $pages - 1) {
      echo "<li><a href='?action=viewPlayer&player=".$data->id."&page=".$i."'>".$i;
      echo "</a></li>";

      $i++;
    }
    if ($nextpage > $pages - 1) {
      echo "<li class='disabled'><a href='#'>";
    } else {  
      echo "<li><a href='?action=viewPlayer&player=".$data->id."&page=".$nextpage."'>";
    }
    echo "&raquo;</a></li>";
    echo "</ul>";


      $r = 0;
      foreach ($reports as $report) {
      
        $format = eventTypeFormatter($report->type);
        
        if ($r % 2 == 0) {
          echo "<div class='row'>";
        }
        $r++;
        echo "<div class='col-md-6'>";
        echo "<div class='panel panel-".$format['class']."' id='".$report->eventid."'>";
        echo "<div class='panel-heading'>".
        nameFormatter($report->username)." ".$format['type']." <a href='?action=viewPlayer&player=".$data->id."'>".$data->name."</a>";
        echo "</div><div class='panel-body'>";
        echo $report->notes;
        echo "</div><div class='panel-footer'>";
        echo "<span class='rollover' data-toggle='tooltip'";
        echo "title='".$report->date."'>";
        echo relativeTime($report->date)."</span> ";
        echo "<a href='?action=viewReport&report=".$report->eventid."'>";
        echo $report->comments. " ".singular($report->comments, 'comment', 'comments')."</a>";
        echo "<p class='pull-right'>Event ID ";
        echo "<a href='?action=viewReport&report=".$report->eventid."'>#".$report->eventid."</a>";
        echo "</p></div></div>";
        echo "</div>";
        if ($r % 2 == 0) {
          echo "</div>";
        }
      }

    $i = 0;
    $nextpage = $page + 1;
    $prevpage = $page - 1;

    echo "<ul class='pagination pagination-lg'>";
    if ($prevpage < 0) {
      echo "<li class='disabled'><a href='#'>";
    } else {
      echo "<li><a href='?action=viewPlayer&player=".$data->id."&page=".$prevpage."'>";
    }
    echo "&laquo;</a></li>";
    while ($i <= $pages - 1) {
      echo "<li><a href='?action=viewPlayer&player=".$data->id."&page=".$i."'>".$i;
      echo "</a></li>";

      $i++;
    }
    if ($nextpage > $pages - 1) {
      echo "<li class='disabled'><a href='#'>";
    } else {  
      echo "<li><a href='?action=viewPlayer&player=".$data->id."&page=".$nextpage."'>";
    }
    echo "&raquo;</a></li>";
    echo "</ul>";

      ?>
    </div>
  </div>
  <?php } ?>