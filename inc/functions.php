<?php 
function headerBtns() {
  if(isset($_SESSION['username'])) {
    $output = '<div class="navbar-right">You are logged in as '.$_SESSION['username'].'</div>';
  } else {
    $output = '<form class="navbar-form navbar-right" action="index.php?action=login" role="form">';
    $output.= '<div class="form-group">';
    $output.= '<input type="text" placeholder="Username" name="username" class="form-control">';
    $output.= '</div>';
    $output.= '<div class="form-group">';
    $output.= '<input type="password" placeholder="Password" name="password" class="form-control">';
    $output.= '</div>';
    $output.= '<button type="submit" class="btn btn-success">Sign in</button>';
    $output.= '</form>'; 
  }
  return $output; 
}

function relativeTime($date, $postfix = ' ago', $fallback = 'F Y') {
  $diff = time() - strtotime($date);
  if($diff < 60) 
      return $diff . ' second'. ($diff != 1 ? 's' : '') . $postfix;
  $diff = round($diff/60);
  if($diff < 60) 
      return $diff . ' minute'. ($diff != 1 ? 's' : '') . $postfix;
  $diff = round($diff/60);
  if($diff < 24) 
      return $diff . ' hour'. ($diff != 1 ? 's' : '') . $postfix;
  $diff = round($diff/24);
  if($diff < 7) 
      return $diff . ' day'. ($diff != 1 ? 's' : '') . $postfix;
  $diff = round($diff/7);
  if($diff < 4) 
      return $diff . ' week'. ($diff != 1 ? 's' : '') . $postfix;
  $diff = round($diff/4);
  if($diff < 12) 
      return $diff . ' month'. ($diff != 1 ? 's' : '') . $postfix;

  return date($fallback, strtotime($date));
}

function tableHeader($columns) {
  $header = "<table class='table table-condensed table-responsive'><thead><tr>";
  foreach ($columns as $column) {
      $header.= "<th>".$column."</th>";
  }
  $header.= "</thead><tbody>";
  
  return $header;
}

function nameFormatter($name) {
  $user = new Logsite\user();
  $user = $user->getUserProfile($name);
  switch($user->rank) {
    default:
    case 'U':
    $class = 'primary';
    break;

    case 'A':
    $class = 'danger';
    break;
  }
  $profile = "<span class='label label-".$class."'>";
  $profile.= "<a href='?viewProfile=".$user->id."'>";
  $profile.= $user->username;
  $profile.= "</a></span>";
  return $profile;
}

function singular($value, $one, $many) {
    if ($value == 1) {
        return $one;
    } else {
        return $many;
    }
}
function hashPrint($value) {
  global $colors;
  $output = '';
  $value = str_split(substr(strtolower($value), 0, 40),4);
  foreach ($value as $char) 
  {
    $ord = ord($char[0]);
    $offset = 48; // ASCII value for 0
    if ($char[0] >= 'a') {
      $offset = 87;
    }
    $color = $colors[$ord - $offset];
    $offset = $ord - $offset;
    $output.= "<span class='label' style='background:".$color."'>".$char[0].$char[1].$char[2].$char[3]."</span>";
  }
  return $output;
}

function eventTypeFormatter($type) {
  $format = array();
  switch ($type) {
    default:
    $format['class'] = 'active';
    $format['type'] = '';
    break;
    
    case 'C':
    $format['class'] = 'success contacted';
    $format['type'] = icon('bullhorn').' Contacted';
    break;
    
    case 'W':
    $format['class'] = 'warning warned';
    $format['type'] = icon('warning-sign').' Warned';
    break;
    
    case 'B':
    $format['class'] = 'danger banned';
    $format['type'] = icon('minus-sign').' Banned';
    break;
    
    case 'P':
    $format['class'] = 'permanent';
    $format['type'] = icon('remove').' Permanently banned';
    break;
  }
  return $format;
}

function icon($icon,$class='') {
  return "<span class='glyphicon glyphicon-".$icon." ".$class."'></span> ";
}

function reportTable($reports) {
  echo tableHeader(array('#','User','Type','Player','Time','Event ID'));
  if (!$reports) {
    echo "<tr><td colspan=6>No reports</td></tr>";
  }
  foreach ($reports as $report) {
    $format = eventTypeFormatter($report->type);
    echo "<tr class='".$format['class']."'><td>".$report->id;
    echo "</td><td>";
    echo "<a href='?action=viewUser&user=".$report->userid."'>
    ".$report->username."</a>";
    echo "</td><td>";
    echo $format['type'];
    echo "</td><td>";
    echo "<a href='?action=viewPlayer&player=".$report->playerid."'>
    ".$report->player."</a>";
    echo "</td><td>";
    echo "<span class='rollover' data-toggle='tooltip'";
    echo "title='".$report->timestamp."'>";
    echo relativeTime($report->timestamp)."</strong></span>";
    echo "</td><td>";
    echo "<a href='?action=viewReport&report=".$report->eventid."'>#".$report->eventid."</a>";
    echo "</td></tr>";
  }
echo "</tbody></table>"; 
}

function showColors() {
  global $glyphs;
  global $colors;
  echo "<div class='row'>";
  echo "<div class='col-md-10'>";
  foreach ($glyphs as $glyph) {
    echo "<span>sha1(".$glyph.") = </span>";
    echo "<div class='fingerprint'>".hashPrint(sha1($glyph))."</div><br>";
  }
  echo "</div><div class='col-md-2'>";
  $i = 0;
  foreach ($colors as $color) {
    echo "<span style='width: 100px; height: 20px; display: block; background: ".$color."; color: white;'>$i</span>";
    $i++;
  }
  echo "</div></div>";
}


