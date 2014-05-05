<?php

namespace Logsite;

class user {

  public function isLoggedIn() {
    if ((isset($_SESSION['username'])) && (isset($_SESSION['userid']))) {
      return true;
    }
  }

  public function isAdmin() {
    $sql = "SELECT rank FROM ls_user WHERE ls_user.id = :id";
    global $dbh;
    $admin = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $admin->execute(array(
      ':id'=>$_SESSION['userid']
    ));
    $admin = $admin->fetch();
    if ($admin->rank === 'A') {
      return true;
    }
  }

  public function isUnique($username, $email) {
    $sql = "SELECT * FROM ls_user WHERE username = :username OR email = :email";
    global $dbh;
    $unique = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $unique->execute(array(
      ':username'=>$username,
      ':email'=>$email
    ));
    $unique = $unique->fetchAll();
    if ($unique === array()) {
      return true;
    } else {
      return false;
    }
  }

  public function registerNewUser($username, $password, $email) {
    if($this->isUnique($username, $password)) {

      $sql = "INSERT INTO ls_user
      (username, password, email, salt, timestamp) VALUES 
      (:username, :password, :email, :salt, NOW())";
      global $dbh;
  
      $salt = new site();
      $salt = $salt->getSalt();
  
      $newUser = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
      $newUser->execute(array(
        ':username'=>$username,
        ':password'=>hash('sha512', $salt . $password),
        ':email'=>$email,
        ':salt'=>$salt
      ));
      echo "<div class='alert alert-success'>You are now registered.
      Please log in.</div>";
    } else {
      echo "<div class='alert alert-danger'>This username or
      email address is already in use.</div>";
    }
  }

  public function logIn($username, $password) {
    $sql = "SELECT username, salt FROM ls_user WHERE username = :username";
    global $dbh;
    $check = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $check->execute(array(
      ':username'=>$username
    ));
    $check = $check->fetch();
    if ($check == array()) {
      echo "<div class='alert alert-danger'>Username or
      password invalid.</div>";
      return false;
    } else {
      $sql = "SELECT id, username, email, rank FROM ls_user
      WHERE password = :password AND username = :username";
      $login = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
      $login->execute(array(
        ':password'=>hash('sha512', $check->salt . $password),
        ':username'=>$username,
      ));
      $login = $login->fetch();
      if ($login === array()) {
        echo "<div class='alert alert-danger'>Username or
        password invalid.</div>";
        return false;
      } else {
        echo "<div class='alert alert-success'>You are now logged in as 
        ".$login->username.". <a href='index.php'>Continue</a></div>";
        $_SESSION['username'] = $login->username;
        $_SESSION['userid'] = $login->id;
        $_SESSION['rank'] = $login->rank;
      }
    }
  }

public function getUserProfile($name=null,$id=null) {
    if (isset($name)) { //Search by name
      $sql = "SELECT id, username, rank, md5(email) AS email
      FROM ls_user WHERE username = :name LIMIT 0,1";
      global $dbh;
      $user = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
      $user->execute(array(
        ':name'=>$name
      ));
      $user = $user->fetch();
      return $user;
    } elseif (isset($id)) { //Search by ID
      $sql = "SELECT * FROM ls_user WHERE id = :id LIMIT 0,1";
      global $dbh;
      $user = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
      $user->execute(array(
        ':id'=>$id
      ));
      $user = $user->fetch();
      return $user;   
    }
  }
}