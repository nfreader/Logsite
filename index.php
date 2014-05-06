<?php

require_once('inc/config.php');

$session = new Logsite\session();
session_set_save_handler($session, true);
$user = new Logsite\user();
$player = new Logsite\player();

require_once('header.php');
if (isset($_GET['action'])) { //User is trying to log in
  if ($_GET['action'] === 'login') {
    if (!$user->logIn($_POST['username'],$_POST['password'])){
      include 'views/guest.php';
    } else {
      include 'views/home.php';
    }
  }

  if ($_GET['action'] === 'register') {
      if (sha1($_POST['password']) != sha1($_POST['password-again'])) {
        echo "<div class='alert alert-danger'>The password verification
        did not match. Please try again.</div>";
        } else {
        $user->registerNewUser($_POST['username'],
        $_POST['password'],$_POST['email']);
      } 
    }

  if (isset($_GET['action']) && ($user->isLoggedIn())) {
    //User is doing something and is logged in
  
    //Basic functional $_GET['action'] handlers, just route to the page where
    //the action was initiated from, handle any other parameters there
  
    if ($_GET['action'] === 'bulkAdd') {
      include 'views/bulkAddForm.php';
    }
    if ($_GET['action'] === 'viewReports') {
      include 'views/viewReports.php';
    }
    if ($_GET['action'] === 'newPlayer') {
      include 'views/newPlayerForm.php';
    }    
    if ($_GET['action'] === 'viewReport') {
      include 'views/viewReport.php';
    }
    if ($_GET['action'] === 'viewPlayer') {
      include 'views/viewPlayer.php';
    }
    if ($_GET['action'] === 'viewUser') {
      include 'views/viewUser.php';
    }
    if ($_GET['action'] === 'findPlayer') {
      include 'views/findPlayer.php';
    }
    if ($_GET['action'] === 'listPlayers') {
      include 'views/listPlayers.php';
    }
    if ($_GET['action'] === 'listUsers') {
      include 'views/listUsers.php';
    }
    if ($_GET['action'] === 'activateUser' && isset($_GET['user'])) {
      $user->activateUser($_GET['user']);
      include 'views/home.php';
    }
    if ($_GET['action'] === 'logout') {
      session_destroy();
      echo "<div class='alert alert-success'>You have been logged out.
      <a href='index.php'>Continue</a></div>";
    }
  }
} elseif ($user->isLoggedIn()) {
  //User is not doing something but is logged in
  include 'views/home.php';
} else {
  //User is not logged in
  include 'views/guest.php';
}

require_once 'footer.php';