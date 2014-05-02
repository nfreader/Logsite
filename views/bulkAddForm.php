
<?php
if ($user->isAdmin()) {
  if (isset($_POST['playerlist'])) {
    $players = explode(",",$_POST['playerlist']);
    //print_r($players);
    $add = $player->addNewPlayer($players);
    echo "<div class='alert alert-info'>Added $add players</div>";
  } else { ?>

<div class="row">
  <div class="col-md-12">
    <h2>Bulk add players
    <small>Format: $player1,$player2,$player3 etc</small></h2>
    <form role="form" action="index.php?action=bulkAdd" method="POST">
      <div class="form-group">
        <textarea required="true" rows="10" class="form-control" type="text"
        placeholder="Your list of players,
        separated by a comma" name="playerlist"></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Add</button>
    </form>
  </div>
</div>

<?php

  }
} else {
  echo "<div class='alert alert-danger'>You must be an administrator to do this.</div>";
}

?>
