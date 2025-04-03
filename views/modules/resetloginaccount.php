<!-- Vertical form options -->
<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">
  <div class="col-md-3 mx-auto">
  <form role="form" id="form-resetaccount" method="POST" autocomplete="nope">
    <div class="card" style="border:1px solid rgba(255, 255, 255, 0.1);box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.7); border-radius: 10px;">
      <!-- <div class="loader-transparent rounded"></div> -->
      <div class="card-header d-flex bg-transparent border-bottom" style="border:1px solid rgba(255, 255, 255, 0.3);box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5); border-radius: 10px;">
        <h5 class="card-title flex-grow-1 profile-header-title">RESET ACCOUNT</h5>
        <input type="hidden" id="txt-userid" name="txt-userid" required> 
        <div class="header-elements">
          <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
            <!-- <a class="list-icons-item" data-action="reload"></a> -->
            <a class="list-icons-item" data-action="remove"></a>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="loginUser">Username</label>
                    <div class="form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" placeholder="Temporary User Name" name="temp-username" id="temp-username" value="" autocomplete="none">
                        <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="loginPass">Password</label>
                    <div class="form-group-feedback form-group-feedback-left" style="position: relative;">
                        <input type="password" class="form-control" placeholder="Temporary Password" name="temp-password" id="temp-password" value="" style="margin-bottom:4px; padding-right: 30px;" required>
                        <div class="form-control-feedback">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                        <span id="temp-toggle-eye" style="cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-size: 18px; color: #555;">&#128065;</span> <!-- Eye icon -->
                    </div>
                    <!-- <div class="form-group-feedback form-group-feedback-left">
                        <input type="password" class="form-control" placeholder="Temporary Password" name="temp-password" id="temp-password" value="@j9pw9!1%iPc">
                        <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                        </div>
                    </div> -->
                </div>

                <div class="form-group">
                   <button type="button" class="btn btn-warning btn-block" id="btn-validate">VALIDATE <i class="icon-circle-right2 ml-2"></i></button>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label for="loginUser">Username</label>
                    <div class="form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" placeholder="New User Name" name="new-username" id="new-username" autocomplete="none" value="" required>
                        <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="loginPass">Password</label>
                    <div class="form-group-feedback form-group-feedback-left" style="position: relative;">
                        <input type="password" class="form-control" placeholder="New Password" name="new-password" id="new-password" value="" style="margin-bottom:4px; padding-right: 30px;" required>
                        <div class="form-control-feedback">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                        <span id="toggle-eye" style="cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-size: 18px; color: #555;">&#128065;</span> <!-- Eye icon -->
                    </div>

                    <div id="password-strength" style="width: 100%; height: 3px; background-color: #f3f3f3; border-radius: 5px; margin-bottom: 10px;">
                       <div id="strength-level" style="height: 100%; width: 0; border-radius: 5px;"></div>
                    </div>

                    <p id="password-strength-label" style="font-size: 14px; color: white; text-transform: none;font-style: italic;"></p>
                </div>

                <div class="form-group">
                   <button type="button" class="btn btn-success btn-block" id="btn-resetaccount">Reset<i class="icon-circle-right2 ml-2"></i></button>
                </div>
            </div>
        </div> 
      </div>  <!-- card body -->

    </div>
  </form>
  </div>
</div>

<script src="views/js/resetloginaccount.js"></script>

