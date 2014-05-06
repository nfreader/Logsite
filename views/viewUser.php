<?php 

if(isset($_GET['user'])) {
  $site = new Logsite\site();
  $reports = $site->viewReportsByUser($_GET['user']);
  $profile = $user->getUserProfile(NULL,$_GET['user']); 
} else {
    include 'listUsers.php';
}

 ?>

<div class="row">
  <div class="col-md-12">
    <div class="page-header"><h1>Showing reports filed by <?php echo $profile->username; ?></h1></div>
   <?php reportTable($reports); ?>
  </div>
</div>

