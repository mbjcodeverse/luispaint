<!-- Vertical form options -->
<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">
  <div class="col-md-10 mx-auto">
  <form role="form" id="form-client" method="POST" autocomplete="nope">
    <div class="card" style="border:1px solid rgba(255, 255, 255, 0.1);box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.7); border-radius: 10px;">
      <!-- <div class="loader-transparent rounded"></div> -->
      <div class="card-header d-flex bg-transparent border-bottom" style="border:1px solid rgba(255, 255, 255, 0.3);box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5); border-radius: 10px;">
        <h5 class="card-title flex-grow-1 profile-header-title">CLIENT INFORMATION</h5> 
        <input type="hidden" id="clients-access" name="clients-access" value="<?php echo $_SESSION["clients"];?>">
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
            <div class="col-sm-4 form-group">
                <label for="txt-lname">Lastname</label>
                <input type="text" class="form-control text-capitalize" id="txt-lname" name="txt-lname" autocomplete="nope" required>
            </div>

            <div class="col-sm-4 form-group">
                <label for="tns-fname">Firstname</label>
                <input type="text" class="form-control text-capitalize" id="tns-fname" name="tns-fname" autocomplete="nope" required>
            </div>

            <div class="col-sm-1 form-group">
                <label for="txt-mi">MI</label>
                <input type="text" class="form-control text-capitalize" id="txt-mi" maxlength='1' name="txt-mi" autocomplete="nope">
            </div>                                            

            <div class="col-md-1 form-group">
                <label class="d-block font-weight-semibold">Status</label>
                <div class="custom-control custom-checkbox custom-control-inline">
                  <input type="checkbox" class="custom-control-input" id="chk-isactive" name="chk-isactive" value="1" checked>
                  <label class="custom-control-label" for="chk-isactive">Active</label>
                </div>
            </div>

            <div class="col-sm-2 form-group">
                <label for="txt-clientid">Client ID</label>
                <input type="text" class="form-control profile-code" id="txt-clientid" name="txt-clientid" autocomplete="nope" required readonly="true">
            </div>
        </div>

        <div class="row">                  
            <div class="col-sm-2 form-group">
              <label for="date-bday">B-Day</label>
              <input type="text" class="form-control datepicker" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-bday" name="date-bday">
            </div>

            <div class="col-sm-3 form-group">
                <label for="tns-corporation">Corporation</label>
                <input type="text" class="form-control" id="tns-corporation" name="tns-corporation" autocomplete="nope">
            </div>            
            
            <div class="col-sm-7 form-group">
                <label for="tns-address">Address</label>
                <input type="text" class="form-control" id="tns-address" name="tns-address" autocomplete="nope">
            </div>
        </div> 

        <div class="row">
            <div class="col-sm-2 form-group">
                <label for="num-landline">Landline</label>
                <input type="text" class="form-control" id="num-landline" name="num-landline" value="" autocomplete="nope">
            </div>

            <div class="col-sm-2 form-group">
                <label for="num-mobile">Mobile #</label>
                <input type="text" class="form-control" id="num-mobile" name="num-mobile" value="" autocomplete="nope">
            </div>

            <div class="col-sm-3 form-group">
                <label for="tns-email">E-mail</label>
                <input type="text" class="form-control" id="tns-email" name="tns-email" value="" autocomplete="nope">
            </div> 
            
            <div class="col-sm-5 form-group">
                <label for="tns-spouse">Spouse</label>
                <input type="text" class="form-control" id="tns-spouse" name="tns-spouse" value="" autocomplete="nope">
            </div>             
        </div>
 
        <div class="clearfix">
          <span class="float-left">
          </span>

          <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>
          <input type="hidden" id="num-id" name="num-id">

          <span class="float-right">
            <button type="button" class="btn btn-light btn-lg" id="btn-new" onClick="location.href='clients'"><i class="icon-file-text mr-2"></i> New</button>

            <button type="button" class="btn btn-light btn-lg" id="btn-search" data-toggle="modal" data-target="#modal-search-clients"><i class="icon-search4 mr-2"></i> Search</button>
           
            <button type="submit" class="btn btn-light btn-lg"><i class="icon-floppy-disk mr-2"></i> Save</button>
          </span>
        </div>     
      </div>  <!-- card body -->

    </div>
  </form>
    <?php
      $createClient = new ControllerClients();
      $createClient -> ctrCreateClient();

      $editClient = new ControllerClients();
      $editClient -> ctrEditClient();
    ?>
  </div>
</div>

<div id="modal-search-clients" class="modal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;CLIENT LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

      <!-- <div class="modal-body"> -->
          <table class="table datatable-scroll-y table-bordered table-striped table-hover datatable-responsive datatable-small-font profile-grid-header clientsTable" width="100%">
          <thead>
            <tr>
              <th>Lastname</th>
              <th>Firstname</th>
              <th>MI</th>
              <th>Address</th>
              <!-- <th>Landline</th>
              <th>Mobile</th>
              <th>E-mail</th> -->
            </tr>
          </thead>
          <tbody>
          <?php
            $clients = (new ControllerClients)->ctrShowEmployeesList();
            foreach ($clients as $key => $value) {
              echo '<tr idClient='.$value["id"].'>
                      <td>'.$value["lname"].'</td>
                      <td>'.$value["fname"].'</td>
                      <td>'.$value["mi"].'</td>
                      <td>'.$value["address"].'</td>
                    </tr>';
              }
          ?>
          </tbody>
        </table>
      <!-- </div> -->

    </div>
  </div>
</div>

<script src="views/js/client.js"></script>

