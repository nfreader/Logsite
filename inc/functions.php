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
    $format['text'] = 'No status';
    $format['type'] = icon('minus').''.$format['text'];
    break;
    
    case 'C':
    $format['class'] = 'success contacted';
    $format['text'] = 'Contacted';
    $format['type'] = icon('bullhorn').''.$format['text'];
    break;
    
    case 'W':
    $format['class'] = 'warning warned';
    $format['text'] = 'Warned';
    $format['type'] = icon('warning-sign').''.$format['text'];
    break;
    
    case 'B':
    $format['class'] = 'danger banned';
    $format['text'] = 'Banned';
    $format['type'] = icon('minus-sign').''.$format['text'];
    break;
    
    case 'P':
    $format['class'] = 'permanent';
    $format['text'] = 'Permanently banned';
    $format['type'] = icon('remove').''.$format['text'];

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

function renderComment($comment) {
  $parser = new Logsite\parsedown();
  if ($comment->guest == true) {
    $parser->displayImages(false);
  }
  $body = htmlspecialchars($comment->comment);
  $body = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<p><iframe width=\"320\" height=\"270\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe></p>",$body);
  $body = $string = preg_replace("+(\#\w{9})+", '<a href="?action=viewReport&report=$1">$1</a>', $body);
  $body = str_replace('&report=#', '&report=', $body);
  $body = $parser->parse($body);

  echo "<div class='panel panel-default' id=".$comment->id.">";
  echo "<div class='panel-heading'><h3 class='panel-title'>";
  if ($comment->guest == false){
    echo "Comment from ".nameFormatter($comment->username)." ";
  } else {
    echo "Guest <a href='?action=viewPlayer&player=".$comment->guestid."'>".$comment->username."</a> commented ";
  }
  echo "<span class='rollover' data-toggle='tooltip' title='".$comment->timestamp."'>".relativeTime($comment->timestamp)."</span></h3>";
  echo "<p class='pull-right'><a href='#".$comment->id."'>#".$comment->id."</a></p></div>";
  echo "<div class='panel-body'>";
  echo $body;
  echo "</div></div>";
}

function renderReport($report) {
  $format = eventTypeFormatter($report->type);
  echo "<div class='page-header'><h1>Event #".$report->eventid." ";
  echo "<small>Filed <span class='rollover' data-toggle='tooltip' title='".$report->timestamp."'>".relativeTime($report->timestamp)."</span></small>";
  echo "</h1></div>";
  echo "<div class='panel panel-".$format['class']."'>";
  echo "<div class='panel-heading'><h3 class='panel-title'>";
  echo nameFormatter($report->username)." ".$format['type']." ";
  echo "<a href='?action=viewPlayer&player=".$report->playerid."'>";
  echo $report->player."</a></h3></div>";
  echo "<div class='panel-body'>";
  echo "<p><em>The following note was attached:</em></p>";
  echo "<p>".$report->notes."</p>";
  echo "</div><div class='panel-footer'>";
  if ($report->appeal == false && $report->type == 'B' || $report->type == 'P') {
    echo "This ban may not be appealed.";
  } elseif ($report->type == 'B' || $report->type =='P' && $report->appeal == true) {
    echo "This ban may be appealed.";
  } elseif ($report->public == true) {
    echo "This report can be publicly viewed.";
  }
  echo "</div></div>";
}

