<div class="row">
  <div class="col-md-4">
    <?php
    $site = new Logsite\site();
    $players = $site->countRows('ls_players');
    $stats = $site->getSiteStats();
    $totalReports = $site->countRows('ls_reports');?>
    <h2>
      <?php echo $totalReports." ".singular($totalReports,'report','reports')." for ". $players ." ". singular($players,'player','players');?> 
    </h2>
        
    <?php
    $bars = array();
    foreach($stats as $stat) {
      $format = eventTypeFormatter($stat->type);
      echo $format['type'].": ".$stat->num." ".
      singular($stat->num,'report','reports')."<br>";
      $array = array();
      $array['type'] = strip_tags($format['type']);
      $array['width'] = ($stat->num/$totalReports) * 100;
      $array['class'] = $format['class'];
      $array['num'] = $stat->num;
      array_push($bars, $array);
    }
    ?>

    <div class="progress">
    <?php foreach($bars as $bar) {
      echo "<div class='progress-bar rollover progress-bar-".$bar['class'];
      echo "' style='width:".$bar['width']."%' data-toggle='tooltip'";
      echo "title='".$bar['type']."'>".$bar['num']."</div>";
      } ?>
    </div>
  </div>
  <div class="col-md-8">
    <h2>Recent Reports</h2>
       <?php
        $recent = 5;
        $reports = $site->viewReports(0,$recent,NULL);
        reportTable($reports);
        echo "<p>Showing ".$recent." of ".($totalReports);
        echo " <a href='?action=viewReports'>View All</a></p>";
        ?>
  </div>
</div>
