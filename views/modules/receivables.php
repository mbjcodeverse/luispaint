<!-- Vertical form options -->
<div class="container-fluid">
  <form class="receivable-form" method="POST" autocomplete="nope">
    <div class="row">
      <div class="col-md-10" style="padding-left: 12px;margin-top: 13px;">
        <div class="card h-95">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;">
            <h4 class="card-title flex-grow-1 transaction-name">ACCOUNTS RECEIVABLE</h4>
              <!-- Posted by -->
              <input type="hidden" name="postedby" id="postedby" value="<?php echo $_SESSION["empid"];?>">

              <!-- User ID -->
              <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION["userid"];?>">

              <!-- Transaction type -->
              <input type="hidden" name="trans_type" id="trans_type" value="New" required> 

              <!-- Pay # -->
              <input type="hidden" name="paynum" id="paynum" value="">

              <!-- Customer Code -->
              <input type="hidden" name="tns-customercode" id="tns-customercode" value="" required>    

              <!-- Customer Name Label -->
              <div class="col-sm-1 form-group" style="padding: 0px;padding-top:15px;padding-right:0;margin:0;">
                  <label style="text-align: right;font-size: 1.2em;">CUSTOMER</label>
              </div>              

              <!-- Customer -->
              <div class="col-sm-4 form-group" style="padding: 0px;padding-top:8px;padding-right:8px;margin:0;">
                  <input type="text" class="form-control" id="name" name="name" autocomplete="nope" style="border:1px solid #808080;" required readonly="true">
              </div>

              <!-- <div class="col-sm-2 form-group" style="padding: 0px;padding-top:8px;padding-right:8px;margin:0;padding-left: 10px;">
                  <button type="button" class="btn btn-outline-warning" id="btn-pending" data-toggle="modal" data-target="#modal-search-pending"><i class="icon-loop mr-2"></i>Pending Checks</button>
              </div>               -->
          </div>

          <div class="card-body" style="padding-bottom: 0;margin-bottom: 0;">
              <div class="row" style="padding: 0;margin-bottom: 0;">
                <div class="table-responsive" style="min-height:470px;max-height: clamp(65px,100vh,336px);overflow: auto;padding-top: 0px;">
                  <table class="table table-hover transaction-header-product-list" id="trans-table">
                    <thead class="sticky-top">
                      <tr>
                        <td width="10%" style="border-right: 1px solid #808080;">&nbsp;&nbsp;Date</td>
                        <td width="12%" style="border-right: 1px solid #808080;">Receipt #</td>
                        <td width="11%" style="text-align: right;border-right: 1px solid #808080;padding-right:6px;">Amount</td>
                        <td width="11%" style="text-align: right;border-right: 1px solid #808080;padding-right:6px;">Adjustment</td>
                        <td width="11%" style="text-align: right;border-right: 1px solid #808080;padding-right:6px;">Net Amnt</td>
                        <td width="11%" style="text-align: right;border-right: 1px solid #808080;padding-right:6px;">Posted</td>
                        <td width="11%" style="text-align: right;border-right: 1px solid #808080;padding-right:6px;">Pending</td>
                        <td width="12%" style="text-align: right;border-right: 1px solid #808080;padding-right:6px;">Balance</td>
                        <td width="11%" style="text-align: right;">Payment</td>
                      </tr>                    
                    </thead>
                    <tbody class="enlisted_receivable" id="receivablelist">
                    </tbody>
                  </table>
                </div>
              </div>                          
            
              <input type="hidden" name="paymentlist" id="paymentlist">
          </div>  <!-- card body -->
          
          <div class="card-footer" style="padding-top: 0;margin-top: 0;">
            <div class="row">
             <table class="table transaction-footer">
                <thead>
                  <tr>
                    <td width="11%" style="padding:0;text-align: right;font-size: 1.2em;font-weight: 400;">
                      TOTAL
                    </td>

                    <td width="11%" style="border-right: 1px solid #808080;padding:0;font-size: 1.2em;font-weight: 400;">
                      &nbsp;&nbsp;AMOUNT
                    </td>

                    <td width="11%" style="border-right: 1px solid #808080;padding:0;">
                      <input type="text" style="text-align: right;padding:6px;" class="form-control" id="total-amount" name="total-amount" value="0.00" readonly>
                    </td>

                    <td width="11%" style="border-right: 1px solid #808080;padding:0;">
                      <input type="text" style="text-align: right;padding:6px;" class="form-control" id="total-adjustment" name="total-adjustment" value="0.00" readonly>
                    </td>

                    <td width="11%" style="border-right: 1px solid #808080;padding:0;">
                      <input type="text" style="text-align: right;padding:6px;" class="form-control" id="total-netamount" name="total-netamount" value="0.00" readonly>
                    </td>

                    <td width="11%" style="border-right: 1px solid #808080;padding:0;">
                      <input type="text" style="text-align: right;padding:6px;" class="form-control" id="total-posted" name="total-posted" value="0.00" readonly>
                    </td>

                    <td width="11%" style="border-right: 1px solid #808080;padding:0;">
                      <input type="text" style="text-align: right;padding:6px;" class="form-control" id="total-pending" name="total-pending" value="0.00" readonly>
                    </td>

                    <td width="12%" style="border-right: 1px solid #808080;padding:0;">
                      <input type="text" style="text-align: right;padding:6px;" class="form-control" id="total-balance" name="total-balance" value="0.00" readonly>
                    </td>

                    <td width="11%" style="border-right: 1px solid #808080;padding:0;">
                      <input type="text" style="text-align: right;padding:6px;" class="form-control" id="total-payment" name="total-payment" value="0.00" readonly>
                    </td>
                  </tr>                    
                </thead>
             </table>
            </div>
        
            <!-- ================== Function Buttons ================= -->
            <div class="btn-group btn-group-justified" style="margin-top: 10px;">
              <div class="btn-group">
                <button type="button" class="btn btn-light" id="btn-search-po" name="btn-search-po" data-toggle="modal" data-target="#modal-search-receivable"><i class="icon-file-empty"></i>&nbsp;&nbsp;Get Receivable</button>
              </div>

              <div class="btn-group">
                <button type="submit" class="btn btn-light" name="btn-post" id="btn-post" disabled><i class="icon-floppy-disk"></i>&nbsp;&nbsp;Post</button>
              </div>            

              <div class="btn-group">
                <button type="button" class="btn btn-light" name="btn-search" id="btn-search" data-toggle="modal" data-target="#modal-search-payment"><i class="icon-file-text2"></i>&nbsp;&nbsp;Search</button>
              </div>

              <div class="btn-group">
                <button type="button" class="btn btn-light" disabled name="btn-print" id="btn-print"><i class="icon-printer"></i>&nbsp;&nbsp;Print</button>
              </div>

              <div class="btn-group">
                <button type="button" class="btn btn-light" disabled name="btn-cancel" id="btn-cancel"><i class="icon-blocked"></i>&nbsp;&nbsp;Cancel</button>
              </div>
              
              <!-- <div class="btn-group">
                <button type="button" class="btn btn-light" name="btn-reset" id="btn-reset" data-toggle="modal" data-target="#modal-reset-receivable"><i class="icon-spinner9"></i>&nbsp;&nbsp;Reset</button>
              </div>   -->
              
              <!-- <button type="button" class="btn btn-lg btn-outline bg-teal-400 text-teal-400 border-teal-400 border-2" name="btn-reset" id="btn-reset" data-toggle="modal" data-target="#modal-reset-sale" style="margin-bottom: 9px;margin-top:2px;padding-left:20px;font-size:1.5em;text-align: left;"><i class="icon-spinner9 icon-2x"></i> <span>&nbsp;&nbsp;F9 - Reset</span></button> -->
            </div>
            <!-- ================== Function Buttons ================= -->
          </div>  <!-- footer -->
       </div>     <!-- card -->
      </div>    

      <!-- Payment Details -->
      <div class="col-md-2" style="padding-left: 0px;margin-top: 13px;">
        <div class="card h-95">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 25px;padding-right: 1px;padding-bottom: 17px;">
            <h4 class="card-title flex-grow-1">PAYMENT DETAILS</h4>
          </div>
          <div class="card-body">
            <div class="row">                  
                <div class="col-sm-12 form-group" style="margin:0;margin-bottom:15px;">
                  <label for="date-paydate">Date Paid</label>
                  <input type="text" class="form-control datepicker" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-paydate" name="date-paydate">
                </div>    
            </div>  

            <!-- <div class="col-sm-4 form-group">
              <label for="sel-estatus">Status</label>
              <select data-placeholder="Select Status" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-estatus" name="sel-estatus" required>
                <option></option>
                <option value="Regular">Regular</option>
                <option value="Probationary">Probationary</option>
                <option value="Contractual">Contractual</option>
              </select>
            </div> -->

            <div class="row">                  
              <div class="col-sm-12 form-group" style="margin:0;margin-bottom:15px;">
                <label for="sel-paymode">Mode of Payment</label>
                <select data-placeholder="&nbsp;" class="form-control select" data-fouc id="sel-paymode" name="sel-paymode" required>
                  <option></option>
                  <option value="Cash">Cash</option>
                  <option value="Check">Check</option>
                </select>
              </div>    
            </div> 

            <div class="row">                  
              <div class="col-sm-12 form-group" style="margin:0;margin-bottom:15px;">
                <label for="sel-checkdesc">Check Description</label>
                <select data-placeholder="&nbsp;" class="form-control select" data-fouc id="sel-checkdesc" name="sel-checkdesc" required>
                  <option></option>
                  <option value="On-date">On-date</option>
                  <option value="Post-dated">Post-dated</option>
                </select>
              </div>    
            </div>  

            <div class="row">
              <div class="col-sm-12 form-group" style="margin:0;margin-bottom:15px;">
                <label for="sel-bankcode">Bank</label>
                <select data-placeholder="&nbsp;" class="form-control select-search" id="sel-bankcode" name="sel-bankcode" required>
                  <option></option>
                  <?php
                    $banks = (new ControllerBank)->ctrShowBankList();
                    foreach ($banks as $key => $value) {
                      echo '<option value="'.$value["bankcode"].'">'.$value["bankname"].'</option>';
                    }
                  ?>
                </select>              
              </div>              
            </div>          

            <div class="row">                  
              <div class="col-sm-12 form-group" style="margin:0;margin-bottom:15px;">
                <label for="date-checkdate">Check Date</label>
                <input type="text" class="form-control datepicker" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-checkdate" name="date-checkdate">
              </div>    
            </div>

            <div class="row">
              <div class="col-sm-12 form-group" style="margin:0;margin-bottom:17px;">
                  <label for="tns-checknum">Check #</label>
                  <input type="text" class="form-control" id="tns-checknum" name="tns-checknum" autocomplete="nope">
              </div>              
            </div>

            <div class="row">                  
                <div class="col-sm-12 form-group" style="margin:0;margin-bottom:13px;">
                  <label for="date-posted">Date Posted</label>
                  <input type="text" class="form-control" id="date-posted" name="date-posted" readonly>
                </div>    
            </div>                                          
          </div>  

        </div>   <!-- card -->
      </div>

    </div>  <!-- row -->
  </form>
