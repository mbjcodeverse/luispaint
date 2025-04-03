<!-- Main navbar -->
  <div class="navbar navbar-expand-md navbar-light fixed-top" style="border-bottom: 2px solid rgba(255, 255, 255, 0.2);box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);">
    <div>
      <a href="" class="d-inline-block">
        <img src="views/global_assets/images/tdcacronym.png" alt="">
      </a>
    </div>

    <div class="d-md-none">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
        <i class="icon-tree5"></i>
      </button>
      <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
        <i class="icon-paragraph-justify3"></i>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
            <i class="icon-paragraph-justify3"></i>
          </a>
        </li>
      </ul>
        <table width="100%">
          <tr>
            <td width="80%">
              <h2 class="card-title my-3 my-md-0 ml-md-3 mr-md-auto" style="font-size: 1.5em;text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.8);">
                <span style="color:cornsilk;font-family: 'Arial', sans-serif;">BACOLOD LUIS PAINT</span> 
                <span style="color:aqua;">&nbsp;ONLINE ACCOUNTS RECEIVABLE</span>
              </h2> 
            </td>
          </tr>
        </table>
        <!-- </div> -->
      <ul class="navbar-nav">
        <p style="padding-top: 11px;padding-right: 14px;color:#fff2fe;font-size: 1.5em;" id="current_date"></p>
 
        <li class="nav-item dropdown dropdown-user">
          <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
            <?php 
              if ($_SESSION["photo"] != "") {
                echo '<img src="'.$_SESSION["photo"].'"class="rounded-circle mr-2" height="34" alt="">';
              }else{
                echo '<img class="rounded-circle mr-2" height="34" alt="" src="views/img/users/default/anonymous.png">';
              }
            ?>

            <?php
              $table = 'employees';
              $item = 'empid';
              $value = $_SESSION["empid"];
              $employee = (new ControllerEmployees)->ctrShowEmployees($item, $value);
              $employee_fname = $employee["fname"];
            ?>
            <span style="font-size: 1.3em;padding-bottom: 0;padding-top: 5px;"><?php echo $employee_fname; ?></span>
          </a>

          <div class="dropdown-menu dropdown-menu-right">
            <a href="logout" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </div>

  <script src="views/js/navbar.js"></script> 