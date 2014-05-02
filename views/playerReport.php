<div class="col-md-6">
  <h2>Add new report</h2>
  <form role="form" action="index.php?action=viewPlayer&player=<?php echo $data->id;?>" method="POST">
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
        <input type="checkbox" value="true" name="permanent" id="permanent" 
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
  <script>
  $('#banned').click(function(){
    if($('#banned').is(':checked')) {
      $('#permanent').attr('disabled',false);
    } else {
      $('#permanent').attr('disabled',true);
    }
  });
  </script>      
</div>