</div>

<!-- ============== Customer List ============ -->
<div id="modal-search-receivable" class="modal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;ACCOUNTS RECEIVABLE LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
  
      <div class="h-divider"></div>
      <div modal-body>
      <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header receivableListTable" style="font-size: 1.4em;">  
        <thead>
          <tr>
            <th style="min-width: 430px;">Customer</th>
            <th>Act</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    </div>
  </div>
</div>

<!-- ============== Active Receivable List ============ -->
<div id="modal-search-pending" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;PENDING CHECKS</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
  
      <div class="h-divider"></div>
      <div modal-body>
      <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header pendingListTable">  
        <thead>
          <tr>
            <th style="min-width: 100px;">Date Paid</th>
            <th style="min-width: 130px;">AR Code</th>
            <th style="min-width: 200px;">Bank</th>
            <th style="min-width: 90px;">Check Date</th>
            <th style="min-width: 100px;">Check #</th>
            <th style="min-width: 100px;">Amount</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    </div>
  </div>
</div>

<!-- ============== Modal Form - RESET Receivable ============== -->
<div id="modal-reset-receivable" class="modal allow-modal-drag" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;RECEIVABLE RESET</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider"></div>

      <div class="row reset_content"></div> 

      <div class="row" pb-0 style="margin:10px;margin-bottom: 0px;">
        <div class="col-sm-12 form-group" style="text-align:center;">
          <label style="font-size: 1.2em;margin-top: 6px;">Enter Override Key</label>
          <input type="password" class="form-control border-teal border-1" style="text-align:center;" id="reset-override" name="reset-override" value="">
        </div>
      </div> 

      <div class="h-divider"></div>
      
      <div class="btn-group btn-group-justified" style="margin-left:21px;margin-right:21px;margin-bottom: 22px;margin-top: 20px;">
        <div class="btn-group" style="padding-right:4px;">
          <button type="button" class="btn btn-light btn-lg" id="btn-admin-direct-reset" style="font-size: 1.3em;color:lightgreen;"><i class="icon-database-edit2 icon-2x"></i>&nbsp;&nbsp;Initiate Receivable Reset</button>
        </div>
        <div class="btn-group" style="padding-left:4px;">
          <button type="button" class="btn btn-light btn-lg" id="btn-reset-request" style="font-size: 1.3em;color:orange;"><i class="icon-paperplane icon-2x"></i>&nbsp;&nbsp;Send Reset Request</button>
        </div>
      </div> 

    </div>
  </div>
</div>

<script src="views/js/receivable.js?v=1.2"></script>


