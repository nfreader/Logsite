<?php
  $site = new Logsite\site();
  $metaKeys = $site->getMetaKeys();
  //print_r($metaKeys);
  if (isset($_GET['key'])) {
    $players = $player->searchPlayerMeta($_GET['key'],$_POST['data']);
    playerListTable($players);
  }
  if (isset($_POST['namesearch'])) {
    $players = $player->searchByName($_POST['namesearch']);
    playerListTable($players);
  }
?>

<div class="row">
  <div class="col-md-6">
    <div class="page-header"><h1>Search by player name</h1></div>
      <form class="form" action="?action=findPlayer" method="POST">
        <div class="form-group input-group">
        <input type="text" class="form-control" name="namesearch" placeholder="Player Name" />
        <span class="input-group-btn">
          <button class="btn btn-primary" type="submit">Search</button>
        </span>
      </div>
    </form> 
  </div>
    <div class="col-md-6">
    <div class="page-header"><h1>Search known meta keys</h1></div>
    <?php foreach($metaKeys as $key) {?> 
    <div class="page-header"><h2>Search by <?php echo $key->key; ?></h2></div>
      <form class="form" action="?action=findPlayer&key=<?php echo $key->key; ?>" method="POST">
        <div class="form-group input-group">
        <input type="text" class="form-control" name="data" placeholder="<?php echo $key->key; ?>" />
        <span class="input-group-btn">
          <button class="btn btn-primary" type="submit">Search</button>
        </span>
      </div>
    </form> 
    <?php } ?>
  </div>
</div>
