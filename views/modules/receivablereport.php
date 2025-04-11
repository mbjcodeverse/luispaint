<!-- Vertical form options -->
<div class="container-fluid">
  <form class="receivable-report-form" method="POST" autocomplete="nope">
    <div class="row">
      <div class="col-md-12" style="padding-left: 12px;margin-top: 13px;">
        <div class="card h-95">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;padding-bottom:10px;">
              <input type="hidden" name="generatedby" id="generatedby" value="<?php echo $_SESSION["empid"];?>">

              <h4 class="card-title flex-grow-1 transaction-name">RECEIVABLE REPORT</h4>              

              <div class="col-sm-4 form-group">
              </div>              

              <div class="col-sm-3 form-group" style="padding: 0px;padding-top:8px;padding-left:7px;margin:0;">
                <button type="button" class="btn btn-light" disabled name="btn-print-report" id="btn-print-report" style="float:right;margin-right:19px;"><i class="icon-printer"></i>&nbsp;&nbsp;Print</button>
                <button type="button" class="btn btn-light" disabled name="btn-print-report" id="btn-export" style="float:right;margin-right:19px;"><i class="icon-printer"></i>&nbsp;&nbsp;Export</button>
              </div>    
          </div>

          <div class="card-body" style="padding-bottom: 0;margin: 0;padding-top: 5px;">
              <div class="row" style="padding: 0;margin-bottom: 0;">
                <div class="col-sm-3 form-group">
                    <label for="lst-reptype">Report Type</label>
                    <select data-placeholder="< Select Type >" class="form-control select" data-container-css-class="bg-indigo-400" data-fouc id="lst-reptype" name="lst-reptype" required>
                    <option></option>
                        <option value="1">Receivable Summary</option>
                        <option value="2">Receivable Details</option>
                        <option value="3">Receivable Age Tracking</option>
                    </select>
                </div>

                <div class="col-sm-2 form-group">
                  <label>As of Date</label>
                  <input type="text" class="form-control datepicker" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-paydate" name="date-paydate" required>
                </div>

                <div class="col-sm-5 form-group">
                  <label for="lst-customercode" id="lbl-lst-customercode" style="color:aqua;">= &gt; Customer</label>
                  <select data-placeholder="< Select Customer >" class="form-control select-search" id="lst-customercode" name="lst-customercode" required>
                    <option></option>
                    <?php
                        $customers = (new ControllerClients)->ctrShowCustomerList();
                        foreach ($customers as $key => $value) {
                          echo '<option value="'.$value["customercode"].'">'.$value["name"].'</option>';
                        }
                    ?>
                  </select>              
                </div>

                <div class="col-sm-2 form-group">
                  <!-- <label for="lst-paymode" id="lbl-lst-paymode" style="color:aqua;">= &gt; Mode</label> -->
                  <label for="lst-paymode" id="lbl-lst-paymode">Mode</label>
                  <select data-placeholder="< Select Mode >" class="form-control select" data-fouc id="lst-paymode" name="lst-paymode" required disabled>
                    <option></option>
                    <option value="Cash">Cash</option>
                    <option value="Check">Check</option>
                  </select>
                </div>            

              </div>                                        
          </div>  <!-- card body -->

          <hr style="margin:0;padding: 0;padding-bottom: 24px;">

          <div class="row receivable_content" id="receivable_content" style="min-height:62vh;">
          </div> 
          
          <div class="card-footer" style="padding-top: 0;margin-top: 0;">
          </div>  <!-- footer -->
       </div>     <!-- card -->
      </div>

    </div>  <!-- row -->
  </form>
</div>

<!-- <script src="views/js/receivablereport.js"></script> -->
<script src="views/js/receivablereport.js?v=1.0.0"></script>