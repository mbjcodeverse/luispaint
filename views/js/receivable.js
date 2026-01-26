if (!$.fn.DataTable.isDataTable('.receivableListTable')) {
  var it = $('.receivableListTable').DataTable({
      // ajax: "ajax/receivable_list.ajax.php",
      deferRender: true,
      processing: true,
      autoWidth: true,
      scrollY: 360,
      pagelength: 25,
      lengthMenu: [[25, 50], [25, 50]],
      dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
              language: {
                  loadingRecords: 'Fetching receivable list...',
                  processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
                  search: '<span>Filter:</span> _INPUT_',
                  searchPlaceholder: 'Type to filter...',
                  lengthMenu: '<span>Show:</span> _MENU_',
                  paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
              }
  });
}

if (!$.fn.DataTable.isDataTable('.pendingListTable')) {
  var pc = $('.pendingListTable').DataTable({
      deferRender: true,
      processing: true,
      autoWidth: true,
      scrollY: 360,
      pagelength: 25,
      lengthMenu: [[25, 50], [25, 50]],
      dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
              language: {
                  loadingRecords: 'Fetching pending checks...',
                  processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
                  search: '<span>Filter:</span> _INPUT_',
                  searchPlaceholder: 'Type to filter...',
                  lengthMenu: '<span>Show:</span> _MENU_',
                  paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
              }
  });
}

