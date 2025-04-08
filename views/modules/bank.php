<!-- Vertical form options -->
<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">
  <div class="col-md-8 mx-auto">
  <form role="form" id="form-bank" method="POST" autocomplete="nope">
    <div class="card" style="border:1px solid rgba(255, 255, 255, 0.1);box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.7); border-radius: 10px;">
      <!-- <div class="loader-transparent rounded"></div> -->
      <div class="card-header d-flex bg-transparent border-bottom" style="border:1px solid rgba(255, 255, 255, 0.3);box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5); border-radius: 10px;">
        <h5 class="card-title flex-grow-1 profile-header-title">BANK INFORMATION</h5> 
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
            <div class="col-sm-10 form-group">
                <label for="txt-bankname">Bank Name</label>
                <input type="text" class="form-control" id="txt-bankname" name="txt-bankname" autocomplete="nope" required>
            </div>                                           

            <div class="col-sm-2 form-group">
                <label for="txt-bankcode">Bank Code</label>
                <input type="text" class="form-control profile-code" id="txt-bankcode" name="txt-bankcode" autocomplete="nope" required readonly="true">
            </div>
        </div>

        <div class="row">                  
            <div class="col-sm-7 form-group">
                <label for="tns-bankaddress">Address</label>
                <input type="text" class="form-control" id="tns-bankaddress" name="tns-bankaddress" autocomplete="nope">
            </div>

            <div class="col-sm-5 form-group">
                <label for="tns-bankcontact">Contact Person</label>
                <input type="text" class="form-control" id="tns-bankcontact" name="tns-bankcontact" autocomplete="nope">
            </div>            
        </div>  

        <div class="row">
            <div class="col-sm-4 form-group">
                <label for="num-banklandline">Landline #</label>
                <input type="text" class="form-control" id="num-banklandline" name="num-banklandline" autocomplete="nope">
            </div>
            <div class="col-sm-4 form-group">
                <label for="num-bankmobile">Mobile #</label>
                <input type="text" class="form-control" id="num-bankmobile" name="num-bankmobile" value="" autocomplete="nope">
            </div>
            <div class="col-sm-4 form-group">
                <label for="tns-bankwebsite">Website</label>
                <input type="text" class="form-control" id="tns-bankwebsite" name="tns-bankwebsite" value="" autocomplete="nope">
            </div>            
        </div>
 
        <div class="clearfix">
          <span class="float-left">
          </span>

          <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>
          <input type="hidden" id="num-id" name="num-id">

          <span class="float-right">
            <button type="button" class="btn btn-light btn-lg" id="btn-new" ><i class="icon-file-text mr-2"></i> New</button>

            <button type="button" class="btn btn-light btn-lg" id="btn-search" data-toggle="modal" data-target="#modal-search-bank"><i class="icon-search4 mr-2"></i> Search</button>
           
            <button type="submit" class="btn btn-light btn-lg"><i class="icon-floppy-disk mr-2"></i> Save</button>
          </span>
        </div>     
      </div>  <!-- card body -->

    </div>
  </form>
    <?php
      $createBank = new ControllerBank();
      $createBank -> ctrCreateBank();

      $editBank = new ControllerBank();
      $editBank -> ctrEditBank();
    ?>
  </div>
</div>

<div id="modal-search-bank" class="modal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;BANK LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

      <div class="modal-body">
        <table class="table table-bordered table-hover table-striped datatable-responsive datatable-small-font profile-grid-header bankTable" style="width: 100%;">
          <thead>
            <tr>
              <th>Bank Name</th>
              <th>Contact Person</th>
              <th>Address</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $banks = (new ControllerBank)->ctrShowBankList();
            foreach ($banks as $key => $value) {
              echo '<tr idBank='.$value["id"].'>
                      <td>'.$value["bankname"].'</td>
                      <td>'.$value["bankcontact"].'</td>
                      <td>'.$value["bankaddress"].'</td>
                    </tr>';
              }
          ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<script src="views/js/bank.js"></script>

