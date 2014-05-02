<?php

namespace Logsite;

//define('TBL_PREFIX','ls_');

class session implements \SessionHandlerInterface {

    public function __construct() {
      session_set_save_handler(
            array(&$this, 'open'),
            array(&$this, 'close'),
            array(&$this, 'read'),
            array(&$this, 'write'),
            array(&$this, 'destroy'),
            array(&$this, 'gc')
        );
      session_start();
      register_shutdown_function('session_write_close');
    }

    public function open($savePath, $session_name) {
        $sql = "INSERT INTO ls_session
        SET session_id = :sessionName,
        session_data = ''
        ON DUPLICATE KEY UPDATE session_lastaccesstime = NOW()";
        global $dbh;
        $sess = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
        $sess->execute(array(
          ':sessionName'=>$session_name
        ));
        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($id)
    {
        $sql = "SELECT * FROM ls_session where session_id = :id";
        global $dbh;
        $sess = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
        if ($sess->execute(array(':id'=>$id))) {
          $result = $sess->fetch(\PDO::FETCH_ASSOC);
          return $result["session_data"];
        }
        return '';
    }

    public function write($id, $data) {
      if ($data == null) {
        return true;
      }
      $sql = "INSERT INTO ls_session 
      SET session_id = :id, 
      session_data = :data
      ON DUPLICATE KEY UPDATE session_data = :data";
      global $dbh;
      $sess = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
      $sess->execute(array(
        ':id'=>$id,
        ':data'=>$data
      ));
      //session_write_close();
    }

    public function destroy($id) {
      $sql = "DELETE FROM ls_session WHERE session_id = :id";
      global $dbh;
      $sess = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
      $sess->execute(array(
        ':id'=>$id
      ));
      return true;
    }

    public function gc($maxlifetime) {
      $sql = "DELETE FROM ls_session WHERE session_lastaccesstime < DATE_SUB(NOW(), INTERVAL " . $lifetime . " SECOND)";
      global $dbh;
      $sess = $dbh->prepare(str_replace('ls_', TBL_PREFIX, $sql));
      $sess->execute();
      return true;
    }
}