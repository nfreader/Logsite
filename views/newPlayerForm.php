<?php
  if (isset($_POST['player'])) {
    $meta = array();
    $meta['IP'] = $_POST['ip'];
    $meta['email'] = $_POST['email'];
    $player = new Logsite\player();
    $player->addNewPlayer($_POST['player']);
    $playerid = $player->getId($_POST['player']);
    $player->addPlayerMeta($playerid, $meta);
  }
?>

<div class="row">
  <div class="col-md-12">
    <form role="form" action="index.php?action=newPlayer" method="POST">
    <h3>Player Name</h3>
      <div class="form-group">
        <input type="text" name="player" class="form-control" placeholder="Name" />
      </div>
      <h3>Metadata</h3>
      <div class="form-group">
        <label for="ip">IP Address</label>
        <input type="text" name="ip" class="form-control" placeholder="IP Address" />
      </div>
      <div class="form-group">
        <label for="ip">Email Address</label>
        <input type="text" name="email" class="form-control" placeholder="user@example.com" />
      </div>
      <button type="submit" class="btn btn-primary btn-block">Add</button>
    </form>
  </div>
</div>