<!-- Vertical form options -->
<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">
  <div class="col-md-9 mx-auto">
  <form role="form" id="form-sales" method="POST" autocomplete="nope">
    <div class="card" style="border:1px solid rgba(255, 255, 255, 0.1);box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.7); border-radius: 10px;">
      <div class="card-header d-flex bg-transparent border-bottom" style="border:1px solid rgba(255, 255, 255, 0.3);box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5); border-radius: 10px;">
        <h5 class="card-title flex-grow-1 profile-header-title">SALES INFORMATION</h5> 

        <input type="hidden" name="txt-postedby" id="txt-postedby" value="<?php echo $_SESSION["empid"];?>">
        <input type="hidden" name="trans_type" id="trans_type" value="New" required>
        <input type="hidden" name="current_receipt" id="current_receipt" value="">

        <div class="header-elements">
          <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
            <a class="list-icons-item" data-action="remove"></a>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
            <div class="col-sm-6 form-group">
              <label for="sel-customercode">Client Name</label>
              <select class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-customercode" name="sel-customercode" required>
                <option value="" selected hidden disabled>&lt;&nbsp;Select Client&nbsp;&gt;</option>
                <?php
                    $clients = (new ControllerClients)->ctrShowActiveCustomerList();
                    foreach ($clients as $key => $value) {
                    echo '<option value="'.$value["customercode"].'">'.$value["name"].'</option>';
                    }
                 ?>
              </select>
            </div> 

            <div class="col-sm-2 form-group">
                <label for="date-sdate">Date</label>
                <input type="text" class="form-control datepicker" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-sdate" name="date-sdate">
            </div>                                           

            <div class="col-sm-2 form-group">
                <label for="txt-status">Status</label>
                <input type="text" class="form-control" id="txt-status" name="txt-status" autocomplete="nope" required readonly="true">
            </div>

            <div class="col-sm-2 form-group">
                <label for="txt-invno">Invo Code</label>
                <input type="text" class="form-control profile-code" id="txt-invno" name="txt-invno" autocomplete="nope" required readonly="true">
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2 form-group">
                <label for="txt-receiptnum">Receipt #</label>
                <input type="text" class="form-control" id="txt-receiptnum" name="txt-receiptnum" autocomplete="nope">
            </div>

            <div class="col-sm-2 form-group">
                <label for="sel-salemode">Sale Mode</label>
                <select data-placeholder="Select Mode" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-salemode" name="sel-salemode" required>
                    <option></option>
                    <option value="Delivery">Delivery</option>
                    <option value="Charge">Charge</option>
                </select>
            </div>

            <div class="col-sm-2 form-group">
            </div>

            <div class="col-sm-2 form-group">
                <label for="num-amount">Amount</label>
                <input type="text" style="font-size:1.2em;padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control qty numeric" name="num-amount" id="num-amount" value="0.00" required>
            </div> 
            
            <div class="col-sm-2 form-group">
                <label for="num-discount">Adjustment</label>
                <input type="text" style="font-size:1.2em;padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control qty numeric" name="num-discount" id="num-discount" value="0.00" required>
            </div>            

            <div class="col-sm-2 form-group">
                <label for="num-netamount">Net Amount</label>
                <input type="text" style="font-size:1.2em;padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control qty numeric" name="num-netamount" id="num-netamount" value="0.00" required disabled>
            </div>            
        </div>

        <div class="row" style="margin-top:-15px;">
            <div class="col-sm-12 form-group">
                <label for="txt-remarks">Remarks</label>
                <input type="text" class="form-control" id="txt-remarks" name="txt-remarks" autocomplete="nope">
            </div> 
        </div>

        <div class="clearfix">
          <span class="float-left">
            <div class="custom-control custom-checkbox custom-control-inline" style="margin-top:10px;color:#cafa9d;">
              <input type="checkbox" class="custom-control-input" id="chk-lockclient" name="chk-lockclient" value="0">
              <label class="custom-control-label" for="chk-lockclient">Lock Client</label>
            </div>

            <div class="custom-control custom-checkbox custom-control-inline" style="margin-top:10px;color:#f59471;">
              <input type="checkbox" class="custom-control-input" id="chk-lockdate" name="chk-lockdate" value="0">
              <label class="custom-control-label" for="chk-lockdate">Lock Date</label>
            </div>

            <div class="custom-control custom-checkbox custom-control-inline" style="margin-top:10px;color:#f59471;">
              <input type="checkbox" class="custom-control-input" id="chk-locksalemode" name="chk-locksalemode" value="0">
              <label class="custom-control-label" for="chk-locksalemode">Lock Mode</label>
            </div>
          </span>

          <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>
          <input type="hidden" id="num-id" name="num-id">

          <span class="float-right">
            <button type="button" class="btn btn-light btn-lg" id="btn-new"><i class="icon-file-text mr-2"></i> New</button>

            <button type="button" class="btn btn-light btn-lg" id="btn-search" data-toggle="modal" data-target="#modal-search-sales"><i class="icon-search4 mr-2"></i> Search</button>
           
            <button type="button" class="btn btn-light btn-lg" id="btn-save"><i class="icon-floppy-disk mr-2"></i> Save</button>

            <button type="button" class="btn btn-light btn-lg" id="btn-void" style="color:#fc6fcf;"><i class="icon-database-remove mr-2"></i> Void</button>
          </span>
        </div>     
      </div>  <!-- card body -->

    </div>
  </form>
  </div>
