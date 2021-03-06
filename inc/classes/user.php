<?php

namespace Logsite;

use HipChat\HipChat as Hipchat;

class user {

  public function isLoggedIn() {
    if ((isset($_SESSION['username'])) && (isset($_SESSION['userid'])) && $_SESSION['status'] == 1) {
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
    } 
  }

  public function registerNewUser($username, $password, $email) {
    if($this->isUnique($username, $password)) {
      $sql = "INSERT INTO ls_user
      (username, password, email, salt, timestamp) VALUES 
      (:username, :password, :email, :salt, NOW())";
      global $dbh;
  
      $site = new site();
      $salt = $site->getSalt();
  
      $newUser = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
      $newUser->execute(array(
        ':username'=>$username,
        ':password'=>hash('sha512', $salt . $password),
        ':email'=>$email,
        ':salt'=>$salt
      ));
      echo "<div class='alert alert-success'>You are now registered.
      <a href='index.php'>Please log in</a></div>";
      if ($site->countRows('ls_user') == 1) {
        $this->makeAdmin($count->id);
        $this->activateUser($count->id);
      }
      if (HIPCHAT == true) {
        $hc = new HipChat(HIPCHAT_TOKEN);
        $url = SITE_URL."/index.php";
        $hc->message_room(HIPCHAT_ROOM, SITE_NAME, "User <a href='".$url."?action=listUsers'>".$username."</a> has registered and requires activation.", true, 'gray');
      }
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
      $sql = "SELECT id, username, email, rank, status FROM ls_user
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
        $_SESSION['username'] = $login->username;
        $_SESSION['userid'] = $login->id;
        $_SESSION['rank'] = $login->rank;
        $_SESSION['status'] = $login->status;
        if ($login->status == 0) {
          echo "<div class='alert alert-info'>You are now logged in as 
        ".$login->username.". The site administrator must activate your account before you can continue.</div>";
        } else {
          echo "<div class='alert alert-success'>You are now logged in as 
        ".$login->username.". <a href='index.php'>Continue</a></div>";
        }
        return true;
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
      return $user->id;
    } elseif (isset($id)) { //Search by ID
      $sql = "SELECT * FROM ls_user WHERE id = :id LIMIT 0,1";
      global $dbh;
      $user = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
      $user->execute(array(
        ':id'=>$id
      ));
      $user = $user->fetch();
      return $user->username;   
    }
  }

  public function listUsers() {
    $sql = "SELECT ls_user.id,
            ls_user.username,
            ls_user.email,
            ls_user.timestamp,
            ls_user.rank,
            ls_user.status,
            COUNT(ls_reports.user) AS reports
            FROM ls_user
            LEFT JOIN ls_reports ON ls_user.id = ls_reports.user
            GROUP BY ls_reports.user";
    global $dbh;
    $users = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $users->execute();
    return $users->fetchAll();
  }

  private function makeAdmin($id) {
    $sql = 'UPDATE ls_user SET rank = "A" WHERE id = :user';
    global $dbh;
    $admin = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
    $admin->execute(array(
      ':user'=>$id
    ));
  }
  public function activateUser($id) {
    if ($this->isAdmin()){
      $sql = "UPDATE ls_user SET status = 1 WHERE id = :user";
      global $dbh;
      $approve = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
      $approve->execute(array(
        ':user'=>$id
      ));
      if ($id != $_SESSION['userid']) {
        $name = $this->getUserProfile(NULL,$id);
        $sql = "DELETE FROM ls_session WHERE session_data LIKE '%".$name."%'";
        $approve = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
        $approve->execute();
      }
      echo "<div class='alert alert-success'>User has been activated.</div>";
    } else {
      echo "<div class='alert alert-danger'>Users can only be activated by administrators.</div>";
    }
  }
  public function deactivateUser($id) {
    if ($this->isAdmin()){
      if ($id != $_SESSION['userid']) {
        $sql = "UPDATE ls_user SET status = 0 WHERE id = :user";
        global $dbh;
        $approve = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
        $approve->execute(array(
          ':user'=>$id
        ));
        $name = $this->getUserProfile(NULL,$id);
        $sql = "DELETE FROM ls_session WHERE session_data LIKE '%".$name."%'";
        $approve = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
        $approve->execute();
        echo "<div class='alert alert-success'>User has been deactivated.</div>";
      } else {
        echo "<div class='alert alert-danger'>You cannot deactivate yourself.</div>";
      }
    } else {
      echo "<div class='alert alert-danger'>Users can only be deactivated by administrators.</div>";
    }
  }
}