<!-- Page content -->
<div class="page-content" style="background-image: url('views/global_assets/images/dark_background.png'); background-size: cover; height: 100vh; display: flex; justify-content: flex-start; align-items: flex-start; margin: 0; overflow: hidden; box-sizing: border-box;">
  <div class="content-wrapper" style="width: 100%; display: flex; justify-content: center; align-items: flex-start; padding: 0; margin-top: 0;">
    <div class="content" style="width: 100%; display: flex; justify-content: center; align-items: flex-start;">
      <!-- Login form -->
      <form class="login-form" method="post" autocomplete="off" style="max-width: 450px; width: 100%; padding: 20px; background: linear-gradient(135deg, #007bff, #9bff6e); border-radius: 10px; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.6), 0px 8px 24px rgba(0, 0, 0, 0.4); box-sizing: border-box; position: relative;">
        <div class="card mb-0" style="border-radius: 10px; border: none;">
          <div class="card-body">
            <div class="text-center mb-3">
              <img src="views/global_assets/images/blplogo.png" height="120">
              <!-- <h5 class="mb-0 paint-text" style="font-size: 2rem; font-weight: 600; color: #f8f9fa;">BACOLOD LUIS PAINT</h5> -->
              <h5 class="mb-0" style="font-size: 2rem; font-weight: 600; color: transparent; background-image: linear-gradient(45deg, #f7e1ff, #b3f0ff, #f0f8d2, #ffebcc, #f7f7f7); background-clip: text; -webkit-background-clip: text;">BACOLOD LUIS PAINT</h5>
              <span class="d-block text-muted" style="font-size: 1.2rem; color: #9bff6e;">Enter your credentials below</span>
            </div>
            <div class="form-group form-group-feedback form-group-feedback-left" style="margin-bottom: 20px; position: relative;">
              <input type="text" class="form-control" placeholder="Username" name="loginUser" id="loginUser" autocomplete="none" required style="padding-left: 40px; padding-right: 20px; font-size: 1.2rem; border-radius: 8px; border: 1px solid #ccc; box-sizing: border-box;">
              <div class="form-control-feedback" style="position: absolute; top: 50%; left: 5px; transform: translateY(-50%); color: #888;">
                <i class="icon-user"></i>
              </div>
            </div>
            <div class="form-group form-group-feedback form-group-feedback-left" style="margin-bottom: 20px; position: relative;">
              <input type="password" class="form-control" placeholder="Password" name="loginPass" required style="padding-left: 40px; padding-right: 20px; font-size: 1.2rem; border-radius: 8px; border: 1px solid #ccc; box-sizing: border-box;">
              <div class="form-control-feedback" style="position: absolute; top: 50%; left: 5px; transform: translateY(-50%); color: #888;">
                <i class="icon-lock2"></i>
              </div>
            </div>
            <div class="form-group" style="margin-bottom: 30px;">
              <button type="submit" class="btn btn-primary btn-block sign-in-btn" style="font-size: 1.4rem; font-weight: bold; padding: 15px; border-radius: 8px; background-color: #007bff; border: none; color: #fff; box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3); transition: all 0.3s ease-in-out; box-sizing: border-box;">
                Sign in <i class="icon-circle-right2 ml-2"></i>
              </button>
            </div>
            <div class="text-center">
              <a href="login_password_recover.html" style="font-size: 1.2rem; color: #7edcfc;">Forgot password?</a>
            </div>
          </div>
        </div>
        <?php
          $login = new ControllerUserRights();
          $login -> ctrUserLogin();
        ?>
      </form>
    </div>
  </div>
</div>
<!-- /page content -->

<script>
  $("#loginUser").focus();
</script>

<style>
  /* Gradient effect for the "Sign in" button */
  .sign-in-btn {
    background: linear-gradient(135deg, #FF6A00, #D600FF); /* Bright orange to purple gradient */
    border-radius: 8px;
    font-size: 1.4rem;
    font-weight: bold;
    padding: 15px;
    color: white;
    border: none;
    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease-in-out;
  }

  .sign-in-btn:hover {
    background: linear-gradient(135deg, #D600FF, #FF6A00); /* Reverse the gradient on hover */
    transform: scale(1.05); /* Slight zoom effect on hover */
  }

  .paint-text {
    font-size: 2rem;
    font-weight: 600;
    color: transparent;
    background-image: linear-gradient(45deg, #ffcc00, #ff80ab, #33ccff, #8a2be2, #ffff00); /* Bright pastel gradient colors */
    background-clip: text; /* Clip the background to text */
    -webkit-background-clip: text; /* Ensure compatibility in WebKit-based browsers */
    color: transparent; /* Make text transparent to show background */
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.4); /* Subtle shadow for depth */
  }
</style>
