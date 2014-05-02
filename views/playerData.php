<div class="col-md-6">
  <?php
  $bars = array();
  foreach($stats as $stat) {
    $format = eventTypeFormatter($stat->type);
    echo $format['type'].": ".$stat->num." ".
    singular($stat->num,'time','times')."<br>";
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
      echo "<div class='progress-bar rollover progress-bar-".$bar['class']."'
      style='width:".$bar['width']."%' data-toggle='tooltip' title='".$bar['type']."'>".$bar['num']."</div>";
      } ?>
    </div>
    <?php
    echo "Averaging ".$totalReports/ceil(abs(strtotime($data->timestamp)-time())/86400)." reports per day."; ?>
    <h3>Player metadata</h3>
    <p><?php
    foreach ($meta as $met) {
      if ($met->key == 'IP'){
        echo "<p><strong>IP Address</strong><br>";
        echo "<div class='fingerprint'>".hashPrint($met->value)."</div></p>";
      } elseif ($met->key == 'email') {
        echo "<p><strong>Email address</strong><br>";
        echo "<div class='fingerprint'>".hashPrint($met->value)."</div></p>";
      } else {
        echo "<p><strong>".$met->key."</strong><br>";
        echo $met->value."</p>";
      }
    }
    ?></p>
</div>
