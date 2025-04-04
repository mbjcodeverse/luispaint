<!-- Page content -->
<!-- <div class="page-content" style="background-image: url('views/global_assets/images/dark_background.png');background-size: cover;"> -->
<div class="page-content">  
  <!-- Main content -->
  <div class="content-wrapper">
    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center pt-0">
      <!-- Login form -->
      <form class="login-form" method="post" autocomplete="nope" style="opacity:0.93;">
        <div class="card mb-0" style="box-shadow: 4px 4px 8px 1px rgba(0, 0, 0, 0.4);">
          <div class="card-body">
            <div class="text-center mb-3">
              <img src="views/global_assets/images/blplogo.png" height="100">
              <br>
              <br>

              <!-- <i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i> -->
              <h5 class="mb-0">Login your web account</h5>
              <span class="d-block text-muted">Enter your credentials below</span>
            </div>
            <div class="form-group form-group-feedback form-group-feedback-left">
              <input type="text" class="form-control" placeholder="Username" name="loginUser" id="loginUser" autocomplete="none" value="" required>
              <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
              </div>
            </div>
            <div class="form-group form-group-feedback form-group-feedback-left">
              <input type="password" class="form-control" placeholder="Password" name="loginPass" value="" required>
              <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
              </div>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 ml-2"></i></button>
            </div>
            <div class="text-center">
              <a href="login_password_recover.html">Forgot password?</a>
            </div>
          </div>
        </div>
        <?php
          // $login = new ControllerUsers();
          // $login -> ctrUserLogin();

          $login = new ControllerUserRights();
          $login -> ctrUserLogin();
        ?>
      </form>
      <!-- /login form -->
    </div>
    <!-- /content area -->
  </div>
  <!-- /main content -->
</div>
<!-- /page content -->

<script>
  $("#loginUser").focus();
</script>  