$(function() {
  disableControls();

  $(".select").select2({
    minimumResultsForSearch: Infinity,
  });

  $(".select-search").select2();

  $('#modal-search-receivable').on('shown.bs.modal', function (e) {
      loadReceivableList();
  });

  // Get Customer Receivable List
  $(".receivableListTable tbody").on('click', '.btnCustomerReceivable', function () {
      var customercode = $(this).attr("customercode");
      var customer_name = $(this).attr("name");
      $("#name").val(customer_name.toUpperCase());
      $("#tns-customercode").val(customercode);
      paintReceivableTable(customercode);
  });

  $(".receivable-form").on("keydown keypress focus", "input.payment", function(){
      _gblBindNumericClasses('numeric'); 
      addingTotalPayment();
      checkEmptyEntry();
      listPayments(); 
  });

  $(".receivable-form").on("dblclick", "input.payment", function(){
    var total_balance_amount = $(this).parent().parent().children(".total_balance").children().val();
    $(this).val(total_balance_amount);
    addingTotalPayment();
    checkEmptyEntry();
    listPayments();
  });  

  // Payment Mode - Interactive Change
  $('#sel-paymode').on("change", function(){
    var pay_mode = $("#sel-paymode").val();
    $("#sel-checkdesc").val('').trigger('change');;
    $("#tns-checknum").val('');
    $("#sel-bankcode").val('').trigger('change');;
    $("#date-checkdate").val('');

    if (pay_mode == 'Cash'){         /*Cash*/
      $("#sel-checkdesc").prop('disabled', true);
      $("#tns-checknum").prop('disabled', true);
      $("#sel-bankcode").prop('disabled', true);
      $("#date-checkdate").prop('disabled', true);
    }else{                           /*Check*/
      $("#sel-checkdesc").prop('disabled', false);
      $("#tns-checknum").prop('disabled', false);
      $("#sel-bankcode").prop('disabled', false);
      $("#date-checkdate").prop('disabled', false);
      $("#sel-checkdesc").focus();
    }
    checkEmptyEntry();
  });

  $('#sel-checkdesc,#sel-bankcode').on("change", function(){
     checkEmptyEntry();
  }); 

  $('#tns-checknum').on("keyup", function(){
     checkEmptyEntry();
  });  

   // SAVE Accounts Receivable Payment
  $(".receivable-form").submit(function (e) {
    e.preventDefault();
    swal.fire({
        title: 'Do you want to save accounts receivable transaction?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Save it!',
        cancelButtonText: 'Cancel!',
        confirmButtonClass: 'btn btn-outline-success',
        cancelButtonClass: 'btn btn-outline-danger',
        allowOutsideClick: false,
        buttonsStyling: false
    }).then(function(result) {
        if(result.value) {
          var trans_type = $("#trans_type").val();

          var paymode = $("#sel-paymode").val();
          let format_paydate = $("#date-paydate").val().split("/");
          format_paydate = format_paydate[2] + "-" + format_paydate[0] + "-" + format_paydate[1];          
          var paydate = format_paydate; 

          var checkdesc = $("#sel-checkdesc").val(); 
          var bankcode = $("#sel-bankcode").val(); 
          var checknum = $("#tns-checknum").val();

          if ($("#date-checkdate").val() != ''){
            let format_checkdate = $("#date-checkdate").val().split("/");
            format_checkdate = format_checkdate[2] + "-" + format_checkdate[0] + "-" + format_checkdate[1];          
            var checkdate = format_checkdate;             
          }else{
            var checkdate = '0000-00-00';
          }

          var txt_total_payment = $("#total-payment").val();
          var amount = parseFloat(txt_total_payment.replaceAll(",",""));

          var customercode = $("#tns-customercode").val();
          var postedby = $("#postedby").val();

          let format_postdate = $("#date-posted").val().split("/");
          format_postdate = format_postdate[2] + "-" + format_postdate[0] + "-" + format_postdate[1];          
          var postdate = format_postdate;

          var paymentlist = $("#paymentlist").val();

          //--------------------------------------        
          var receivable = new FormData();
          receivable.append("trans_type", trans_type);
          receivable.append("paydate", paydate);
          receivable.append("paymode", paymode);
          receivable.append("checkdesc", checkdesc);
          receivable.append("bankcode", bankcode);
          receivable.append("checknum", checknum);
          receivable.append("checkdate", checkdate);
          receivable.append("amount", amount);
          receivable.append("customercode", customercode);
          receivable.append("postedby", postedby);
          receivable.append("postdate", postdate);
          receivable.append("paymentlist", paymentlist);

          $.ajax({
             url:"ajax/receivable_save_record.ajax.php",
             method: "POST",
             data: receivable,
             cache: false,
             contentType: false,
             processData: false,
             dataType:"text",
             success:function(answer){
                var cust_code = $("#tns-customercode").val();
                paintReceivableTable(cust_code);
                $("#total-payment").val('0.00');

                let paynum = answer;
                $("#paynum").val(paynum); 
             },
             error: function () {
                alert("Oops. Something went wrong!");
             },
             complete: function () {
                let pay_num = $("#paynum").val(); 
                window.open("extensions/tcpdf/pdf/paymentdetails.php?paynum="+pay_num+"&postedby="+postedby, "_blank");

                swal.fire({
                    title: 'Receivable transaction successfully saved!',
                    type: 'success',
                    confirmButtonText: 'Got it!',
                    confirmButtonClass: 'btn btn-outline-success',
                    allowOutsideClick: false,
                    buttonsStyling: false
                }).then(function(result){
                    if(result.value) {              
                      // window.location = 'incoming';
                    }
                })
             }
          })
        }
     });
  }); 

  $("#btn-pending").click(function(){
      $(".pendingListTable").DataTable().clear();
      pc.draw();

      let customercode = $("#tns-customercode").val();
      var percent = 0;
      var notice = new PNotify({
          text: "Fetching records...",
          addclass: 'stack-left-right bg-primary border-primary',
          type: 'info',
          icon: 'icon-spinner4 spinner',
          hide: false,
          buttons: {
              closer: false,
              sticker: false
          },
          opacity: .9,
          width: "190px"
      });      

      var data = new FormData();
      data.append("customercode", customercode);

      $.ajax({
         url:"ajax/receivable_pending_check_list.ajax.php",   
         method: "POST",                
         data: data,                    
         cache: false,                  
         contentType: false,            
         processData: false,            
         dataType:"json",               
         success:function(answer){
              $(".pendingListTableTable").DataTable().clear();
              for(var i = 0; i < answer.length; i++) {
                percent = Math.round(i/answer.length*100);
                var options = {
                  text: percent + "% complete."
                };

                let spc = answer[i];

                let pay_date = spc.paydate;
                let paydate = pay_date.split("-");
                paydate = paydate[1] + "/" + paydate[2] + "/" + paydate[0];

                let paynum = spc.paynum;
                let bankname = spc.bankname;

                let check_date = spc.checkdate;
                let checkdate = check_date.split("-");
                checkdate = checkdate[1] + "/" + checkdate[2] + "/" + checkdate[0];

                let checknum = spc.checknum;
                let amount = numberWithCommas(spc.amount);

                var actions = "<td><button type='button' class='btn btn-outline-success btn-sm btnMatureCheck' paynum='"+paynum+"'><i class='icon-check'></i> Mature</button> <button type='button' class='btn btn-outline-info btn-sm btnRedeemCheck' paynum='"+paynum+"'><i class='icon-rotate-ccw3'></i> Redeem</button> <button type='button' class='btn btn-outline-danger btn-sm btnBounceCheck' paynum='"+paynum+"'><i class='icon-paperplane'></i>  Bounce</button></td>";
                pc.row.add([paydate, paynum, bankname, checkdate, checknum, amount, actions]); 
              }
              pc.draw();

              notice.update(options);
              notice.remove();
              return;
         },
         beforeSend: function() {
         },  
         complete: function() {
         }, 
      })          
  });   
  
  $('#modal-reset-receivable').on('shown.bs.modal', function (e) {
    $('#reset-override').val('');

    let branchcode = $("#branch_code").val();
    let postedby = $("#postedby").val();

    var checkdata = new FormData();
    checkdata.append("branchcode", branchcode);
    checkdata.append("postedby", postedby);
    $.ajax({
       url:"ajax/receivable_reset.ajax.php",
       method: "POST",
       data: checkdata,
       cache: false,
       contentType: false,
       processData: false,
       dataType:"json",
       success:function(answer){
         $(".reset_content").empty();
         var html = [];

         if (answer.length > 0){
            html.push('<table class="table mx-auto w-auto" style="margin-top:20px;font-size:1.2em;border: 1px solid bisque;">');
                html.push('<thead>');
                  html.push('<tr>');
                    html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;">Customer</th>');
                    html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;">Date</th>');
                    html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;">Invoice #</th>');
                    html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;">Mode</th>');
                    html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;">Chk Date</th>');
                    html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;">Check #</th>');
                    html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;">Status</th>');
                    html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;">Amount</th>');
                  html.push('</tr>');
                html.push('</thead>');
                
                var total_amount = 0.00;
                for(var i = 0; i < answer.length; i++) {
                    var reset = answer[i];
                    var name = reset.name;

                    var pdate = reset.paydate;
                    var paydate = pdate.split("-");
                    paydate = paydate[1] + "/" + paydate[2] + "/" + paydate[0];
                    if (paydate == '00/00/0000'){      
                      paydate = '';
                    }

                    var invno = reset.invno;
                    var paymode = reset.paymode;

                    var check_date = reset.checkdate;
                    var checkdate = check_date.split("-");
                    checkdate = checkdate[1] + "/" + checkdate[2] + "/" + checkdate[0];
                    if (checkdate == '00/00/0000'){      
                      checkdate = '';
                    }

                    var checknum = reset.checknum;
                    var checkdesc = reset.checkdesc;
                    var amount = numberWithCommas(reset.amount);
                    total_amount = total_amount + Number(reset.amount);

                    html.push('<tr>');
                        html.push('<td style="padding-top:4px;padding-bottom:4px;">'+name+'</td>');
                        html.push('<td style="padding-top:4px;padding-bottom:4px;">'+paydate+'</td>');
                        html.push('<td style="padding-top:4px;padding-bottom:4px;">'+invno+'</td>');
                        html.push('<td style="padding-top:4px;padding-bottom:4px;">'+paymode+'</td>');
                        html.push('<td style="padding-top:4px;padding-bottom:4px;">'+checkdate+'</td>');
                        html.push('<td style="padding-top:4px;padding-bottom:4px;">'+checknum+'</td>');
                        html.push('<td style="padding-top:4px;padding-bottom:4px;">'+checkdesc+'</td>');
                        html.push('<td style="padding-top:4px;padding-bottom:4px;text-align:right;">'+amount+'</td>');
                    html.push('</tr>');
                } 

                html.push('<tr>');
                  html.push('<td colspan="6" style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;font-size:1.2em;text-align:right;">TOTAL RECEIVABLE AMOUNT</td>');
                  html.push('<td colspan="2" style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;font-size:1.2em;color:greenyellow;text-align:right;">'+numberWithCommas(total_amount)+'</td>');
                html.push('</tr>');
            html.push('</table>');
            $('.reset_content').html(html.join('')); 

            $("#reset-override").prop('disabled', false);
            $("#btn-admin-direct-reset").prop('disabled', false);
            $("#btn-reset-request").prop('disabled', false);
            $('#reset-override').focus();
         }else{
             html.push('<table class="table mx-auto w-auto" style="margin-top:20px;font-size:1.2em;">');
              html.push('<thead>');
                html.push('<tr>');
                  html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid red;color: red;">NO RECEIVABLE TRANSACTION FOR RESETTING!</th>');
                html.push('</tr>');
              html.push('</thead>');
            html.push('</table>');
            $('.reset_content').html(html.join(''));

            $("#reset-override").prop('disabled', true);
            $("#btn-admin-direct-reset").prop('disabled', true);
            $("#btn-reset-request").prop('disabled', true);
         }
       }
    })
 });          

//  $("#btn-admin-direct-reset").click(function(){
//    reset_receivable();
//  });
 
 // Show Credit Details
//  $(".receivable-form tbody").on('click', '.btnCreditDetails', function () {
//     var invno = $(this).attr("invno");
//     window.open("extensions/tcpdf/pdf/deliverysaleview.php?invno="+invno, "_blank");
//  }); 

//  function reset_receivable(){
//   // Focus input override textbox after sweetalert is closed
//   $('#reset-override').focus();
//   if ($("#reset-override").val() == ''){  // empty override key
//      swal.fire({
//         title: 'Cannot Reset, override key must be entered!',
//         type: 'error',
//         confirmButtonText: 'Got it',
//         confirmButtonClass: 'btn btn-outline-warning',
//         allowOutsideClick: false,
//         buttonsStyling: false
//      }).then(function(result){
//         if(result.value) {              
//           $('#reset-override').focus();
//         }
//      });
//   }else{
//      let override_key = $("#reset-override").val();
//      var reset_sale = new FormData();
//      reset_sale.append("override_key", override_key);
//      $.ajax({
//         url:"ajax/get_override_key.ajax.php",
//         method: "POST",
//         data: reset_sale,
//         cache: false,
//         contentType: false,
//         processData: false,
//         dataType:"json",
//         success:function(answer){
//           if(answer["override"] === undefined){  // override key not found
//             swal.fire({
//               title: 'Cannot Reset, unidentified authorization code!',
//               type: 'error',
//               confirmButtonText: 'Got it',
//               confirmButtonClass: 'btn btn-outline-warning',
//               allowOutsideClick: false,
//               buttonsStyling: false,
//             }).then(function(result){
//               if(result.value) { 
//                 $('#reset-override').val('');
//               }
//             });
//           }else{    // Valid override key
//             swal.fire({
//               title: 'Do you want to RESET receivable transaction?',
//               text: 'You will not be able to revert this process.',
//               type: 'question',
//               showCancelButton: true,
//               confirmButtonText: 'Yes, Reset it!',
//               cancelButtonText: 'Cancel!',
//               confirmButtonClass: 'btn btn-outline-success',
//               cancelButtonClass: 'btn btn-outline-danger',
//               allowOutsideClick: false,
//               buttonsStyling: false
//             }).then(function(result) {
//               if(result.value) {
//                 var branchcode = $("#branch_code").val();
//                 var prefix = $("#prefix").val();
//                 var branch_name = $("#branch_name").val();
//                 var postedby = $("#postedby").val();
//                 var userid = $("#userid").val();
//                 var user_type = $("#user_type").val();
//                 // alert(prefix);

//                 var digityear = twodigityear();

//                 // Count number of Reset (RR + Branch Prefix + User ID + 2 digit year)
//                 // BRANCH PREFIX NOT WORKING - module to get the branch prefix was not included here
//                 // Unlike with cashier.js
//                 var reset_prefix = "RR" + prefix + userid.substring(1, 4) + digityear;
//                 var reset_count = new FormData();
//                 reset_count.append("reset_prefix", reset_prefix);
//                 $.ajax({
//                   url:"ajax/reset_check_count_receivable.ajax.php",
//                   method: "POST",
//                   data: reset_count,
//                   cache: false,
//                   contentType: false,
//                   processData: false,
//                   dataType:"json",
//                   success:function(answer){
//                     let reset_count = answer.length + 1;
//                     let reset_count_string = String(reset_count);
//                     let reset_count_length = reset_count_string.length;

//                     let prefix_num = '0';
//                     let reset_sequence = prefix_num.repeat(4 - reset_count_length) + reset_count;
//                     var resetcode = reset_prefix + reset_sequence;

//                     let resetby = $("#postedby").val();

//                     // Actual Resetting
//                     var reset_data = new FormData();
//                     reset_data.append("branchcode", branchcode);
//                     reset_data.append("resetcode", resetcode);
//                     reset_data.append("resetby", resetby);
//                     $.ajax({
//                        url:"ajax/receivable_transaction_post_reset.ajax.php",
//                        method: "POST",
//                        data: reset_data,
//                        cache: false,
//                        contentType: false,
//                        processData: false,
//                        dataType:"text",
//                        success:function(answer){

//                        },
//                        complete: function () {
//                          swal.fire({
//                             title: 'Receivable reset successfully posted!',
//                             type: 'success',
//                             confirmButtonText: 'Got it',
//                             confirmButtonClass: 'btn btn-outline-success',
//                             allowOutsideClick: false,
//                             buttonsStyling: false
//                          })

//                          $('#modal-reset-receivable').modal('hide');
//                          // $(".reset_content").printArea({ mode: 'popup', popClose: true });
//                          window.open("extensions/tcpdf/pdf/resetreceivable.php?resetcode="+resetcode+"&postedby="+postedby+"&branch_name="+branch_name, "_blank");
//                          initialize();
//                        }
//                     });  
//                   }
//                 });
//               }else if (result.dismiss === Swal.DismissReason.cancel){
//                 $('#reset-override').val('');
//               }
//             });
//           }
//         },
//         error: function () {
//            alert("Oops. Something went wrong!");
//         },
//         complete: function () {
//        }   // (success) get Override key
//      });   // get Override key
//     }      // if (override == '')   
//  }  

  // List Payments
  function listPayments(){
    var paymentlist = [];
    var payment = $(".payment");
    var num_entries = payment.length; 

    if (num_entries > 0){
      for(var i = 0; i < num_entries; i++){
        var txt_amount = $(payment[i]).val();

        var amount = parseFloat(txt_amount.replaceAll(",",""));
        if (amount > 0.00){
           paymentlist.push({"amount" : amount.toFixed(2),
                             "invno" : $(payment[i]).attr("invno")}); 
        }     
      }
      // alert("Mom = " + JSON.stringify(paymentlist));
      $("#paymentlist").val(JSON.stringify(paymentlist));
    }else{
      $("#paymentlist").val('');
    }
  }  

  // Add Total Amount from Table
  function addingTotalPayment(){
    var paymentItems = $(".payment");
    var arrayAdditionPayment = [];  
    for(var i = 0; i < paymentItems.length; i++){
       var p = $(paymentItems[i]).val();
       arrayAdditionPayment.push(Number(p.replace(/,/g, "")));
    }
    function additionArrayPayment(totalPayment, numberArray){
      return totalPayment + numberArray;
    }
    var adding_Total_Payment = arrayAdditionPayment.reduce(additionArrayPayment);
    $("#total-payment").val(numberWithCommas(adding_Total_Payment.toFixed(2))); 
  }   

  function disableControls(){
    $("#date-paydate").prop('disabled', true);
    $("#sel-paymode").prop('disabled', true);
    $("#sel-checkdesc").prop('disabled', true);
    $("#sel-bankcode").prop('disabled', true);
    $("#date-checkdate").prop('disabled', true);
    $("#tns-checknum").prop('disabled', true);
  }       

  function checkEmptyEntry(){
    if(($('#total-payment').val() == '0.00')||
       ($('#sel-paymode').val() == '')||
      (($('#sel-paymode').val() == 'Check')&&(($('#sel-checkdesc').val() == '')||($('#sel-bankcode').val() == '')||($('#date-checkdate').val() == '')||($('#tns-checknum').val() == '')))){
      $("#btn-post").prop('disabled', true);
    }else{
      $("#btn-post").prop('disabled', false);
    }   
  }

  function loadReceivableList(){
    it.clear();
    it.draw();
    $.ajax({
       url:"ajax/receivable_list.ajax.php",
       method: "POST",
       cache: false,
       contentType: false,
       processData: false,
       dataType:"json",
       success:function(answer){
           var num_rec = answer.length;
           for(i=0; i < answer.length; i++){
              let rec = answer[i];

              var customercode = rec.customercode;
              var customer_name = rec.name;
                 
              var buttons =  "<button type='button' class='btn btn-outline btn-sm bg-orange-400 border-orange-400 text-orange-400 btn-icon rounded-round border-2 ml-2 btnCustomerReceivable' customercode='"+rec.customercode+"' name='"+rec.name+"'><i class='icon-clipboard3'></i></button>";
              it.row.add([customer_name, buttons]);
           }
           it.draw();        
       }
    })
  } 

  // function paintReceivableTable(customercode){
  //   var data = new FormData();
  //   data.append("customercode", customercode);
  //   $.ajax({
  //      url:"ajax/receivable_customer_list.ajax.php",
  //      method: "POST",
  //      data: data,
  //      cache: false,
  //      contentType: false,
  //      processData: false,
  //      dataType:"json",
  //      success:function(answer){
  //         $(".enlisted_receivable").empty();

  //         var _amount = 0.00;
  //         var _adjustment = 0.00;
  //         var _netamount = 0.00;
  //         var _posted = 0.00;
  //         var _pending = 0.00;
  //         var _balance = 0.00;          

  //         for(var p = 0; p < answer.length; p++) {
  //           var pay = answer[p];

  //           let sdate = pay.sdate.split("-");
  //           sdate = sdate[1] + "/" + sdate[2] + "/" + sdate[0];

  //           var invno = pay.invno;
  //           var receiptnum = pay.receiptnum;
  //           var salemode = pay.salemode;
  //           var totalamount = numberWithCommas(pay.netamount);
  //           var totaladjustment = '0.00';

  //           var net_amount = Number(pay.netamount);
  //           var netamount = numberWithCommas(net_amount.toFixed(2));

  //           var totalposted;
  //           var totalpending;

  //           var posted_amount;
  //           var pending_amount;
  //           // async = false [so that variables inside AJAX below can be access outside when printing variables on table]
  //           var pay_data = new FormData();
  //           pay_data.append("invno", invno);
  //           $.ajax({
  //              url:"ajax/receivable_get_posted_pending.ajax.php",
  //              method: "POST",
  //              data: pay_data,
  //              async: false,
  //              cache: false,
  //              contentType: false,
  //              processData: false,
  //              dataType:"json",
  //              success:function(posted_pending){
  //                 posted_amount = posted_pending[0].total_amount;
  //                 pending_amount = posted_pending[1].total_amount;

  //                 if (posted_amount == null){
  //                   totalposted = '0.00';
  //                 }else{
  //                   totalposted = numberWithCommas(posted_amount);
  //                   // _posted += Number(posted_amount);
  //                 }

  //                 if (pending_amount == null){
  //                   totalpending = '0.00';
  //                 }else{
  //                   totalpending = numberWithCommas(pending_amount);
  //                   // _pending += Number(pending_amount);
  //                 }
  //              }
  //           })
            
  //           // Calculate Balance of each Invoice #
  //           var tposted = Number(totalposted.replaceAll(",",""));
  //           var tpending = Number(totalpending.replaceAll(",",""));

  //           var balance = net_amount - (tposted + tpending);
  //           var totalbalance = numberWithCommas(balance.toFixed(2));
  //           _balance += balance;

  //           if (balance > 0.00){
  //             // allow only values to TOTALS to be included
  //             // if balance for individual DELNUMBER > 0.00
  //             // see Footer of Table
  //             _amount += Number(pay.netamount);
  //             _adjustment += 0.00;
  //             _netamount += net_amount;
  //             _posted += Number(posted_amount);
  //             _pending += Number(pending_amount);

  //             // Since Balance > 0.00, add row to table
  //             $(".enlisted_receivable").append(
  //               '<tr>'+               
  //                 '<td class="del_date" width="11%" style="padding:2px;">'+
  //                    '<input type="text" style="padding:2px;padding-right:6px;text-align:center;" class="form-control sdate numeric" invno="'+invno+'" name="sdate" value="'+sdate+'" readonly required>'+
  //                 '</td>' +

  //                 '<td class="del_number" width="11%" style="padding:2px;">'+
  //                    '<button type="button" class="col-sm-10 btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon ml-2 btnCreditDetails" invno="'+invno+'">'+receiptnum+'</button>'+
  //                 '</td>' +  

  //                 '<td class="total_amount" width="11%" style="padding:2px;">'+
  //                    '<input type="text" style="padding:2px;padding-right:6px;text-align:center;color:#f0c7fc;" class="form-control totalamount numeric" invno="'+invno+'" name="totalamount" value="'+invno+'" readonly required>'+
  //                 '</td>' +  

  //                 '<td class="total_adjustment" width="11%" style="padding:2px;">'+
  //                    '<input type="text" style="padding:2px;padding-right:6px;text-align:right;" class="form-control totaladjustment numeric" invno="'+invno+'" name="totaladjustment" value="'+totaladjustment+'" readonly required>'+
  //                 '</td>' + 

  //                 '<td class="net_amount" width="11%" style="padding:2px;">'+
  //                    '<input type="text" style="padding:2px;padding-right:6px;text-align:right;color:#a2edfa;" class="form-control netamount numeric" invno="'+invno+'" name="netamount" value="'+netamount+'" readonly required>'+
  //                 '</td>' +                   

  //                 '<td class="total_posted" width="11%" style="padding:2px;">'+
  //                    '<input type="text" style="padding:2px;padding-right:6px;text-align:right;color:#9dfcad;" class="form-control totalposted numeric" invno="'+invno+'" name="totalposted" value="'+totalposted+'" readonly required>'+
  //                 '</td>' + 

  //                 '<td class="total_pending" width="11%" style="padding:2px;">'+
  //                    '<input type="text" style="padding:2px;padding-right:6px;text-align:right;color:#fcda9a;" class="form-control totalpending numeric" invno="'+invno+'" name="totalpending" value="'+totalpending+'" readonly required>'+
  //                 '</td>' + 

  //                 '<td class="total_balance" width="11%" style="padding:2px;">'+
  //                    '<input type="text" style="padding:2px;padding-right:6px;text-align:right;color:#fc90b1;" class="form-control totalbalance numeric" invno="'+invno+'" name="totalbalance" value="'+totalbalance+'" readonly required>'+
  //                 '</td>' +                                                                      

  //                 '<td class="payment" width="11%" style="padding:2px;">'+
  //                    '<input type="text" style="padding:2px;padding-right:6px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control payment numeric" invno="'+invno+'" name="payment" value="0.00">'+
  //                 '</td>' +                                
  //               '</tr>');
  //           }
  //         }

  //         var _amount_total = numberWithCommas(_amount.toFixed(2));
  //         $("#total-amount").val(_amount_total);

  //         var _adjustment_total = numberWithCommas(_adjustment.toFixed(2));
  //         $("#total-adjustment").val(_adjustment_total);

  //         var _netamount_total = numberWithCommas(_netamount.toFixed(2));
  //         $("#total-netamount").val(_netamount_total);

  //         var _posted_total = numberWithCommas(_posted.toFixed(2));
  //         $("#total-posted").val(_posted_total); 
          
  //         var _pending_total = numberWithCommas(_pending.toFixed(2));
  //         $("#total-pending").val(_pending_total); 

  //         var _balance_total = numberWithCommas(_balance.toFixed(2));
  //         $("#total-balance").val(_balance_total);                       
  //      }
  //   });

  //   $("#modal-search-receivable").modal('hide');

  //   //Initialize entry
  //   $("#sel-paymode").val('').trigger('change');
  //   $("#sel-checkdesc").val('').trigger('change');
  //   $("#tns-checknum").val('');
  //   $("#sel-bankcode").val('').trigger('change');
  //   $("#date-checkdate").val('');

  //   disableControls();

  //   //get Current Date
  //   let today = new Date().toLocaleDateString();
  //   $("#date-paydate").val(today);
  //   $("#date-posted").val(today);

  //   $("#date-paydate").prop('disabled', false);
  //   $("#sel-paymode").prop('disabled', false);

  //   checkEmptyEntry();
  // }

  function paintReceivableTable(customercode){
    var data = new FormData();
    data.append("customercode", customercode);
    $.ajax({
       url:"ajax/receivable_customer_list.ajax.php",
       method: "POST",
       data: data,
       cache: false,
       contentType: false,
       processData: false,
       dataType:"json",
       success:function(answer){
          $(".enlisted_receivable").empty();

          var _amount = 0.00;
          var _adjustment = 0.00;
          var _netamount = 0.00;
          var _posted = 0.00;
          var _pending = 0.00;
          var _balance = 0.00;          

          for(var p = 0; p < answer.length; p++) {
            var pay = answer[p];

            let sdate = pay.sdate.split("-");
            sdate = sdate[1] + "/" + sdate[2] + "/" + sdate[0];

            var invno = pay.invno;
            var receiptnum = pay.receiptnum;
            var salemode = pay.salemode;
            var totalamount = numberWithCommas(pay.netamount);
            var totaladjustment = '0.00';

            var inv_amount = Number(pay.amount);
            var amount = numberWithCommas(inv_amount.toFixed(2));

            var inv_discount = Number(pay.discount);
            var discount = numberWithCommas(inv_discount.toFixed(2));

            var net_amount = Number(pay.netamount);
            var netamount = numberWithCommas(net_amount.toFixed(2));

            var posted_amount = pay.posted_amount;
            if (posted_amount == '0.00'){
              var totalposted = '0.00';
            }else{
              var totalposted = numberWithCommas(posted_amount);
            }

            var pending_amount = pay.pending_amount;
            if (pending_amount == '0.00'){
              var totalpending = '0.00';
            }else{
              var totalpending = numberWithCommas(pending_amount);
            }

            // Calculate Balance of each Invoice #
            var tposted = Number(totalposted.replaceAll(",",""));
            var tpending = Number(totalpending.replaceAll(",",""));

            var balance = net_amount - (tposted + tpending);
            var totalbalance = numberWithCommas(balance.toFixed(2));
            _balance += balance;

            if (balance > 0.00){
              // allow only values to TOTALS to be included
              // if balance for individual DELNUMBER > 0.00
              // see Footer of Table
              _amount += Number(pay.amount);
              _adjustment += Number(pay.discount);
              _netamount += net_amount;
              _posted += Number(posted_amount);
              _pending += Number(pending_amount);

              // Since Balance > 0.00, add row to table
              $(".enlisted_receivable").append(
                '<tr>'+               
                  '<td class="del_date" width="11%" style="padding:2px;">'+
                     '<input type="text" style="padding:2px;padding-right:6px;text-align:center;" class="form-control sdate numeric" invno="'+invno+'" name="sdate" value="'+sdate+'" readonly required>'+
                  '</td>' +

                  '<td class="del_number" width="11%" style="padding:2px;">'+
                     '<button type="button" class="col-sm-10 btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon ml-2 btnCreditDetails" invno="'+invno+'">'+receiptnum+'</button>'+
                  '</td>' +  

                  '<td class="total_amount" width="11%" style="padding:2px;">'+
                     '<input type="text" style="padding:2px;padding-right:6px;text-align:right;" class="form-control totalamount numeric" invno="'+invno+'" name="totalamount" value="'+amount+'" readonly required>'+
                  '</td>' +  

                  '<td class="total_adjustment" width="11%" style="padding:2px;">'+
                     '<input type="text" style="padding:2px;padding-right:6px;text-align:right;" class="form-control discount numeric" invno="'+invno+'" name="discount" value="'+discount+'" readonly required>'+
                  '</td>' + 

                  '<td class="net_amount" width="11%" style="padding:2px;">'+
                     '<input type="text" style="padding:2px;padding-right:6px;text-align:right;color:#a2edfa;" class="form-control netamount numeric" invno="'+invno+'" name="netamount" value="'+netamount+'" readonly required>'+
                  '</td>' +                   

                  '<td class="total_posted" width="11%" style="padding:2px;">'+
                     '<input type="text" style="padding:2px;padding-right:6px;text-align:right;color:#9dfcad;" class="form-control totalposted numeric" invno="'+invno+'" name="totalposted" value="'+totalposted+'" readonly required>'+
                  '</td>' + 

                  '<td class="total_pending" width="11%" style="padding:2px;">'+
                     '<input type="text" style="padding:2px;padding-right:6px;text-align:right;color:#fcda9a;" class="form-control totalpending numeric" invno="'+invno+'" name="totalpending" value="'+totalpending+'" readonly required>'+
                  '</td>' + 

                  '<td class="total_balance" width="11%" style="padding:2px;">'+
                     '<input type="text" style="padding:2px;padding-right:6px;text-align:right;color:#fc90b1;" class="form-control totalbalance numeric" invno="'+invno+'" name="totalbalance" value="'+totalbalance+'" readonly required>'+
                  '</td>' +                                                                      

                  '<td class="payment" width="11%" style="padding:2px;">'+
                     '<input type="text" style="padding:2px;padding-right:6px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control payment numeric" invno="'+invno+'" name="payment" value="0.00">'+
                  '</td>' +                                
                '</tr>');
            }
          }

          var _amount_total = numberWithCommas(_amount.toFixed(2));
          $("#total-amount").val(_amount_total);

          var _adjustment_total = numberWithCommas(_adjustment.toFixed(2));
          $("#total-adjustment").val(_adjustment_total);

          var _netamount_total = numberWithCommas(_netamount.toFixed(2));
          $("#total-netamount").val(_netamount_total);

          var _posted_total = numberWithCommas(_posted.toFixed(2));
          $("#total-posted").val(_posted_total); 
          
          var _pending_total = numberWithCommas(_pending.toFixed(2));
          $("#total-pending").val(_pending_total); 

          var _balance_total = numberWithCommas(_balance.toFixed(2));
          $("#total-balance").val(_balance_total);                       
       }
    });

    $("#modal-search-receivable").modal('hide');

    //Initialize entry
    $("#sel-paymode").val('').trigger('change');
    $("#sel-checkdesc").val('').trigger('change');
    $("#tns-checknum").val('');
    $("#sel-bankcode").val('').trigger('change');
    $("#date-checkdate").val('');

    disableControls();

    //get Current Date
    let today = new Date().toLocaleDateString();
    $("#date-paydate").val(today);
    $("#date-posted").val(today);

    $("#date-paydate").prop('disabled', false);
    $("#sel-paymode").prop('disabled', false);

    checkEmptyEntry();
  }
});