<?php 

if(empty($_GET['report'])) {
  include 'home.php';
} else {
  $site = new Logsite\site();
  $report = $site->viewReport($_GET['report']);
  if ((isset($_POST['comment'])) && ($user->isLoggedIn())) {
    $site->addReportComment($_POST['comment'], $report->eventid);
  } elseif (!$user->isLoggedIn() && isset($_SESSION['player'])) {
    $site->addReportCommentGuest($comment, $report->eventid);
  }
  //print_r($report);
 ?>

<div class="row">
  <div class="col-md-12">

  <?php 

    renderReport($report);

    $comments = $site->getReportComments($report->eventid);

    foreach ($comments as $comment) {
      renderComment($comment);
    }
  ?>

  <div class='page-header'><h3>Leave a comment</h3></div>
  <form class="form" action="?action=viewReport&report=<?php echo $report->eventid;?>" method="POST">
    <div class='form-group'>
    <label for='comment'>Comment (You can use <a href="https://help.github.com/articles/github-flavored-markdown" target="_blank">Markdown</a>! YouTube links will be automatically embedded!)</label>
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