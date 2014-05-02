<div class="row">
  <div class="col-md-4">
    <h2>Register</h2>
    <form role="form" action="index.php?action=register" method="POST"
    class="register">
      <div class="form-group">
        <input required="" class="form-control" type="text" placeholder="Username" name="username">
      </div>
      <div class="form-group">
        <input required="" class="form-control" type="email" placeholder="Email" name="email">
      </div>
      <div class="form-group">
        <input required="" class="form-control" type="password" placeholder="Password" name="password">
      </div>
      <div class="form-group">
        <input required="" class="form-control" type="password" placeholder="Password Again" name="password-again">
      </div>
      <div class="checkbox">
        <label>
            <input type="checkbox" name="spamcheck" value="1"> I am not a spambot
          </label>
      </div>
  <button type="submit" class="btn btn-primary btn-block">Register</button>
</form>
  </div>
  <div class="col-md-4">
    <h2>Log In</h2>
    <form role="form" action="index.php?action=login" method="POST">
  <div class="form-group">
    <input required="" class="form-control" type="text" placeholder="Username" name="username">
  </div>
  <div class="form-group">
    <input required="" class="form-control" type="password" placeholder="Password" name="password">
  </div>
  <button type="submit" class="btn btn-primary btn-block">Log in</button>
</form>
 </div>
  <div class="col-md-4">
    <h2><?php echo SITE_NAME; ?></h2>
    <p>An app for tracking administrative interactions with players.</p>
  </div>
</div>
