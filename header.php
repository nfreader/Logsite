<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo SITE_NAME; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css"rel="stylesheet">

    <link rel="stylesheet/less" type="text/css" href="assets/css/style.less" />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">
    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/1.6.3/less.min.js">
    </script>
   
    <link rel="stylesheet" type="text/css" href="assets/css/select.css" />
    <style>
      form.register .checkbox {
        display: none;
      } 
    </style>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  </head>
  <body>
    <div class="navbar navbar-inverse navbar-static-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><?php echo SITE_NAME; ?></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Players <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="?action=newPlayer"><?php echo icon('plus'); ?>Add new player</a></li>
                  <li><a href="#"><?php echo icon('list-alt'); ?>Add new player and report</a></li>
                  <li><a href="?action=addReport"><?php echo icon('pencil'); ?>Add new report</a></li>
                  <li><a href="#"><?php echo icon('search'); ?>Find player</a></li>
                  <li class="divider"></li>
                  <li>
                  <li role="presentation" class="dropdown-header"> 
                    Administrators only
                  </li>
                  <li>
                    <a href="?action=bulkAdd"><?php echo icon('import'); ?>Bulk add players</a>
                  </li>
                </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="?action=addReport"><?php echo icon('pencil'); ?>Add new report</a></li>
                  <li><a href="?action=viewReports"><?php echo icon('list'); ?>View all reports</a></li>
                  <li><a href="#"><?php echo icon('search'); ?>Find report</a></li>
                </ul>
              </li>
            </ul>
            <p class="navbar-text navbar-right">
            <?php 
            if ($user->isLoggedIn()) {
                echo "You are logged in as ".$_SESSION['username']."! <a href='?action=logout'>Log out</a>";
              } else {
                echo "You are not logged in.";
              } 
            ?>
            </p>                   
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <!-- Main jumbotron for a primary marketing message or call to action -->
   <!--  <div class="jumbotron">
      <div class="container">
        <h1>Hello, world!</h1>
        <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
        <p><a class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a></p>
      </div>
    </div> -->

    <div class="container">