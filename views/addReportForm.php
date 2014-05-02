<?php 
if ((isset($_POST['contactType']))) {
  if (empty($_POST['permanent'])) {
    $perma = false;
  } elseif (isset($_POST['permanent'])) {
    $perma = true;
  }
  $contact = new Logsite\contact();
  $contact->newReport($_POST['player'],$_POST['contactType'],$_POST['notes'],
  $perma);
  include 'home.php';
} else {

?>
<div class="row">
  <div class="col-md-12">
    <h2>Add new report</h2>
    <form role="form" action="index.php?action=addReport" method="POST">
      <div class="form-group">
        <label for="player">Player</label>
        <select class="selectpicker form-control"
          required="true" data-live-search="true" name="player"
          title="Select a player">
          <?php 
          $playerlist = $player->getPlayerList();
          foreach ($playerlist as $option) {
            echo "<option value='".$option->id."'>".$option->name."</option>";
          } 
          ?>
        </select>
      </div>
       <p><strong>Contact Type</strong></p>
       <div class="radio">
         <label class='alert alert-success'>
           <input type="radio" name="contactType" id="contactType" value="C" />
           Contacted
         </label>
       </div>
       <div class="radio">
          <label class='alert alert-warning'>
           <input type="radio" name="contactType" id="contactType" value="W"/>
           Warned
          </label>
        </div>
        <div class="radio">
          <label class='alert alert-danger'>
           <input type="radio" name="contactType" id="banned" value="B"/>
           Banned
          </label>
       </div>
      <div class="checkbox">
        <label class='alert alert-danger'>
          <input type="checkbox" value="1" name="permanent" id="permanent" 
          disabled >
            This is a permanent ban
        </label>
      </div>
       <div class="form-group">
        <textarea required="true" rows="10" class="form-control" type="text"
        placeholder="Notes" name="notes"></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Add</button>
    </form>
  </div>
</div>
<?php } ?>
<script>
// $('#banned').change(function(){
//   if($(this).val() == 'B') {
//     $('#permanent').attr('disabled',false);
//   } else {
//     $('#permanent').attr('disabled',true);
//   }
// });
$('#banned').click(function(){
  if($('#banned').is(':checked')) {
    $('#permanent').attr('disabled',false);
  } else {
    $('#permanent').attr('disabled',true);
  }
});
</script>
