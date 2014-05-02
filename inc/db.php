<?php 

try {
  $dbh = new PDO(''.DB_METHOD.':host='.DB_HOST.';dbname='.DB_NAME.'', DB_USER, DB_PASS, array(
  PDO::ATTR_PERSISTENT => true
));
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
  $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
  //$dbh->setAttribute(PDO::ATTR_PERSISTENT, true);
} catch(PDOException $e) {
  echo "<div class='alert alert-danger'>".$e->getMessage().'</div>';
  $dbh = false;
}


?>