</div>

<div id="modal-search-customer" class="modal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;CUSTOMER LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

      <!-- <div class="modal-body"> -->
        <table class="table datatable-basic table-bordered table-hover datatable-small-font profile-grid-header customerTable" width="100%">
          <thead>
            <tr>
              <th>Customer Name</th>
              <th>Contact Person</th>
              <th>Mobile</th>
              <th>Landline</th>
              <th>Fax #</th>
              <th>Limit</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $customers = (new ControllerClients)->ctrShowCustomerList();
            foreach ($customers as $key => $value) {
              echo '<tr idCustomer='.$value["id"].'>
                      <td>'.$value["name"].'</td>
                      <td>'.$value["contactperson"].'</td>
                      <td>'.$value["mobile"].'</td>
                      <td>'.$value["landline"].'</td>
                      <td>'.$value["faxnum"].'</td>
                      <td>'.number_format($value["creditlimit"], 2).'</td>
                    </tr>';
              }
          ?>
          </tbody>
        </table>
      <!-- </div> -->

    </div>
  </div>
</div>

<!-- ============== Sales List ============ -->
<div id="modal-search-sales" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name" style="margin-top:-3px;"><i class="icon-menu7 mr-2"></i> &nbsp; SALES LIST INFORMATION&nbsp;&nbsp;&nbsp;&nbsp;</h5>
        <!-- <button type="button" class="btn btn-light btn-sm" id="btn-print" style="margin-top:-5px;color:#f3fcb6;border-radius: 12px;"><i class="icon-printer"></i> &nbsp;Print Invoices</button> -->
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>
          <!-- -25px - reduces gap between row with comboboxes and table below -->
          <div class="row" pb-0 style="margin:10px;margin-bottom: -25px;">  
            <div class="col-sm-3 form-group">
                <label for="lst-customercode" id="lbl-lst-customercode" style="color:aqua;">= &gt; Client</label>
                <select class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="lst-customercode" name="lst-customercode">
                    <option value="" selected hidden disabled>&lt;&nbsp;Select Client&nbsp;&gt;</option>
                    <?php
                        $customers = (new ControllerClients)->ctrShowCustomerList();
                        foreach ($customers as $key => $value) {
                          echo '<option value="'.$value["customercode"].'">'.$value["name"].'</option>';
                        }
                    ?>
                </select>
            </div>

            <div class="col-sm-2 form-group">
                <label for="lst-salemode" id="lbl-lst-salemode" style="color:aqua;">= &gt; Sale Mode</label>
                <select data-placeholder="Select Mode" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-salemode" name="lst-salemode" required>
                    <option></option>
                    <option value="Delivery">Delivery</option>
                    <option value="Charge">Charge</option>
                </select>
            </div>            

            <div class="col-sm-3 form-group">
              <div class="form-group">
                <label for="lst_date_range" id="lbl-lst-date-range" style="color:aqua;">= &gt; Date Range</label>
                <div class="input-group">
                  <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-calendar22"></i></span>
                  </span>
                  <input type="text" class="form-control daterange-basic" id="lst_date_range" name="lst_date_range" required> 
                </div>
              </div>
            </div>

            <div class="col-sm-2 form-group">
                <label for="lst-status" id="lbl-lst-status" style="color:aqua;">= &gt; Sale Status</label>
                <select data-placeholder="Select Status" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-status" name="lst-status" required>
                    <option></option>
                    <option value="Sold">Sold</option>
                    <option value="Void">Void</option>
                </select>
            </div>

            <div class="col-sm-2 form-group">
              <label for="lst-paystatus" id="lbl-lst-paystatus" style="color:aqua;">= &gt; Pay Status</label>
              <select data-placeholder="< Select Status >" class="form-control select" data-fouc data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="lst-paystatus" name="lst-paystatus" required>
                <option></option>
                <option value="<All>">&lt;All>&gt;</option>
                <option value="Paid">Paid</option>
                <option value="Unpaid">Unpaid</option>
              </select>
            </div> 
          </div>  

          <!-- <div class="h-divider"></div> -->

          <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header salesListTable">
          <thead>
            <tr>
              <th style="min-width: 130px;">Date</th>
              <th style="min-width: 120px;">Receipt #</th>
              <th style="min-width: 325px;">Client</th>
              <th style="min-width: 145px;text-align:right;">Amount</th>
              <th style="min-width: 145px;text-align:right;">Paid</th>
              <th style="min-width: 145px;text-align:right;">Balance</th>
              <th>Act</th>
            </tr>
          </thead>

          <!-- <tfoot>
            <tr>
                <th colspan="3" style="text-align:right;">TOTAL AMOUNT</th>
                <th><input type="text" class="form-control" id="num-totalamount" name="num-totalamount"></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
          </tfoot> -->

          <tbody>
          </tbody>
        </table>

        <!-- <div class="row">
        <div class="col-sm-2 form-group">
                <input type="text" style="font-size:1em;padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control qty numeric" name="num-netamount" id="num-netamount" value="0.00" required>
            </div> 
        </div> -->

    </div>
  </div>
</div>

<!-- <script src="views/js/sales.js"></script> -->
<script src="views/js/sales.js?v=1.2"></script>

