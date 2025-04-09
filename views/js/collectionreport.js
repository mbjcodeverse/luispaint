$(function() {
       // select styling
   $(".select").select2({
     minimumResultsForSearch: Infinity,
   });

   // select with search
    $(".select-search").select2(); 

    $('#lst_date_range').daterangepicker({
      ranges:{
        'Today'         : [moment(),moment()],
        'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
        'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
        'This Month'    : [moment().startOf('month'), moment().endOf('month')]
      }
    });
    
    // $("#lst-reptype").change(function(){
    //   if (($("#lst-reptype").val() == 3)||($("#lst-reptype").val() == 4)){
    //     $("#lst-customercode").val('').trigger('change');    
    //     $("#lst-customercode").prop('disabled', true);
    //     $("#lst-paymode").val('').trigger('change');    
    //     $("#lst-paymode").prop('disabled', true);
    //   }else{
    //     if ($('#lst-branchcode').val() != ''){
    //       loadBranchCustomers(); 
    //       $("#lst-customercode").prop('disabled', false);
    //     }
    //     $("#lst-paymode").prop('disabled', false);
    //   }  
    // });
  
    // Clearing Customer selection
    $("#lbl-lst-customercode").click(function(){
        $("#lst-customercode").val('').trigger('change');    // trigger change - all customer
    });

    $('#lst_date_range, #lst-customercode, #lst-paymode, #lst-reptype').on("change", function(){
        $("#btn-print-report").prop('disabled', false);
        $("#btn-export").prop('disabled', false);

        let date_range = $("#lst_date_range").val();
        let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
        let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);

        let customercode = $("#lst-customercode").val();
        let paymode = $("#lst-paymode").val();
        let reptype = $("#lst-reptype").val();
  
        var data = new FormData();
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        data.append("customercode", customercode);
        data.append("paymode", paymode);
        data.append("reptype", reptype);
  
        $.ajax({
             url:"ajax/collection_report.ajax.php",   
             method: "POST",                
             data: data,                    
             cache: false,                  
             contentType: false,            
             processData: false,            
             dataType:"json",               
             success:function(answer){
               $(".collection_content").empty();
               var html = [];
               if (reptype == 1){ 
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                      html.push('<table class="table mx-auto w-auto">');
                        html.push('<thead>');
                          html.push('<tr>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;min-width:330px;">Customer</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                          html.push('</tr>');
                        html.push('</thead>');
                        var total_amount = 0.00;
                        for(var i = 0; i < answer.length; i++) {
                            var receivable = answer[i];
                            var name = receivable.name;
                            var amount = numberWithCommas(receivable.amount);

                            var total_amount = total_amount + Number(receivable.amount);

                            html.push('<tr>');
                               html.push('<td>'+name+'</td>');
                               html.push('<td style="text-align:right;">'+amount+'</td>');
                            html.push('</tr>');
                        }

                        if (i > 0){
                          html.push('<tr>');
                            html.push('<td style="text-align:right;border-top:2px solid white;font-size:1.3em;font-weight:bold;border-right:2px solid white;border-bottom:2px solid white">TOTAL AMOUNT</td>');
                            html.push('<td style="text-align:right;border-top:2px solid white;font-size:1.3em;font-weight:bold;border-bottom:2px solid white">'+numberWithCommas(total_amount)+'</td>');
                          html.push('</tr>');    
                        }                    

                      html.push('</table>');
                    html.push('</div>');
               }else if (reptype == 2){    
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                      html.push('<table class="table mx-auto w-auto">');
                        html.push('<thead>');
                          html.push('<tr>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;min-width:330px;">Customer</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Date Paid</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Mode</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Chk Desc</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Chk Num</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Chk Date</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Bank</th>');
                          html.push('</tr>');
                        html.push('</thead>');

                        for(var i = 0; i < answer.length; i++){
                            var receivable = answer[i];
                            var name = receivable.name;

                            if (i == 0){
                                var prev_name = name;
                            }else{
                                var curr_name = name;
                                if (prev_name == curr_name){
                                  name = '';
                                }
                                var prev_name = curr_name;                   
                            } 

                            var pay_date = receivable.paydate;
                            if (pay_date != null){
                                var paydate = pay_date.substring(5, 7) + '/' + pay_date.substring(8, 10) + '/' + pay_date.substring(0, 4);
                            }else{
                                var paydate = ''; 
                            }

                            var paymode = receivable.paymode;
                            var amount = numberWithCommas(receivable.amount);
                            var checkdesc = receivable.checkdesc;
                            var checknum = receivable.checknum;
                            var bank = receivable.bank;

                            var check_date = receivable.checkdate;
                            if (check_date != '0000-00-00'){
                                var checkdate = check_date.substring(5, 7) + '/' + check_date.substring(8, 10) + '/' + check_date.substring(0, 4);
                            }else{
                                var checkdate = ''; 
                            }
                            
                            if (pay_date != null){ 
                                html.push('<tr>');
                                    html.push('<td>'+name+'</td>');
                                    html.push('<td>'+paydate+'</td>');
                                    html.push('<td>'+paymode+'</td>');
                                    html.push('<td style="text-align:right;">'+amount+'</td>');
                                    html.push('<td>'+checkdesc+'</td>');
                                    html.push('<td>'+checknum+'</td>');
                                    html.push('<td>'+checkdate+'</td>');
                                    html.push('<td>'+bank+'</td>');
                                html.push('</tr>');
                            }else{
                                html.push('<tr>');
                                    html.push('<td></td>');
                                    html.push('<td></td>');
                                    html.push('<td></td>');
                                    html.push('<td style="text-align:right;border-top:2px solid white;font-size:1.1em;font-weight:bold;color:#60fc75">'+amount+'</td>');
                                    html.push('<td></td>');
                                    html.push('<td></td>');
                                    html.push('<td></td>');
                                    html.push('<td></td>');
                                html.push('</tr>');                                
                            }    
                        }              

                      html.push('</table>');
                    html.push('</div>'); 
               }else if (reptype == 3){ 
                  html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                    html.push('<table class="table mx-auto w-auto">');
                      html.push('<thead>');
                        html.push('<tr>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;min-width:330px;">Customer</th>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Date Paid</th>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Pay #</th>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Mode</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Chk Desc</th>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Chk Num</th>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Chk Date</th>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Bank</th>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Invoice #</th>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Posted</th>');
                        html.push('</tr>');
                      html.push('</thead>');

                      var overall_total = 0.00;
                      var total_credit = 0.00;
                      for(var i = 0; i < answer.length; i++){
                          var receivable = answer[i];
                          var name = receivable.name;
                          var paymode = receivable.paymode;
                          var paynum = receivable.paynum;
                          var pay_date = receivable.paydate;
                          var paydate = pay_date.substring(5, 7) + '/' + pay_date.substring(8, 10) + '/' + pay_date.substring(0, 4);
                          var amount = numberWithCommas(receivable.amount);
                          var checkdesc = receivable.checkdesc;
                          var checknum = receivable.checknum;
                          var bank = receivable.bank;
                          var invno = receivable.invno;
                          var posted_amount = numberWithCommas(receivable.posted_amount);
                          var check_date = receivable.checkdate;

                          if (check_date != '0000-00-00'){
                            var checkdate = check_date.substring(5, 7) + '/' + check_date.substring(8, 10) + '/' + check_date.substring(0, 4);
                          }else{
                            var checkdate = ''; 
                          }

                          if (i == 0){
                              var prev_name = name;
                              html.push('<tr>');
                                html.push('<td>'+name+'</td>');
                                html.push('<td>'+paydate+'</td>');
                                html.push('<td>'+paynum+'</td>');
                                html.push('<td>'+paymode+'</td>');
                                html.push('<td style="text-align:right;">'+amount+'</td>');
                                html.push('<td>'+checkdesc+'</td>');
                                html.push('<td>'+checknum+'</td>');
                                html.push('<td>'+checkdate+'</td>');
                                html.push('<td>'+bank+'</td>');
                                html.push('<td style="border-left:1px solid white;">'+invno+'</td>');
                                html.push('<td style="text-align:right;">'+posted_amount+'</td>');
                              html.push('</tr>');
                              total_credit = total_credit + Number(receivable.posted_amount);
                              overall_total = overall_total + Number(receivable.posted_amount);
                          }else{
                              var curr_name = name;
                              if (prev_name == curr_name){
                                name = '';
                                total_credit = total_credit + Number(receivable.posted_amount);
                              }else{
                                html.push('<tr>');
                                  html.push('<td colspan="9" style="border-bottom:1px solid white;"></td>');
                                  html.push('<td style="border-bottom:1px solid white;border-right:1px solid white;border-top:2px solid white;color:yellow;">SUB-TOTAL</td>');
                                  html.push('<td style="border-bottom:1px solid white;text-align:right;border-top:2px solid white;font-size:1.1em;color:#60fc75">'+numberWithCommas(total_credit)+'</td>');
                                html.push('</tr>'); 
                                total_credit = Number(receivable.posted_amount) 
                              }
                              overall_total = overall_total + Number(receivable.posted_amount);
                              var prev_name = curr_name;                   
                          } 

                          if (i == 0){
                            var prev_paynum = paynum;
                          }else{
                            var curr_paynum = paynum;
                            if (prev_paynum == curr_paynum){
                              html.push('<tr>');
                                html.push('<td colspan="9"></td>');
                                html.push('<td style="border-left:1px solid white;">'+invno+'</td>');
                                html.push('<td style="text-align:right;">'+posted_amount+'</td>');
                              html.push('</tr>');
                            }else{
                              html.push('<tr>');
                                html.push('<td>'+name+'</td>');
                                html.push('<td>'+paydate+'</td>');
                                html.push('<td>'+paynum+'</td>');
                                html.push('<td>'+paymode+'</td>');
                                html.push('<td style="text-align:right;">'+amount+'</td>');
                                html.push('<td>'+checkdesc+'</td>');
                                html.push('<td>'+checknum+'</td>');
                                html.push('<td>'+checkdate+'</td>');
                                html.push('<td>'+bank+'</td>');
                                html.push('<td style="border-left:1px solid white;">'+invno+'</td>');
                                html.push('<td style="text-align:right;">'+posted_amount+'</td>');
                              html.push('</tr>');
                             
                            }
                            var prev_paynum = curr_paynum;
                          } 
                      }         
                      
                      if (i > 0){
                        html.push('<tr>');
                          html.push('<td colspan="9" style="border-bottom:1px solid white;"></td>');
                          html.push('<td style="border-bottom:1px solid white;border-right:1px solid white;border-top:2px solid white;color:yellow;">SUB-TOTAL</td>');
                          html.push('<td style="border-bottom:1px solid white;text-align:right;border-top:2px solid white;font-size:1.1em;color:#60fc75">'+numberWithCommas(total_credit)+'</td>');
                        html.push('</tr>');

                        html.push('<tr>');
                          html.push('<td colspan="10" style="text-align:right;border-bottom:1px solid white;border-right:1px solid white;font-size:1.2em;color:yellow;">OVERALL TOTAL</td>');
                          html.push('<td style="border-bottom:1px solid white;text-align:right;border-top:1px solid white;font-size:1.2em;color:#60fc75">'+numberWithCommas(overall_total)+'</td>');
                        html.push('</tr>');
                      }

                    html.push('</table>');
                  html.push('</div>');                                      
               }else if (reptype == 4){
                 html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                    html.push('<table class="table mx-auto w-auto">');
                      html.push('<thead>');
                        html.push('<tr>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;min-width:120px;">Date</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">Counter</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">Cash</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">OD Check</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">PDC Check</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">AR Total</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:160px;">TOTAL AMOUNT</th>');
                        html.push('</tr>');
                      html.push('</thead>');

                      var counter_amount = 0.00;
                      var cash_amount = 0.00;
                      var check_ondate_amount = 0.00;
                      var check_post_dated_amount = 0.00;
                      var total_amount = 0.00;

                      var total_counter = 0.00;
                      var total_cash = 0.00;
                      var total_check_ondate = 0.00;
                      var total_check_postdated = 0.00;

                      for(var i = 0; i < answer.length; i++) {
                        var collection = answer[i];  
                        var col_date = collection.cdate;
                        var cdate = col_date.substring(5, 7) + '/' + col_date.substring(8, 10) + '/' + col_date.substring(0, 4);
                        var salemode = collection.salemode;
                        var paymode = collection.paymode;
                        var checkdesc = collection.checkdesc;
                        var amount = Number(collection.amount);

                        if (i == 0){
                          var prev_date = cdate;          
                          if ((salemode == 'Counter')&&(paymode == '')&&(checkdesc == '')){
                            counter_amount = amount;      
                            total_counter = total_counter + amount;
                          }else if ((salemode == 'Credit')&&(paymode == 'Cash')&&(checkdesc == '')){
                            cash_amount = amount;
                            total_cash = total_cash + amount;
                          }else if ((salemode == 'Credit')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                            check_ondate_amount = amount;
                            total_check_ondate = total_check_ondate + amount;
                          }else{
                            check_post_dated_amount = amount;
                            total_check_postdated = total_check_postdated + amount;
                          }
                          total_amount = total_amount + amount
                        }else{
                          var curr_date = cdate;         // i = 1;2023-11-28, i = 2;2023-11-29
                          if (prev_date == curr_date){   // i = 1
                            if ((salemode == 'Counter')&&(paymode == '')&&(checkdesc == '')){
                              counter_amount = amount;      
                              total_counter = total_counter + amount;
                            }else if ((salemode == 'Credit')&&(paymode == 'Cash')&&(checkdesc == '')){
                              cash_amount = amount;
                              total_cash = total_cash + amount;
                            }else if ((salemode == 'Credit')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                              check_ondate_amount = amount;
                              total_check_ondate = total_check_ondate + amount;
                            }else{
                              check_post_dated_amount = amount;
                              total_check_postdated = total_check_postdated + amount;
                            }
                            total_amount = total_amount + amount;
                          }else{                         // i = 2
                            html.push('<tr>');
                              html.push('<td style="border-right:1px solid white;">'+prev_date+'</td>');
                              html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(counter_amount)+'</td>');
                              html.push('<td style="text-align:right;">'+numberWithCommas(cash_amount)+'</td>');
                              html.push('<td style="text-align:right;">'+numberWithCommas(check_ondate_amount)+'</td>');
                              html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(check_post_dated_amount)+'</td>');
                              html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(cash_amount + check_ondate_amount + check_post_dated_amount)+'</td>');
                              html.push('<td style="text-align:right;color:chartreuse;">'+numberWithCommas(total_amount)+'</td>');
                            html.push('</tr>'); 

                            counter_amount = 0.00;
                            cash_amount = 0.00;
                            check_ondate_amount = 0.00;
                            check_post_dated_amount = 0.00;
                            total_amount = 0.00;

                            if ((salemode == 'Counter')&&(paymode == '')&&(checkdesc == '')){
                              counter_amount = amount;      
                              total_counter = total_counter + amount;
                            }else if ((salemode == 'Credit')&&(paymode == 'Cash')&&(checkdesc == '')){
                              cash_amount = amount;
                              total_cash = total_cash + amount;
                            }else if ((salemode == 'Credit')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                              check_ondate_amount = amount;
                              total_check_ondate = total_check_ondate + amount;
                            }else{
                              check_post_dated_amount = amount;
                              total_check_postdated = total_check_postdated + amount;
                            }

                            total_amount = total_amount + amount;
                            var prev_date = curr_date;
                          }              
                        }
                      }
                      if (i > 0){
                        html.push('<tr>');
                          html.push('<td style="border-right:1px solid white;">'+cdate+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(counter_amount)+'</td>');
                          html.push('<td style="text-align:right;">'+numberWithCommas(cash_amount)+'</td>');
                          html.push('<td style="text-align:right;">'+numberWithCommas(check_ondate_amount)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(check_post_dated_amount)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(cash_amount + check_ondate_amount + check_post_dated_amount)+'</td>');
                          html.push('<td style="text-align:right;color:chartreuse;">'+numberWithCommas(total_amount)+'</td>');
                        html.push('</tr>');  
                        
                        html.push('<tr>');
                          html.push('<td style="border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">TOTAL AMOUNT</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_counter)+'</td>');
                          html.push('<td style="text-align:right;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_cash)+'</td>');
                          html.push('<td style="text-align:right;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_check_ondate)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_check_postdated)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_cash + total_check_ondate + total_check_postdated)+'</td>');
                          html.push('<td style="text-align:right;color:chartreuse;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_counter + total_cash + total_check_ondate + total_check_postdated)+'</td>');
                        html.push('</tr>');
                      }
 
                    html.push('</table>');
                 html.push('</div>');
               }else if (reptype == 5){
                 html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                  html.push('<table class="table mx-auto w-auto">');
                    html.push('<thead>');
                      html.push('<tr>');
                        html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;min-width:120px;">Date</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">Counter</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">Cash</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">OD Check</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">PDC Check</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">AR Total</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:160px;">TOTAL AMOUNT</th>');
                      html.push('</tr>');
                    html.push('</thead>');

                    var counter_amount = 0.00;
                    var cash_amount = 0.00;
                    var check_ondate_amount = 0.00;
                    var check_post_dated_amount = 0.00;
                    var total_amount = 0.00;

                    var total_counter = 0.00;
                    var total_cash = 0.00;
                    var total_check_ondate = 0.00;
                    var total_check_postdated = 0.00;

                    for(var i = 0; i < answer.length; i++) {
                      var collection = answer[i];  
                      var cdate = collection.cdate;
                      var salemode = collection.salemode;
                      var paymode = collection.paymode;
                      var checkdesc = collection.checkdesc;
                      var amount = Number(collection.amount);

                      if (i == 0){
                        var prev_date = cdate;          
                        if ((salemode == 'Counter')&&(paymode == '')&&(checkdesc == '')){
                          counter_amount = amount;      
                          total_counter = total_counter + amount;
                        }else if ((salemode == 'Credit')&&(paymode == 'Cash')&&(checkdesc == '')){
                          cash_amount = amount;
                          total_cash = total_cash + amount;
                        }else if ((salemode == 'Credit')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                          check_ondate_amount = amount;
                          total_check_ondate = total_check_ondate + amount;
                        }else{
                          check_post_dated_amount = amount;
                          total_check_postdated = total_check_postdated + amount;
                        }
                        total_amount = total_amount + amount
                      }else{
                        var curr_date = cdate;         // i = 1;2023-11-28, i = 2;2023-11-29
                        if (prev_date == curr_date){   // i = 1
                          if ((salemode == 'Counter')&&(paymode == '')&&(checkdesc == '')){
                            counter_amount = amount;      
                            total_counter = total_counter + amount;
                          }else if ((salemode == 'Credit')&&(paymode == 'Cash')&&(checkdesc == '')){
                            cash_amount = amount;
                            total_cash = total_cash + amount;
                          }else if ((salemode == 'Credit')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                            check_ondate_amount = amount;
                            total_check_ondate = total_check_ondate + amount;
                          }else{
                            check_post_dated_amount = amount;
                            total_check_postdated = total_check_postdated + amount;
                          }
                          total_amount = total_amount + amount;
                        }else{                         // i = 2
                          html.push('<tr>');
                            html.push('<td style="border-right:1px solid white;">'+prev_date+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(counter_amount)+'</td>');
                            html.push('<td style="text-align:right;">'+numberWithCommas(cash_amount)+'</td>');
                            html.push('<td style="text-align:right;">'+numberWithCommas(check_ondate_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(check_post_dated_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(cash_amount + check_ondate_amount + check_post_dated_amount)+'</td>');
                            html.push('<td style="text-align:right;color:chartreuse;">'+numberWithCommas(total_amount)+'</td>');
                          html.push('</tr>'); 

                          counter_amount = 0.00;
                          cash_amount = 0.00;
                          check_ondate_amount = 0.00;
                          check_post_dated_amount = 0.00;
                          total_amount = 0.00;

                          if ((salemode == 'Counter')&&(paymode == '')&&(checkdesc == '')){
                            counter_amount = amount;      
                            total_counter = total_counter + amount;
                          }else if ((salemode == 'Credit')&&(paymode == 'Cash')&&(checkdesc == '')){
                            cash_amount = amount;
                            total_cash = total_cash + amount;
                          }else if ((salemode == 'Credit')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                            check_ondate_amount = amount;
                            total_check_ondate = total_check_ondate + amount;
                          }else{
                            check_post_dated_amount = amount;
                            total_check_postdated = total_check_postdated + amount;
                          }

                          total_amount = total_amount + amount;
                          var prev_date = curr_date;
                        }              
                      }
                    }
                    if (i > 0){
                      html.push('<tr>');
                        html.push('<td style="border-right:1px solid white;">'+cdate+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(counter_amount)+'</td>');
                        html.push('<td style="text-align:right;">'+numberWithCommas(cash_amount)+'</td>');
                        html.push('<td style="text-align:right;">'+numberWithCommas(check_ondate_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(check_post_dated_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(cash_amount + check_ondate_amount + check_post_dated_amount)+'</td>');
                        html.push('<td style="text-align:right;color:chartreuse;">'+numberWithCommas(total_amount)+'</td>');
                      html.push('</tr>');  
                      
                      html.push('<tr>');
                        html.push('<td style="border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">TOTAL AMOUNT</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_counter)+'</td>');
                        html.push('<td style="text-align:right;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_cash)+'</td>');
                        html.push('<td style="text-align:right;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_check_ondate)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_check_postdated)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_cash + total_check_ondate + total_check_postdated)+'</td>');
                        html.push('<td style="text-align:right;color:chartreuse;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_counter + total_cash + total_check_ondate + total_check_postdated)+'</td>');
                      html.push('</tr>');
                    }

                  html.push('</table>');
                html.push('</div>');                
               }else if (reptype == 6){
                html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                  html.push('<table class="table mx-auto w-auto">');
                    html.push('<thead>');                      
                      html.push('<tr>');
                        html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;min-width:120px;">Date</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">Counter</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">Credit</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">Cash</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">OD Check</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">PDC Check</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">AR Total</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:160px;">TOTAL AMOUNT</th>');
                      html.push('</tr>');
                    html.push('</thead>');

                    var counter_amount = 0.00;
                    var credit_amount = 0.00;
                    var cash_amount = 0.00;
                    var check_ondate_amount = 0.00;
                    var check_post_dated_amount = 0.00;
                    var total_amount = 0.00;

                    var total_counter = 0.00;
                    var total_credit = 0.00;
                    var total_cash = 0.00;
                    var total_check_ondate = 0.00;
                    var total_check_postdated = 0.00;

                    for(var i = 0; i < answer.length; i++) {
                      var collection = answer[i];  
                      var col_date = collection.cdate;
                      var cdate = col_date.substring(5, 7) + '/' + col_date.substring(8, 10) + '/' + col_date.substring(0, 4);
                      var salemode = collection.salemode;
                      var paymode = collection.paymode;
                      var checkdesc = collection.checkdesc;
                      var amount = Number(collection.amount);

                      if (i == 0){
                        var prev_date = cdate;          
                        if ((salemode == 'Counter')&&(paymode == '')&&(checkdesc == '')){
                          counter_amount = amount;      
                          total_counter = total_counter + amount;
                        }else if ((salemode == 'Credit')&&(paymode == '')&&(checkdesc == '')){
                          credit_amount = amount;      
                          total_credit = total_credit + amount;  
                        }else if ((salemode == 'Collection')&&(paymode == 'Cash')&&(checkdesc == '')){
                          cash_amount = amount;
                          total_cash = total_cash + amount;
                        }else if ((salemode == 'Collection')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                          check_ondate_amount = amount;
                          total_check_ondate = total_check_ondate + amount;
                        }else{
                          check_post_dated_amount = amount;
                          total_check_postdated = total_check_postdated + amount;
                        }
                        total_amount = total_amount + amount
                      }else{
                        var curr_date = cdate;         // i = 1;2023-11-28, i = 2;2023-11-29
                        if (prev_date == curr_date){   // i = 1
                          if ((salemode == 'Counter')&&(paymode == '')&&(checkdesc == '')){
                            counter_amount = amount;      
                            total_counter = total_counter + amount;
                          }else if ((salemode == 'Credit')&&(paymode == '')&&(checkdesc == '')){
                            credit_amount = amount;      
                            total_credit = total_credit + amount;   
                          }else if ((salemode == 'Collection')&&(paymode == 'Cash')&&(checkdesc == '')){
                            cash_amount = amount;
                            total_cash = total_cash + amount;
                          }else if ((salemode == 'Collection')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                            check_ondate_amount = amount;
                            total_check_ondate = total_check_ondate + amount;
                          }else{
                            check_post_dated_amount = amount;
                            total_check_postdated = total_check_postdated + amount;
                          }
                          total_amount = total_amount + amount;
                        }else{                         // i = 2
                          html.push('<tr>');
                            html.push('<td style="border-right:1px solid white;">'+prev_date+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(counter_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:#fc8677;">'+numberWithCommas(credit_amount)+'</td>');
                            html.push('<td style="text-align:right;">'+numberWithCommas(cash_amount)+'</td>');
                            html.push('<td style="text-align:right;">'+numberWithCommas(check_ondate_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(check_post_dated_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(cash_amount + check_ondate_amount + check_post_dated_amount)+'</td>');
                            html.push('<td style="text-align:right;color:chartreuse;">'+numberWithCommas(total_amount)+'</td>');
                          html.push('</tr>'); 

                          counter_amount = 0.00;
                          credit_amount = 0.00;
                          cash_amount = 0.00;
                          check_ondate_amount = 0.00;
                          check_post_dated_amount = 0.00;
                          total_amount = 0.00;

                          if ((salemode == 'Counter')&&(paymode == '')&&(checkdesc == '')){
                            counter_amount = amount;      
                            total_counter = total_counter + amount;
                          }else if ((salemode == 'Credit')&&(paymode == '')&&(checkdesc == '')){
                            credit_amount = amount;      
                            total_credit = total_credit + amount;   
                          }else if ((salemode == 'Collection')&&(paymode == 'Cash')&&(checkdesc == '')){
                            cash_amount = amount;
                            total_cash = total_cash + amount;
                          }else if ((salemode == 'Collection')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                            check_ondate_amount = amount;
                            total_check_ondate = total_check_ondate + amount;
                          }else{
                            check_post_dated_amount = amount;
                            total_check_postdated = total_check_postdated + amount;
                          }

                          total_amount = total_amount + amount;
                          var prev_date = curr_date;
                        }              
                      }
                    }
                    if (i > 0){
                      html.push('<tr>');
                        html.push('<td style="border-right:1px solid white;">'+cdate+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(counter_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:#fc8677;">'+numberWithCommas(credit_amount)+'</td>');
                        html.push('<td style="text-align:right;">'+numberWithCommas(cash_amount)+'</td>');
                        html.push('<td style="text-align:right;">'+numberWithCommas(check_ondate_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(check_post_dated_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(cash_amount + check_ondate_amount + check_post_dated_amount)+'</td>');
                        html.push('<td style="text-align:right;color:chartreuse;">'+numberWithCommas(total_amount)+'</td>');
                      html.push('</tr>');  
                      
                      html.push('<tr>');
                        html.push('<td style="border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">TOTAL AMOUNT</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_counter)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:#fc8677;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_credit)+'</td>');
                        html.push('<td style="text-align:right;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_cash)+'</td>');
                        html.push('<td style="text-align:right;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_check_ondate)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_check_postdated)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_cash + total_check_ondate + total_check_postdated)+'</td>');
                        html.push('<td style="text-align:right;color:chartreuse;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_counter + total_credit + total_cash + total_check_ondate + total_check_postdated)+'</td>');
                      html.push('</tr>');
                    }

                  html.push('</table>');
                html.push('</div>');                
               }else if (reptype == 7){
                html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                  html.push('<table class="table mx-auto w-auto">');
                    html.push('<thead>');                      
                      html.push('<tr>');
                        html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;min-width:120px;">Branch</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">Counter</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">Credit</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">Cash</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">OD Check</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">PDC Check</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;">AR Total</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:160px;">TOTAL AMOUNT</th>');
                      html.push('</tr>');
                    html.push('</thead>');

                    var counter_amount = 0.00;
                    var credit_amount = 0.00;
                    var cash_amount = 0.00;
                    var check_ondate_amount = 0.00;
                    var check_post_dated_amount = 0.00;
                    var total_amount = 0.00;

                    var total_counter = 0.00;
                    var total_credit = 0.00;
                    var total_cash = 0.00;
                    var total_check_ondate = 0.00;
                    var total_check_postdated = 0.00;

                    for(var i = 0; i < answer.length; i++) {
                      var collection = answer[i];  
                      var branch_name = collection.branch_name;
                      var salemode = collection.salemode;
                      var paymode = collection.paymode;
                      var checkdesc = collection.checkdesc;
                      var amount = Number(collection.amount);

                      if (i == 0){
                        var prev_branch = branch_name;          
                        if ((salemode == 'Counter')&&(paymode == '')&&(checkdesc == '')){
                          counter_amount = amount;      
                          total_counter = total_counter + amount;
                        }else if ((salemode == 'Credit')&&(paymode == '')&&(checkdesc == '')){
                          credit_amount = amount;      
                          total_credit = total_credit + amount;  
                        }else if ((salemode == 'Collection')&&(paymode == 'Cash')&&(checkdesc == '')){
                          cash_amount = amount;
                          total_cash = total_cash + amount;
                        }else if ((salemode == 'Collection')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                          check_ondate_amount = amount;
                          total_check_ondate = total_check_ondate + amount;
                        }else{
                          check_post_dated_amount = amount;
                          total_check_postdated = total_check_postdated + amount;
                        }
                        total_amount = total_amount + amount
                      }else{
                        var curr_branch = branch_name;         
                        if (prev_branch == curr_branch){   
                          if ((salemode == 'Counter')&&(paymode == '')&&(checkdesc == '')){
                            counter_amount = amount;      
                            total_counter = total_counter + amount;
                          }else if ((salemode == 'Credit')&&(paymode == '')&&(checkdesc == '')){
                            credit_amount = amount;      
                            total_credit = total_credit + amount;   
                          }else if ((salemode == 'Collection')&&(paymode == 'Cash')&&(checkdesc == '')){
                            cash_amount = amount;
                            total_cash = total_cash + amount;
                          }else if ((salemode == 'Collection')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                            check_ondate_amount = amount;
                            total_check_ondate = total_check_ondate + amount;
                          }else{
                            check_post_dated_amount = amount;
                            total_check_postdated = total_check_postdated + amount;
                          }
                          total_amount = total_amount + amount;
                        }else{                         // i = 2
                          html.push('<tr>');
                            html.push('<td style="border-right:1px solid white;">'+prev_branch+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(counter_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:#fc8677;">'+numberWithCommas(credit_amount)+'</td>');
                            html.push('<td style="text-align:right;">'+numberWithCommas(cash_amount)+'</td>');
                            html.push('<td style="text-align:right;">'+numberWithCommas(check_ondate_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(check_post_dated_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(cash_amount + check_ondate_amount + check_post_dated_amount)+'</td>');
                            html.push('<td style="text-align:right;color:chartreuse;">'+numberWithCommas(total_amount)+'</td>');
                          html.push('</tr>'); 

                          counter_amount = 0.00;
                          credit_amount = 0.00;
                          cash_amount = 0.00;
                          check_ondate_amount = 0.00;
                          check_post_dated_amount = 0.00;
                          total_amount = 0.00;

                          if ((salemode == 'Counter')&&(paymode == '')&&(checkdesc == '')){
                            counter_amount = amount;      
                            total_counter = total_counter + amount;
                          }else if ((salemode == 'Credit')&&(paymode == '')&&(checkdesc == '')){
                            credit_amount = amount;      
                            total_credit = total_credit + amount;   
                          }else if ((salemode == 'Collection')&&(paymode == 'Cash')&&(checkdesc == '')){
                            cash_amount = amount;
                            total_cash = total_cash + amount;
                          }else if ((salemode == 'Collection')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                            check_ondate_amount = amount;
                            total_check_ondate = total_check_ondate + amount;
                          }else{
                            check_post_dated_amount = amount;
                            total_check_postdated = total_check_postdated + amount;
                          }

                          total_amount = total_amount + amount;
                          var prev_branch = curr_branch;
                        }              
                      }
                    }
                    if (i > 0){
                      html.push('<tr>');
                        html.push('<td style="border-right:1px solid white;">'+branch_name+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(counter_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:#fc8677;">'+numberWithCommas(credit_amount)+'</td>');
                        html.push('<td style="text-align:right;">'+numberWithCommas(cash_amount)+'</td>');
                        html.push('<td style="text-align:right;">'+numberWithCommas(check_ondate_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(check_post_dated_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(cash_amount + check_ondate_amount + check_post_dated_amount)+'</td>');
                        html.push('<td style="text-align:right;color:chartreuse;">'+numberWithCommas(total_amount)+'</td>');
                      html.push('</tr>');  
                      
                      html.push('<tr>');
                        html.push('<td style="border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">TOTAL AMOUNT</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_counter)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:#fc8677;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_credit)+'</td>');
                        html.push('<td style="text-align:right;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_cash)+'</td>');
                        html.push('<td style="text-align:right;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_check_ondate)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_check_postdated)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_cash + total_check_ondate + total_check_postdated)+'</td>');
                        html.push('<td style="text-align:right;color:chartreuse;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_counter + total_credit + total_cash + total_check_ondate + total_check_postdated)+'</td>');
                      html.push('</tr>');
                    }

                  html.push('</table>');
                html.push('</div>');                 
               }else if (reptype == 8){
                  html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                    html.push('<table class="table mx-auto w-auto">');
                      html.push('<thead>');                      
                        html.push('<tr>');
                          html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;min-width:120px;">Date</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;color:lightpink;">Cash VAT</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;color:lightpink;">Cash Non-VAT</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;color:lightpink;">TOTAL</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:lightskyblue;">Credit VAT</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:lightskyblue;">Credit Non-VAT</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:lightskyblue;">TOTAL</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:plum;">Cash</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:plum;">ODC</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:plum;">PDC</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:plum;">TOTAL</th>');
                          html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:160px;">TOTAL AMOUNT</th>');
                        html.push('</tr>');
                      html.push('</thead>');

                      var counter_VAT_amount = 0.00;
                      var counter_Non_VAT_amount = 0.00;
                      var counter_total = 0.00;
                      var credit_VAT_amount = 0.00;
                      var credit_Non_VAT_amount = 0.00;
                      var credit_total = 0.00;
                      var cash_amount = 0.00;
                      var check_ondate_amount = 0.00;
                      var check_post_dated_amount = 0.00;
                      var total_amount = 0.00;

                      // last row
                      var total_VAT_counter = 0.00;
                      var total_Non_VAT_counter = 0.00;
                      var total_counter = 0.00;

                      var total_VAT_credit = 0.00;
                      var total_Non_VAT_credit = 0.00;
                      var total_credit = 0.00;

                      var total_cash = 0.00;
                      var total_check_ondate = 0.00;
                      var total_check_postdated = 0.00;

                      for(var i = 0; i < answer.length; i++) {
                        var collection = answer[i];  
                        var col_date = collection.cdate;
                        var cdate = col_date.substring(5, 7) + '/' + col_date.substring(8, 10) + '/' + col_date.substring(0, 4);
                        var salemode = collection.salemode;
                        var vatdesc = collection.vatdesc;
                        var paymode = collection.paymode;
                        var checkdesc = collection.checkdesc;
                        var amount = Number(collection.amount);

                        if (i == 0){
                          var prev_date = cdate;    
                          if ((salemode == 'Counter')&&(vatdesc == 'VAT')&&(paymode == '')&&(checkdesc == '')){
                            counter_VAT_amount = amount;  
                            counter_total = counter_total + amount;    
                            // last row
                            total_VAT_counter = total_VAT_counter + amount;
                            total_counter = total_counter + amount;
                          }else if ((salemode == 'Counter')&&(vatdesc == 'Non-VAT')&&(paymode == '')&&(checkdesc == '')){
                            counter_Non_VAT_amount = amount;   
                            counter_total = counter_total + amount; 
                            // last row
                            total_Non_VAT_counter = total_VAT_counter + amount;
                            total_counter = total_counter + amount;
                          }else if ((salemode == 'Credit')&&(vatdesc == 'VAT')&&(paymode == '')&&(checkdesc == '')){
                            credit_VAT_amount = amount; 
                            credit_total = credit_total + amount;
                            // last row
                            total_VAT_credit = total_VAT_credit + amount;     
                            total_credit = total_credit + amount;  
                          }else if ((salemode == 'Credit')&&(vatdesc == 'Non-VAT')&&(paymode == '')&&(checkdesc == '')){
                            credit_Non_VAT_amount = amount;
                            credit_total = credit_total + amount;
                            // last row
                            total_Non_VAT_credit = total_Non_VAT_credit + amount;      
                            total_credit = total_credit + amount;  
                          }else if ((salemode == 'Collection')&&(paymode == 'Cash')&&(checkdesc == '')){
                            cash_amount = amount;
                            total_cash = total_cash + amount;
                          }else if ((salemode == 'Collection')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                            check_ondate_amount = amount;
                            total_check_ondate = total_check_ondate + amount;
                          }else{
                            check_post_dated_amount = amount;
                            total_check_postdated = total_check_postdated + amount;
                          }
                          total_amount = total_amount + amount    // last column
                        }else{
                          var curr_date = cdate;         
                          if (prev_date == curr_date){   
                            if ((salemode == 'Counter')&&(vatdesc == 'VAT')&&(paymode == '')&&(checkdesc == '')){
                              counter_VAT_amount = amount;     
                              counter_total = counter_total + amount; 
                              // last row
                              total_VAT_counter = total_VAT_counter + amount;
                              total_counter = total_counter + amount;
                            }else if ((salemode == 'Counter')&&(vatdesc == 'Non-VAT')&&(paymode == '')&&(checkdesc == '')){
                              counter_Non_VAT_amount = amount;
                              counter_total = counter_total + amount;
                              // last row
                              total_Non_VAT_counter = total_VAT_counter + amount;      
                              total_counter = total_counter + amount;
                            }else if ((salemode == 'Credit')&&(vatdesc == 'VAT')&&(paymode == '')&&(checkdesc == '')){
                              credit_VAT_amount = amount;
                              credit_total = credit_total + amount;
                              // last row
                              total_VAT_credit = total_VAT_credit + amount;      
                              total_credit = total_credit + amount;  
                            }else if ((salemode == 'Credit')&&(vatdesc == 'Non-VAT')&&(paymode == '')&&(checkdesc == '')){
                              credit_Non_VAT_amount = amount;
                              credit_total = credit_total + amount;
                              // last row
                              total_Non_VAT_credit = total_Non_VAT_credit + amount;      
                              total_credit = total_credit + amount;  
                            }else if ((salemode == 'Collection')&&(paymode == 'Cash')&&(checkdesc == '')){
                              cash_amount = amount;
                              total_cash = total_cash + amount;
                            }else if ((salemode == 'Collection')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                              check_ondate_amount = amount;
                              total_check_ondate = total_check_ondate + amount;
                            }else{
                              check_post_dated_amount = amount;
                              total_check_postdated = total_check_postdated + amount;
                            }
                            total_amount = total_amount + amount;   // last column
                          }else{                         
                            html.push('<tr>');
                              html.push('<td style="border-right:1px solid white;color:yellow;">'+prev_date+'</td>');
                              html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(counter_VAT_amount)+'</td>');
                              html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(counter_Non_VAT_amount)+'</td>');
                              html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(counter_total)+'</td>');
                              html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(credit_VAT_amount)+'</td>');
                              html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(credit_Non_VAT_amount)+'</td>');
                              html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(credit_total)+'</td>');
                              html.push('<td style="text-align:right;">'+numberWithCommas(cash_amount)+'</td>');
                              html.push('<td style="text-align:right;">'+numberWithCommas(check_ondate_amount)+'</td>');
                              html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(check_post_dated_amount)+'</td>');
                              html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(cash_amount + check_ondate_amount + check_post_dated_amount)+'</td>');
                              html.push('<td style="text-align:right;color:chartreuse;">'+numberWithCommas(total_amount)+'</td>');
                            html.push('</tr>'); 

                            var counter_VAT_amount = 0.00;
                            var counter_Non_VAT_amount = 0.00;
                            var counter_total = 0.00;
                            var credit_VAT_amount = 0.00;
                            var credit_Non_VAT_amount = 0.00;
                            var credit_total = 0.00;
                            var cash_amount = 0.00;
                            var check_ondate_amount = 0.00;
                            var check_post_dated_amount = 0.00;
                            var total_amount = 0.00;

                            if ((salemode == 'Counter')&&(vatdesc == 'VAT')&&(paymode == '')&&(checkdesc == '')){
                              counter_VAT_amount = amount; 
                              counter_total = counter_total + amount;
                              // last row
                              total_VAT_counter = total_VAT_counter + amount;     
                              total_counter = total_counter + amount;
                            }else if ((salemode == 'Counter')&&(vatdesc == 'Non-VAT')&&(paymode == '')&&(checkdesc == '')){
                              counter_Non_VAT_amount = amount; 
                              counter_total = counter_total + amount; 
                              // last row
                              total_Non_VAT_counter = total_VAT_counter + amount;    
                              total_counter = total_counter + amount;
                            }else if ((salemode == 'Credit')&&(vatdesc == 'VAT')&&(paymode == '')&&(checkdesc == '')){
                              credit_VAT_amount = amount;
                              credit_total = credit_total + amount;
                              // last row
                              total_VAT_credit = total_VAT_credit + amount;      
                              total_credit = total_credit + amount;  
                            }else if ((salemode == 'Credit')&&(vatdesc == 'Non-VAT')&&(paymode == '')&&(checkdesc == '')){
                              credit_Non_VAT_amount = amount;
                              credit_total = credit_total + amount;
                              // last row
                              total_Non_VAT_credit = total_Non_VAT_credit + amount;      
                              total_credit = total_credit + amount;  
                            }else if ((salemode == 'Collection')&&(paymode == 'Cash')&&(checkdesc == '')){
                              cash_amount = amount;
                              total_cash = total_cash + amount;
                            }else if ((salemode == 'Collection')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                              check_ondate_amount = amount;
                              total_check_ondate = total_check_ondate + amount;
                            }else{
                              check_post_dated_amount = amount;
                              total_check_postdated = total_check_postdated + amount;
                            }

                            total_amount = total_amount + amount;   // last column
                            var prev_date = curr_date;
                          }              
                        }
                      }
                      if (i > 0){
                        html.push('<tr>');
                          html.push('<td style="border-right:1px solid white;color:yellow;">'+cdate+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(counter_VAT_amount)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(counter_Non_VAT_amount)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(counter_total)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(credit_VAT_amount)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(credit_Non_VAT_amount)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(credit_total)+'</td>');
                          html.push('<td style="text-align:right;">'+numberWithCommas(cash_amount)+'</td>');
                          html.push('<td style="text-align:right;">'+numberWithCommas(check_ondate_amount)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(check_post_dated_amount)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(cash_amount + check_ondate_amount + check_post_dated_amount)+'</td>');
                          html.push('<td style="text-align:right;color:chartreuse;">'+numberWithCommas(total_amount)+'</td>');
                        html.push('</tr>');  
                        
                        html.push('<tr>');
                          html.push('<td style="border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">TOTAL</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_VAT_counter)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_Non_VAT_counter)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_counter)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_VAT_credit)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_Non_VAT_credit)+'</td>');                          
                          html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_credit)+'</td>');
                          html.push('<td style="text-align:right;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_cash)+'</td>');
                          html.push('<td style="text-align:right;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_check_ondate)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_check_postdated)+'</td>');
                          html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_cash + total_check_ondate + total_check_postdated)+'</td>');
                          html.push('<td style="text-align:right;color:chartreuse;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_counter + total_credit + total_cash + total_check_ondate + total_check_postdated)+'</td>');
                        html.push('</tr>');
                      }

                    html.push('</table>');
                  html.push('</div>');                
               }else if (reptype == 9){
                html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                  html.push('<table class="table mx-auto w-auto">');
                    html.push('<thead>');                      
                      html.push('<tr>');
                        html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;min-width:120px;">Branch</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;color:lightpink;">Cash VAT</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;color:lightpink;">Cash Non-VAT</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;color:lightpink;">TOTAL</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:lightskyblue;">Credit VAT</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:lightskyblue;">Credit Non-VAT</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:lightskyblue;">TOTAL</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:plum;">Cash</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:plum;">ODC</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:plum;">PDC</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:140px;color:plum;">TOTAL</th>');
                        html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;min-width:160px;">TOTAL AMOUNT</th>');
                      html.push('</tr>');
                    html.push('</thead>');

                    var counter_VAT_amount = 0.00;
                    var counter_Non_VAT_amount = 0.00;
                    var counter_total = 0.00;
                    var credit_VAT_amount = 0.00;
                    var credit_Non_VAT_amount = 0.00;
                    var credit_total = 0.00;
                    var cash_amount = 0.00;
                    var check_ondate_amount = 0.00;
                    var check_post_dated_amount = 0.00;
                    var total_amount = 0.00;

                    // last row
                    var total_VAT_counter = 0.00;
                    var total_Non_VAT_counter = 0.00;
                    var total_counter = 0.00;

                    var total_VAT_credit = 0.00;
                    var total_Non_VAT_credit = 0.00;
                    var total_credit = 0.00;

                    var total_cash = 0.00;
                    var total_check_ondate = 0.00;
                    var total_check_postdated = 0.00;

                    for(var i = 0; i < answer.length; i++) {
                      var collection = answer[i];  
                      var branch_name = collection.branch_name;
                      var salemode = collection.salemode;
                      var vatdesc = collection.vatdesc;
                      var paymode = collection.paymode;
                      var checkdesc = collection.checkdesc;
                      var amount = Number(collection.amount);

                      if (i == 0){
                        var prev_branch = branch_name;    
                        if ((salemode == 'Counter')&&(vatdesc == 'VAT')&&(paymode == '')&&(checkdesc == '')){
                          counter_VAT_amount = amount;  
                          counter_total = counter_total + amount;    
                          // last row
                          total_VAT_counter = total_VAT_counter + amount;
                          total_counter = total_counter + amount;
                        }else if ((salemode == 'Counter')&&(vatdesc == 'Non-VAT')&&(paymode == '')&&(checkdesc == '')){
                          counter_Non_VAT_amount = amount;   
                          counter_total = counter_total + amount; 
                          // last row
                          total_Non_VAT_counter = total_VAT_counter + amount;
                          total_counter = total_counter + amount;
                        }else if ((salemode == 'Credit')&&(vatdesc == 'VAT')&&(paymode == '')&&(checkdesc == '')){
                          credit_VAT_amount = amount; 
                          credit_total = credit_total + amount;
                          // last row
                          total_VAT_credit = total_VAT_credit + amount;     
                          total_credit = total_credit + amount;  
                        }else if ((salemode == 'Credit')&&(vatdesc == 'Non-VAT')&&(paymode == '')&&(checkdesc == '')){
                          credit_Non_VAT_amount = amount;
                          credit_total = credit_total + amount;
                          // last row
                          total_Non_VAT_credit = total_Non_VAT_credit + amount;      
                          total_credit = total_credit + amount;  
                        }else if ((salemode == 'Collection')&&(paymode == 'Cash')&&(checkdesc == '')){
                          cash_amount = amount;
                          total_cash = total_cash + amount;
                        }else if ((salemode == 'Collection')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                          check_ondate_amount = amount;
                          total_check_ondate = total_check_ondate + amount;
                        }else{
                          check_post_dated_amount = amount;
                          total_check_postdated = total_check_postdated + amount;
                        }
                        total_amount = total_amount + amount    // last column
                      }else{
                        var curr_branch = branch_name;         
                        if (prev_branch == curr_branch){   
                          if ((salemode == 'Counter')&&(vatdesc == 'VAT')&&(paymode == '')&&(checkdesc == '')){
                            counter_VAT_amount = amount;     
                            counter_total = counter_total + amount; 
                            // last row
                            total_VAT_counter = total_VAT_counter + amount;
                            total_counter = total_counter + amount;
                          }else if ((salemode == 'Counter')&&(vatdesc == 'Non-VAT')&&(paymode == '')&&(checkdesc == '')){
                            counter_Non_VAT_amount = amount;
                            counter_total = counter_total + amount;
                            // last row
                            total_Non_VAT_counter = total_VAT_counter + amount;      
                            total_counter = total_counter + amount;
                          }else if ((salemode == 'Credit')&&(vatdesc == 'VAT')&&(paymode == '')&&(checkdesc == '')){
                            credit_VAT_amount = amount;
                            credit_total = credit_total + amount;
                            // last row
                            total_VAT_credit = total_VAT_credit + amount;      
                            total_credit = total_credit + amount;  
                          }else if ((salemode == 'Credit')&&(vatdesc == 'Non-VAT')&&(paymode == '')&&(checkdesc == '')){
                            credit_Non_VAT_amount = amount;
                            credit_total = credit_total + amount;
                            // last row
                            total_Non_VAT_credit = total_Non_VAT_credit + amount;      
                            total_credit = total_credit + amount;  
                          }else if ((salemode == 'Collection')&&(paymode == 'Cash')&&(checkdesc == '')){
                            cash_amount = amount;
                            total_cash = total_cash + amount;
                          }else if ((salemode == 'Collection')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                            check_ondate_amount = amount;
                            total_check_ondate = total_check_ondate + amount;
                          }else{
                            check_post_dated_amount = amount;
                            total_check_postdated = total_check_postdated + amount;
                          }
                          total_amount = total_amount + amount;   // last column
                        }else{                         
                          html.push('<tr>');
                            html.push('<td style="border-right:1px solid white;color:yellow;">'+prev_branch+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(counter_VAT_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(counter_Non_VAT_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(counter_total)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(credit_VAT_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(credit_Non_VAT_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(credit_total)+'</td>');
                            html.push('<td style="text-align:right;">'+numberWithCommas(cash_amount)+'</td>');
                            html.push('<td style="text-align:right;">'+numberWithCommas(check_ondate_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(check_post_dated_amount)+'</td>');
                            html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(cash_amount + check_ondate_amount + check_post_dated_amount)+'</td>');
                            html.push('<td style="text-align:right;color:chartreuse;">'+numberWithCommas(total_amount)+'</td>');
                          html.push('</tr>'); 

                          var counter_VAT_amount = 0.00;
                          var counter_Non_VAT_amount = 0.00;
                          var counter_total = 0.00;
                          var credit_VAT_amount = 0.00;
                          var credit_Non_VAT_amount = 0.00;
                          var credit_total = 0.00;
                          var cash_amount = 0.00;
                          var check_ondate_amount = 0.00;
                          var check_post_dated_amount = 0.00;
                          var total_amount = 0.00;

                          if ((salemode == 'Counter')&&(vatdesc == 'VAT')&&(paymode == '')&&(checkdesc == '')){
                            counter_VAT_amount = amount; 
                            counter_total = counter_total + amount;
                            // last row
                            total_VAT_counter = total_VAT_counter + amount;     
                            total_counter = total_counter + amount;
                          }else if ((salemode == 'Counter')&&(vatdesc == 'Non-VAT')&&(paymode == '')&&(checkdesc == '')){
                            counter_Non_VAT_amount = amount; 
                            counter_total = counter_total + amount; 
                            // last row
                            total_Non_VAT_counter = total_VAT_counter + amount;    
                            total_counter = total_counter + amount;
                          }else if ((salemode == 'Credit')&&(vatdesc == 'VAT')&&(paymode == '')&&(checkdesc == '')){
                            credit_VAT_amount = amount;
                            credit_total = credit_total + amount;
                            // last row
                            total_VAT_credit = total_VAT_credit + amount;      
                            total_credit = total_credit + amount;  
                          }else if ((salemode == 'Credit')&&(vatdesc == 'Non-VAT')&&(paymode == '')&&(checkdesc == '')){
                            credit_Non_VAT_amount = amount;
                            credit_total = credit_total + amount;
                            // last row
                            total_Non_VAT_credit = total_Non_VAT_credit + amount;      
                            total_credit = total_credit + amount;  
                          }else if ((salemode == 'Collection')&&(paymode == 'Cash')&&(checkdesc == '')){
                            cash_amount = amount;
                            total_cash = total_cash + amount;
                          }else if ((salemode == 'Collection')&&(paymode == 'Check')&&(checkdesc == 'On-date')){
                            check_ondate_amount = amount;
                            total_check_ondate = total_check_ondate + amount;
                          }else{
                            check_post_dated_amount = amount;
                            total_check_postdated = total_check_postdated + amount;
                          }

                          total_amount = total_amount + amount;   // last column
                          var prev_branch = curr_branch;
                        }              
                      }
                    }
                    if (i > 0){
                      html.push('<tr>');
                        html.push('<td style="border-right:1px solid white;color:yellow;">'+branch_name+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(counter_VAT_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(counter_Non_VAT_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(counter_total)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(credit_VAT_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:white;">'+numberWithCommas(credit_Non_VAT_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(credit_total)+'</td>');
                        html.push('<td style="text-align:right;">'+numberWithCommas(cash_amount)+'</td>');
                        html.push('<td style="text-align:right;">'+numberWithCommas(check_ondate_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;">'+numberWithCommas(check_post_dated_amount)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;">'+numberWithCommas(cash_amount + check_ondate_amount + check_post_dated_amount)+'</td>');
                        html.push('<td style="text-align:right;color:chartreuse;">'+numberWithCommas(total_amount)+'</td>');
                      html.push('</tr>');  
                      
                      html.push('<tr>');
                        html.push('<td style="border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">TOTAL</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_VAT_counter)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_Non_VAT_counter)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_counter)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_VAT_credit)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_Non_VAT_credit)+'</td>');                          
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_credit)+'</td>');
                        html.push('<td style="text-align:right;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_cash)+'</td>');
                        html.push('<td style="text-align:right;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_check_ondate)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_check_postdated)+'</td>');
                        html.push('<td style="text-align:right;border-right:1px solid white;color:aqua;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_cash + total_check_ondate + total_check_postdated)+'</td>');
                        html.push('<td style="text-align:right;color:chartreuse;border-top:1px solid white;border-bottom:1px solid white;font-weight:bold;font-size:1.2em;">'+numberWithCommas(total_counter + total_credit + total_cash + total_check_ondate + total_check_postdated)+'</td>');
                      html.push('</tr>');
                    }

                  html.push('</table>');
                html.push('</div>');                
               }
               $('.collection_content').html(html.join(''));  
             }
        })    
    });
    
    $("#btn-print-report").click(function(){
        var branchcode = $('#lst-branchcode').val();
        let date_range = $("#lst_date_range").val();
        let start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
        let end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
  
        let customercode = $("#lst-customercode").val();
        let paymode = $("#lst-paymode").val();
        let reptype = $("#lst-reptype").val();

        let generatedby = $("#tns-generatedby").val();
  
        window.open("extensions/tcpdf/pdf/collection_report.php?start_date="+start_date+"&end_date="+end_date+"&customercode="+customercode+"&branchcode="+branchcode+"&paymode="+paymode+"&reptype="+reptype+"&generatedby="+generatedby, "_blank");
    });    

    $("#btn-export").click(function(){
      alert("mom");
      exportToExcel();
    });   

    function exportToExcel() {
      var location = 'data:application/vnd.ms-excel;base64,';
      var excelTemplate = '<html> ' +
      '<head> ' +
      '<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/> ' +
      '</head> ' +
      '<body> ' +
      document.getElementById("collection_content").innerHTML +
      '</body> ' +
      '</html>'
      window.location.href = location + window.btoa(excelTemplate);
    }    
});    