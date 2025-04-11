$(function() { 
    var generatedby = $("#generatedby").val();

    $(".select").select2({
        minimumResultsForSearch: Infinity,
    });

    // select with search
    $(".select-search").select2(); 

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); 
    var yyyy = today.getFullYear();

    var currentDate = mm + '/' + dd + '/' + yyyy;

    // Assign the date to the input field
    $('#date-paydate').val(currentDate);
    
    // Clearing Customer selection
    $("#lbl-lst-customercode").click(function(){
       $("#lst-customercode").val('').trigger('change');
    });
 
    // $("#lbl-lst-paymode").click(function(){
    //    $("#lst-paymode").val('').trigger('change');
    // });    
 
    $('#date-paydate, #lst-customercode, #lst-paymode, #lst-reptype').on("change", function(){
       $("#btn-print-report").prop('disabled', false);
       $("#btn-export").prop('disabled', false);
 
       let pay_date = $("#date-paydate").val();
       let paydate = pay_date.substring(6, 10) + '-' + pay_date.substring(0, 2) + '-' + pay_date.substring(3, 5);
       let customercode = $("#lst-customercode").val();
       let paymode = $("#lst-paymode").val();
       let reptype = $("#lst-reptype").val();
 
       var data = new FormData();
       data.append("paydate", paydate);
       data.append("customercode", customercode);
       data.append("paymode", paymode);
       data.append("reptype", reptype);
 
       $.ajax({
            url:"ajax/receivable_report.ajax.php",   
            method: "POST",                
            data: data,                    
            cache: false,                  
            contentType: false,            
            processData: false,            
            dataType:"json",               
            success:function(answer){
              $(".receivable_content").empty();
              var html = [];
 
              if (reptype == 1){
                html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                  html.push('<table class="table mx-auto w-auto">');
                    html.push('<thead>');
                    html.push('<tr>');
                      html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Customer</th>');
                      html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                      html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Paid</th>');
                      html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Balance</th>');
                    html.push('</tr>');
                    html.push('</thead>');

                    var total_credit = 0.00;
                    var total_paid = 0.00;
                    var total_balance = 0.00;

                    var overall_credit = 0.00;
                    var overall_paid = 0.00;
                    var overall_balance = 0.00;

                    for(var i = 0; i < answer.length; i++) {
                        var receivable = answer[i];
                        var name = receivable.name;

                        var invno = receivable.invno;
                        var netamount = numberWithCommas(receivable.netamount);
                        var amount = numberWithCommas(receivable.amount);
                        var balance = numberWithCommas(receivable.balance);
                        // alert(invno + ' ' + netamount + ' ' + amount + ' ' + balance);

                        if (i == 0){
                          var prev_name = receivable.name;                // i = 0, Handumanan Customer 1
                          var prev_invno = receivable.invno;              // i = 0, HD0032300001

                          total_credit = total_credit + Number(receivable.netamount);
                          total_paid = total_paid + Number(receivable.amount);
                          total_balance = total_balance + Number(receivable.balance);

                          overall_credit = overall_credit + Number(receivable.netamount);
                          overall_paid = overall_paid + Number(receivable.amount);
                          overall_balance = overall_balance + Number(receivable.balance);
                        }else{
                          var curr_name = receivable.name;                // i = 1, Handumanan Customer 2,
                                                                          // i = 2, Juan 100
                                                                          // i = 3, Juan 100

                          var curr_invno = receivable.invno;              // i = 1, HD0032300002
                                                                          // i = 2, MB0452300001
                          if (prev_name == curr_name){
                            if (prev_invno != curr_invno){
                              total_credit = total_credit + Number(receivable.netamount);
                              total_paid = total_paid + Number(receivable.amount);
                              total_balance = total_balance + Number(receivable.balance);

                              overall_credit = overall_credit + Number(receivable.netamount);
                              overall_paid = overall_paid + Number(receivable.amount);
                              overall_balance = overall_balance + Number(receivable.balance);
                            }

                            var prev_invno = curr_invno;
                          }else{
                            html.push('<tr>');
                              html.push('<td>'+prev_name+'</td>');
                              html.push('<td style="text-align:right;">'+numberWithCommas(total_credit)+'</td>');
                              html.push('<td style="text-align:right;">'+numberWithCommas(total_paid)+'</td>');
                              html.push('<td style="text-align:right;">'+numberWithCommas(total_balance)+'</td>');
                            html.push('</tr>');
                            
                            total_credit = Number(receivable.netamount);
                            total_paid = Number(receivable.amount);
                            total_balance = Number(receivable.balance);

                            overall_credit = overall_credit + Number(receivable.netamount);
                            overall_paid = overall_paid + Number(receivable.amount);
                            overall_balance = overall_balance + Number(receivable.balance);
                          }
                          var prev_name = curr_name;                       // i = 1, Handumanan Customer 2
                                                                           // i = 2, Juan 100
                          var prev_invno = curr_invno;                                                  
                        } 
                    }  

                    if (i > 0){
                      // var curr_invno = receivable.invno;
                      // if (prev_invno != curr_invno){

                      html.push('<tr>');
                        html.push('<td>'+prev_name+'</td>');
                        html.push('<td style="text-align:right;">'+numberWithCommas(total_credit)+'</td>');
                        html.push('<td style="text-align:right;">'+numberWithCommas(total_paid)+'</td>');
                        html.push('<td style="text-align:right;">'+numberWithCommas(total_balance)+'</td>');
                      html.push('</tr>'); 
                      // }

                      html.push('<tr>');
                        html.push('<td style="font-size:1.1em;font-weight:bold;border-top: 2px solid white;">OVERALL AMOUNT</td>');
                        html.push('<td style="font-size:1.1em;font-weight:bold;border-top: 2px solid white;">'+numberWithCommas(overall_credit)+'</td>');
                        html.push('<td style="font-size:1.1em;font-weight:bold;border-top: 2px solid white;">'+numberWithCommas(overall_paid)+'</td>');
                        html.push('<td style="font-size:1.1em;font-weight:bold;border-top: 2px solid white;">'+numberWithCommas(overall_balance)+'</td>');
                      html.push('</tr>');
                    }

                  html.push('</table>');
                html.push('</div>');
           
              }else if (reptype == 2){
               html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                 html.push('<table class="table mx-auto w-auto">');
                   html.push('<thead>');
                   html.push('<tr>');
                     html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Customer</th>');
                     html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Date</th>');
                     html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Receipt #</th>');
                     html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                     html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Paid</th>');
                     html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Balance</th>');
                     html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Print</th>');
                   html.push('</tr>');
                   html.push('</thead>');

                   var total_credit = 0.00;
                   var total_paid = 0.00;
                   var total_balance = 0.00;

                   var client_credit = 0.00;
                   var client_paid = 0.00;
                   var client_balance = 0.00;
 
                   for(var i = 0; i < answer.length; i++) {
                       var receivable = answer[i];
                       var detail = receivable.detail;
                       var customercode = receivable.customercode;
                       var name = receivable.name;
                       var client_name = receivable.name;;
 
                       var inv_date = receivable.sdate;
                       var sdate = inv_date.substring(5, 7) + '/' + inv_date.substring(8, 10) + '/' + inv_date.substring(0, 4);
 
                       var invno = receivable.invno;
                       var receiptnum = receivable.receiptnum;
                       var receiptnum = receivable.receiptnum;
                       var netamount = numberWithCommas(receivable.netamount);
                       var amount = numberWithCommas(receivable.amount);
                       var balance = numberWithCommas(receivable.balance);
                       var button = '<button style="z-index:2;" type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon rounded-round border-2 ml-2 btnPrintStatement" paydate="'+paydate+'" paymode="" customercode="'+customercode+'" client_name="'+client_name+'" reptype="2" generatedby="'+generatedby+'"><i class="icon-printer"></i></button>';

                       if (i == 0){
                          var prev_name = receivable.name;
                          client_credit = client_credit + Number(receivable.netamount);
                          client_paid = client_paid + Number(receivable.amount);
                          client_balance = client_balance + Number(receivable.balance);
                       }else{
                          var curr_name = receivable.name;
                          if (prev_name == curr_name){
                            name = '';
                            button = '';
                            client_credit = client_credit + Number(receivable.netamount);
                            client_paid = client_paid + Number(receivable.amount);
                            client_balance = client_balance + Number(receivable.balance);
                          }else{
                            // Display sub-total balance of each client
                            html.push('<tr>');
                              html.push('<td style="border-bottom:1px solid white;"></td>');
                              html.push('<td style="border-bottom:1px solid white;"></td>');
                              html.push('<td style="border-right: 2px solid white;text-align:right;color:darkorange;border-bottom:1px solid white;border-top:1px solid peachpuff;">SUB-TOTAL</td>');
                              html.push('<td style="text-align:right;font-size:1.1em;color:aqua;border-bottom:1px solid white;border-top:1px solid peachpuff;">'+numberWithCommas(client_credit)+'</td>');
                              html.push('<td style="text-align:right;font-size:1.1em;color:#0FFF50;border-bottom:1px solid white;border-top:1px solid peachpuff;">'+numberWithCommas(client_paid)+'</td>');
                              html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;">'+numberWithCommas(client_balance)+'</td>');
                            html.push('</tr>');      
                            
                            client_credit = Number(receivable.netamount);;
                            client_paid = Number(receivable.amount);
                            client_balance = Number(receivable.balance);
                          }
                          var prev_name = curr_name;
                       } 

                       if (i == 0){
                          var prev_invno = receivable.invno;
                          total_credit = total_credit + Number(receivable.netamount);
                          total_paid = total_paid + Number(receivable.amount);
                          total_balance = total_balance + Number(receivable.balance);
  
                          html.push('<tr>');
                             html.push('<td>'+name+'</td>');
                             html.push('<td>'+sdate+'</td>');
                             html.push('<td style="border-right: 2px solid white;">'+receiptnum+'</td>');
                             html.push('<td style="text-align:right;">'+netamount+'</td>');
                             html.push('<td style="text-align:right;">'+amount+'</td>');
                             html.push('<td style="text-align:right;">'+balance+'</td>');
                             html.push('<td style="text-align:right;">'+button+'</td>');
                            //  html.push('<td><button style="z-index:2;" type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon rounded-round border-2 ml-2 btnPrintStatement" customercode="'+customercode+'" generatedby="'+generatedby+'"><i class="icon-printer"></i></button></td>');
                          html.push('</tr>'); 
                       }else{
                          var curr_invno = receivable.invno;
                          if (prev_invno != curr_invno){
                             total_credit = total_credit + Number(receivable.netamount);
                             total_paid = total_paid + Number(receivable.amount);
                             total_balance = total_balance + Number(receivable.balance);
                            
                             html.push('<tr>');
                                html.push('<td>'+name+'</td>');
                                html.push('<td>'+sdate+'</td>');
                                html.push('<td style="border-right: 2px solid white;">'+receiptnum+'</td>');
                                html.push('<td style="text-align:right;">'+netamount+'</td>');
                                html.push('<td style="text-align:right;">'+amount+'</td>');
                                html.push('<td style="text-align:right;">'+balance+'</td>');
                                html.push('<td style="text-align:right;">'+button+'</td>');
                                //html.push('<td><button style="z-index:2;" type="button" class="btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon rounded-round border-2 ml-2 btnPrintStatement" customercode="'+customercode+'" generatedby="'+generatedby+'"><i class="icon-printer"></i></button></td>');
                             html.push('</tr>'); 
                          }
                          var prev_invno = curr_invno;
                       }
                   }  

                   if (i > 0){
                      // Display the last Total Balance of the last customer
                      html.push('<tr>');
                        html.push('<td style="border-bottom:1px solid white;"></td>');
                        html.push('<td style="border-bottom:1px solid white;"></td>');
                        html.push('<td style="border-right: 2px solid white;text-align:right;color:darkorange;border-bottom:1px solid white;border-top:1px solid peachpuff;">SUB-TOTAL</td>');
                        html.push('<td style="text-align:right;font-size:1.1em;color:aqua;border-bottom:1px solid white;border-top:1px solid peachpuff;">'+numberWithCommas(client_credit)+'</td>');
                        html.push('<td style="text-align:right;font-size:1.1em;color:#0FFF50;border-bottom:1px solid white;border-top:1px solid peachpuff;">'+numberWithCommas(client_paid)+'</td>');
                        html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;">'+numberWithCommas(client_balance)+'</td>');
                      html.push('</tr>');

                      // Display OVERALL TOTAL AMOUNT after loop finished executing
                      html.push('<tr>');
                          html.push('<td colspan="3" style="text-align:right;font-size:1.2em;font-weight:bold;border-top: 2px solid white;">OVERALL AMOUNT</td>');
                          html.push('<td style="text-align:right;font-size:1.2em;font-weight:bold;border-top: 2px solid white;">'+numberWithCommas(total_credit)+'</td>');
                          html.push('<td style="text-align:right;font-size:1.2em;font-weight:bold;border-top: 2px solid white;">'+numberWithCommas(total_paid)+'</td>');
                          html.push('<td style="text-align:right;font-size:1.2em;font-weight:bold;border-top: 2px solid white;">'+numberWithCommas(total_balance)+'</td>');
                      html.push('</tr>');
                   }

                 html.push('</table>');
               html.push('</div>');              
              }else if (reptype == 3){
                html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                  html.push('<table class="table mx-auto w-auto">');
                    html.push('<thead>');
                    html.push('<tr>');
                      html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Customer</th>');
                      html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Date</th>');
                      html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Receipt #</th>');
                      html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                      html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Paid</th>');
                      html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Balance</th>');
                      html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">0-7</th>');
                      html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">8-15</th>');
                      html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">16-22</th>');
                      html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">23-31</th>');
                      html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">&gt; Month</th>');
                    html.push('</tr>');
                    html.push('</thead>');

                    var total_credit = 0.00;
                    var total_paid = 0.00;
                    var total_balance = 0.00;

                    var client_credit = 0.00;
                    var client_paid = 0.00;
                    var client_balance = 0.00;

                    for(var i = 0; i < answer.length; i++) {
                        var receivable = answer[i];
                        var detail = receivable.detail;
                        var name = receivable.name;

                        var inv_date = receivable.sdate;
                        var sdate = inv_date.substring(5, 7) + '/' + inv_date.substring(8, 10) + '/' + inv_date.substring(0, 4);

                        var invno = receivable.invno;
                        var receiptnum = receivable.receiptnum;
                        var netamount = numberWithCommas(receivable.netamount);
                        var amount = numberWithCommas(receivable.amount);
                        var balance = numberWithCommas(receivable.balance);
                        var age = Number(receivable.age);

                        if (i == 0){
                          var prev_name = receivable.name;
                          client_credit = client_credit + Number(receivable.netamount);
                          client_paid = client_paid + Number(receivable.amount);
                          client_balance = client_balance + Number(receivable.balance);
                        }else{
                          var curr_name = receivable.name;
                          if (prev_name == curr_name){
                            name = '';
                            client_credit = client_credit + Number(receivable.netamount);
                            client_paid = client_paid + Number(receivable.amount);
                            client_balance = client_balance + Number(receivable.balance);
                          }else{
                            // Display sub-total balance of each client
                            html.push('<tr>');
                              html.push('<td style="border-bottom:1px solid white;"></td>');
                              html.push('<td style="border-bottom:1px solid white;"></td>');
                              html.push('<td style="border-right: 2px solid white;text-align:right;color:darkorange;border-bottom:1px solid white;border-top:1px solid peachpuff;">SUB-TOTAL</td>');
                              html.push('<td style="text-align:right;font-size:1.1em;color:aqua;border-bottom:1px solid white;border-top:1px solid peachpuff;">'+numberWithCommas(client_credit)+'</td>');
                              html.push('<td style="text-align:right;font-size:1.1em;color:#0FFF50;border-bottom:1px solid white;border-top:1px solid peachpuff;">'+numberWithCommas(client_paid)+'</td>');
                              html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;border-right:1px solid white;">'+numberWithCommas(client_balance)+'</td>');
                              html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;"></td>');
                              html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;"></td>');
                              html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;"></td>');
                              html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;"></td>');
                              html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;"></td>');
                            html.push('</tr>');      
                            
                            client_credit = Number(receivable.netamount);;
                            client_paid = Number(receivable.amount);
                            client_balance = Number(receivable.balance);
                          }
                          var prev_name = curr_name;
                        } 

                        if (i == 0){
                          var prev_invno = receivable.invno;
                          total_credit = total_credit + Number(receivable.netamount);
                          total_paid = total_paid + Number(receivable.amount);
                          total_balance = total_balance + Number(receivable.balance);
  
                          html.push('<tr>');
                              html.push('<td>'+name+'</td>');
                              html.push('<td>'+sdate+'</td>');
                              html.push('<td style="border-right: 2px solid white;">'+receiptnum+'</td>');
                              html.push('<td style="text-align:right;">'+netamount+'</td>');
                              html.push('<td style="text-align:right;">'+amount+'</td>');
                              html.push('<td style="text-align:right;border-right:1px solid white;">'+balance+'</td>');
                              if (age <= 7){
                                html.push('<td style="text-align:right;">'+balance+'</td>');
                              }else if (age <= 15){
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;">'+balance+'</td>');
                              }else if (age <= 22){
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;">'+balance+'</td>');
                              }else if (age <= 31){
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;">'+balance+'</td>');
                              }else{
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;"></td>');
                                html.push('<td style="text-align:right;">'+balance+'</td>');
                              }
                          html.push('</tr>'); 
                        }else{
                          var curr_invno = receivable.invno;
                          if (prev_invno != curr_invno){
                              total_credit = total_credit + Number(receivable.netamount);
                              total_paid = total_paid + Number(receivable.amount);
                              total_balance = total_balance + Number(receivable.balance);
      
                              html.push('<tr>');
                                html.push('<td>'+name+'</td>');
                                html.push('<td>'+sdate+'</td>');
                                html.push('<td style="border-right: 2px solid white;">'+receiptnum+'</td>');
                                html.push('<td style="text-align:right;">'+netamount+'</td>');
                                html.push('<td style="text-align:right;">'+amount+'</td>');
                                html.push('<td style="text-align:right;border-right:1px solid white;">'+balance+'</td>');
                                if (age <= 7){
                                  html.push('<td style="text-align:right;">'+balance+'</td>');
                                }else if (age <= 15){
                                  html.push('<td style="text-align:right;"></td>');
                                  html.push('<td style="text-align:right;">'+balance+'</td>');
                                }else if (age <= 22){
                                  html.push('<td style="text-align:right;"></td>');
                                  html.push('<td style="text-align:right;"></td>');
                                  html.push('<td style="text-align:right;">'+balance+'</td>');
                                }else if (age <= 31){
                                  html.push('<td style="text-align:right;"></td>');
                                  html.push('<td style="text-align:right;"></td>');
                                  html.push('<td style="text-align:right;"></td>');
                                  html.push('<td style="text-align:right;">'+balance+'</td>');
                                }else{
                                  html.push('<td style="text-align:right;"></td>');
                                  html.push('<td style="text-align:right;"></td>');
                                  html.push('<td style="text-align:right;"></td>');
                                  html.push('<td style="text-align:right;"></td>');
                                  html.push('<td style="text-align:right;">'+balance+'</td>');
                                }
                              html.push('</tr>'); 
                          }
                          var prev_invno = curr_invno;
                        }
                    }  

                    if (i > 0){
                      // Display the last Total Balance of the last customer
                      html.push('<tr>');
                        html.push('<td style="border-bottom:1px solid white;"></td>');
                        html.push('<td style="border-bottom:1px solid white;"></td>');
                        html.push('<td style="border-right: 2px solid white;text-align:right;color:darkorange;border-bottom:1px solid white;border-top:1px solid peachpuff;">SUB-TOTAL</td>');
                        html.push('<td style="text-align:right;font-size:1.1em;color:aqua;border-bottom:1px solid white;border-top:1px solid peachpuff;">'+numberWithCommas(client_credit)+'</td>');
                        html.push('<td style="text-align:right;font-size:1.1em;color:#0FFF50;border-bottom:1px solid white;border-top:1px solid peachpuff;">'+numberWithCommas(client_paid)+'</td>');
                        html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;border-right:1px solid white;">'+numberWithCommas(client_balance)+'</td>');
                        html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;"></td>');
                        html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;"></td>');
                        html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;"></td>');
                        html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;"></td>');
                        html.push('<td style="text-align:right;font-size:1.1em;color:#fc8677;border-bottom:1px solid white;border-top:1px solid peachpuff;"></td>');
                      html.push('</tr>');

                      // Display OVERALL TOTAL AMOUNT after loop finished executing
                      html.push('<tr>');
                          html.push('<td colspan="3" style="text-align:right;font-size:1.2em;font-weight:bold;border-top: 2px solid white;">OVERALL AMOUNT</td>');
                          html.push('<td style="text-align:right;font-size:1.2em;font-weight:bold;border-top: 2px solid white;">'+numberWithCommas(total_credit)+'</td>');
                          html.push('<td style="text-align:right;font-size:1.2em;font-weight:bold;border-top: 2px solid white;">'+numberWithCommas(total_paid)+'</td>');
                          html.push('<td style="text-align:right;font-size:1.2em;font-weight:bold;border-top: 2px solid white;">'+numberWithCommas(total_balance)+'</td>');
                      html.push('</tr>');
                    }

                  html.push('</table>');
                html.push('</div>');                
              }
              $('.receivable_content').html(html.join(''));  
            }
       })    
    });    
 
    $("#btn-print-report").click(function(){
       let pay_date = $("#date-paydate").val();
       let paydate = pay_date.substring(6, 10) + '-' + pay_date.substring(0, 2) + '-' + pay_date.substring(3, 5);
       let customercode = $("#lst-customercode").val();
       let paymode = $("#lst-paymode").val();
       let reptype = $("#lst-reptype").val();  
       
       window.open("extensions/tcpdf/pdf/receivableprint.php?paydate="+paydate+"&customercode="+customercode+"&paymode="+paymode+"&reptype="+reptype+"&generatedby="+generatedby, "_blank");
    });

    $(".receivable-report-form").on("click", "button.btnPrintStatement", function(){
        let paydate = $(this).attr("paydate");
        let customercode = $(this).attr("customercode");
        let client_name = $(this).attr("client_name");
        let generatedby = $(this).attr("generatedby");
        let paymode = $(this).attr("paymode");
        let reptype = $(this).attr("reptype");
        window.open("extensions/tcpdf/pdf/soa.php?paydate="+paydate+"&customercode="+customercode+"&customercode="+customercode+"&client_name="+client_name+"&paymode="+paymode+"&reptype="+reptype+"&generatedby="+generatedby, "_blank");
    });

    $("#btn-export").click(function(){
      exportToExcel();
    });   

    function exportToExcel() {
      var location = 'data:application/vnd.ms-excel;base64,';
      var excelTemplate = '<html> ' +
      '<head> ' +
      '<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/> ' +
      '</head> ' +
      '<body> ' +
      document.getElementById("receivable_content").innerHTML +
      '</body> ' +
      '</html>'
      window.location.href = location + window.btoa(excelTemplate);
    }
 });