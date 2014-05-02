<?php 

if(empty($_GET['report'])) {
  include 'home.php';
} else {
  $site = new site();
  $report = $site->viewReport($_GET['report']);
  if ((isset($_POST['comment'])) && ($user->isLoggedIn())) {
    $site->addReportComment($_POST['comment'], $report->eventid);
  } elseif (!$user->isLoggedIn()) {
    //$site->addReportCommentGuest($comment, $report->eventid);
  }
  //print_r($report);
 ?>

<div class="row">
  <div class="col-md-12">

  <?php $format = eventTypeFormatter($report->type);

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
    if ($report->appeal == false &&
        $report->type == 'B' ||
        $report->type =='P') {
    echo "</div><div class='panel-footer'>";
    
      echo "This is a permanent ban. It may not be appealed.";
    } elseif ($report->type == 'B' || $report->type =='P') {
      echo "This ban may be appealed.";
    } else {
    }
    echo "</div></div>";

    $comments = $site->getReportComments($report->eventid);
    //print_r($comments);

    foreach ($comments as $comment) {
      echo "<div class='panel panel-default'>";
      echo "<div class='panel-heading'><h3 class='panel-title'>";
      echo "Comment from ".nameFormatter($comment->username)." ";
      echo "<span class='rollover' data-toggle='tooltip' title='".$comment->timestamp."'>".relativeTime($comment->timestamp)."</span></h3></div>";
      echo "<div class='panel-body'>";
      echo $comment->comment;
      echo "</div></div>";
    }
  ?>

  <div class='page-header'><h3>Leave a comment</h3></div>
  <form class="form" action="?action=viewReport&report=<?php echo $report->eventid;?>" method="POST">
    <div class='form-group'>
    <label for='comment'>Comment</label>
    <textarea class='form-control' name='comment' rows='10' placeholder='Comment' required></textarea>
    </div>
    <?php if ($report->public == true) {
        echo '<p class="help-block">This comment will be publicly visible.</p>';
      } ?>
    <button type="submit" class="btn btn-primary">Add</button>
  </form>
    
  </div>
</div>

<?php } ?>