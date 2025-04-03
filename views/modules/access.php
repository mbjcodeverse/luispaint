<!-- Vertical form options -->
<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">
  <div class="col-md-4 mx-auto">
  <form role="form" id="form-userrights" method="POST" autocomplete="nope">
    <div class="card" style="border:1px solid rgba(255, 255, 255, 0.1);box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.7); border-radius: 10px;">
      <!-- <div class="loader-transparent rounded"></div> -->
      <div class="card-header d-flex bg-transparent border-bottom" style="border:1px solid rgba(255, 255, 255, 0.3);box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5); border-radius: 10px;">
        <h5 class="card-title flex-grow-1 profile-header-title">USER ACCESS RIGHTS</h5>
        <input type="hidden" name="trans_type" id="trans_type" value="New" required>
        <input type="hidden" id="txt-userid" name="txt-userid" required> 
        <input type="hidden" id="txt-username" name="txt-username" required> 
        <input type="hidden" id="txt-upassword" name="txt-upassword" required> 
        <input type="hidden" id="accessprivilege-access" name="accessprivilege-access" value="<?php echo $_SESSION["accessprivilege"];?>">
        <input type="hidden" id="superadmin-access" name="superadmin-access" value="<?php echo $_SESSION["userid"];?>">

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
                <label for="sel-empid">USER FULL NAME</label>
                <select class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-empid" name="sel-empid" required>
                <option value="" selected hidden disabled>&lt;&nbsp;Select Employee&nbsp;&gt;</option>
                <?php
                    $item = null;
                    $value = null;
                    $employee = (new ControllerEmployees)->ctrShowEmployees($item, $value);
                    foreach ($employee as $key => $value) {
                      echo '<option value="'.$value["empid"].'">'.$value["lname"].', '.$value["fname"].'</option>';
                    }
                ?>
                </select>              
            </div>
        </div>  

        <br>

        <div class="row">                  
            <div class="col-sm-6 form-group">
              <label for="sel-invoices" style="color:lightgreen;">INVOICES</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-invoices" name="sel-invoices" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-6 form-group" style="color:lemonchiffon;">
              <label for="sel-clients">CLIENTS</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-clients" name="sel-clients" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>
        </div> 

        <div class="row">                  
            <div class="col-sm-6 form-group" style="color:lightgreen;">
              <label for="sel-receivable">RECEIVABLE</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-receivable" name="sel-receivable" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-6 form-group" style="color:lemonchiffon;">
              <label for="sel-employees">EMPLOYEES</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-employees" name="sel-employees" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>
        </div>

        <div class="row">                  
            <div class="col-sm-6 form-group">
              <label for="sel-reports" style="color:lightgreen;">REPORTS</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-reports" name="sel-reports" required>
                <option></option>
                <option value="ViewAll">View All</option>
                <option value="InvoicesOnly">Invoices Only</option>
                <option value="ReceivableOnly">Receivable Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-6 form-group" style="color:lemonchiffon;">
              <label for="sel-branch">BRANCH</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-branch" name="sel-branch" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>
        </div>

        <div class="row">                  
            <div class="col-sm-6 form-group" style="color:lightgreen;">
              <label for="sel-dashboard">DASHBOARD</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-dashboard" name="sel-dashboard" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>

            <div class="col-sm-6 form-group" style="color:pink;">
              <label for="sel-accessprivilege">ACCESS PRIVILEGE</label>
              <select data-placeholder="Access Type" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-accessprivilege" name="sel-accessprivilege" required>
                <option></option>
                <option value="Full">Full</option>
                <option value="ViewOnly">View Only</option>
                <option value="Restricted">Restricted</option>
              </select>
            </div>
        </div>

        <div class="clearfix">
          <span class="float-left">
          </span>

          <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>
          <input type="hidden" id="num-id" name="num-id">

          <span class="float-right">
            <button type="button" class="btn btn-light btn-lg" id="btn-new"><i class="icon-file-text mr-2"></i> New</button>

            <button type="button" class="btn btn-light btn-lg" id="btn-search" data-toggle="modal" data-target="#modal-search-users"><i class="icon-search4 mr-2"></i> Search</button>
           
            <button type="button" class="btn btn-light btn-lg" id="btn-save"><i class="icon-floppy-disk mr-2"></i> Save</button>
          </span>
        </div>
      </div>  <!-- card body -->
    </div>
  </form>
  </div>
</div>

<div id="modal-search-users" class="modal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;USERS LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

      <!-- <div class="modal-body"> -->
          <table class="table datatable-scroll-y table-bordered table-striped table-hover datatable-responsive datatable-small-font profile-grid-header userRightsTable" width="100%">
          <thead>
            <tr>
              <th>Lastname</th>
              <th>Firstname</th>
              <th>MI</th>
              <th>Designation</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $users = (new ControllerUserRights)->ctrShowUserList();
            foreach ($users as $key => $value) {
              if (($_SESSION["userid"] == 'U0001') || ($value["userid"] != 'U0001')){  // do show me in the accessprivilege search - if it's not me logging in to the system
                echo '<tr userId='.$value["userid"].'>
                        <td>'.$value["lname"].'</td>
                        <td>'.$value["fname"].'</td>
                        <td>'.$value["mi"].'</td>
                        <td>'.$value["positiondesc"].'</td>
                      </tr>';
              }
            }
          ?>
          </tbody>
        </table>
      <!-- </div> -->

    </div>
  </div>
</div>

<script src="views/js/access.js"></script>

