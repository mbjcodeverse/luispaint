if (!$.fn.DataTable.isDataTable('.salesListTable')) {
    var slst = $('.salesListTable').DataTable({
        deferRender: true,
        processing: true,
        autoWidth: true,
        scrollY: 360,
        pageLength: 25,
        lengthMenu: [[25, 50], [25, 50]],
        dom: '<"datatable-header"><"extra-row"> <"datatable-scroll"t><"datatable-footer"fp>',
        language: {
            loadingRecords: 'Please wait - loading...',
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',
            search: '<span>Filter:</span> _INPUT_',
            searchPlaceholder: 'Type to filter...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 
                'first': 'First', 
                'last': 'Last', 
                'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 
                'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' 
            }
        },
        columnDefs: [
            { targets: [3, 4, 5], className: 'text-right' } // Adjust column indices as needed
        ]
    });
}


$(function() {
    $('input[type="text"], textarea').css('border', '1px solid rgba(255, 255, 255, 0.3)');

    $(".select").select2({
        minimumResultsForSearch: Infinity,
    });

    // Tabbing Sequence
    var tabSequence = [
        '#sel-customercode', 
        '#date-sdate', 
        '#txt-status', 
        '#txt-invno', 
        '#txt-receiptnum', 
        '#sel-salemode', 
        '#num-amount', 
        '#num-discount',
        '#txt-remarks', 
        '#btn-new', 
        '#btn-search', 
        '#btn-save'
    ];

    // Function to handle tabbing behavior
    $('input, select, button').on('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();  // Prevent the default tab action
            var currentIndex = tabSequence.indexOf('#' + this.id);
            var nextIndex = (currentIndex + 1) % tabSequence.length;  // Loop back to the start if at the end
            $(tabSequence[nextIndex]).focus();
        }
    });

    // Change the border color on focus and reset it on blur
    $('input, select, button').on('focus', function() {
        $(this).css('border-color', '#007bff');    // Change border color on focus
    }).on('blur', function() {
        $(this).css('border-color', '#FFFFFF4D');  // Reset border color when focus is lost
    });

    // End Tabbing -------------------------------------

    initialize();
  
    $(".select-search").select2();

    $("#btn-new").click(function(){
        new_sale();
    });

    $('#btn-save').click(function(){
        var emptyFields = [];

        if ($('#sel-customercode').val() == null) {
            emptyFields.push('Customer');
        }
        if ($('#date-sdate').val().trim() === '') {
            emptyFields.push('Date');
        }
        if ($('#txt-receiptnum').val().trim() === '') {
            emptyFields.push('Receipt #');
        }
        if ($('#sel-salemode').val() == '') {
            emptyFields.push('Sale Mode');
        }
        if ($('#num-amount').val().trim() === '0.00') {
            emptyFields.push('Amount');
        }
    
        if (emptyFields.length > 0) {
            var message = 'Fields are empty : ' + emptyFields.join(', ');
            swal.fire({
                title: 'Please fill in all required fields',
                text: message,
                type: 'error',
                confirmButtonText: 'Got it!',
                confirmButtonClass: 'btn btn-outline-danger',
                allowOutsideClick: false,
                buttonsStyling: false
            });
        } else {
            swal.fire({
                title: 'Do you want to post sales transaction?',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, save it!',
                cancelButtonText: 'No',
                confirmButtonClass: 'btn btn-outline-success',
                cancelButtonClass: 'btn btn-outline-danger',
                allowOutsideClick: false,
                buttonsStyling: false
            }).then(function(result) {
                 if(result.value) {  
                   postsales();
                 }
            }); 	
        }
    });

    $("#btn-void").click(function(){
        swal.fire({
            title: 'Do you want to VOID sales invoice?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Void!',
            cancelButtonText: 'No',
            confirmButtonClass: 'btn btn-outline-success',
            cancelButtonClass: 'btn btn-outline-danger',
            allowOutsideClick: false,
            buttonsStyling: false
        }).then(function(result) {
            if(result.value) {  
                var invno = $("#txt-invno").val();
                var void_invno = new FormData();
                void_invno.append("invno", invno);
                $.ajax({
                    url:"ajax/sales_void.ajax.php",
                    method: "POST",
                    data: void_invno,
                    cache: false,
                    contentType: false,
                    processData: false,
                    async: false,
                    dataType:"text",
                    success:function(answer){
                        if (answer && answer.trim() !== ''){
                            swal.fire({
                                title: 'Sales invoice successfully VOIDED!.',
                                type: 'success',
                                confirmButtonText: 'Got it!',
                                confirmButtonClass: 'btn btn-outline-success',
                                allowOutsideClick: false,
                                buttonsStyling: false
                            }).then(function(result){
                                if(result.value) {              
                                    initialize();
                                }
                            })
                        } 
                    }
                });    
            }
        });        
    });

    $('#txt-receiptnum').on('blur', function () {
        var receiptnum = $(this).val().trim();
        // alert(receiptnum);
        var current_receipt = $("#current_receipt").val();
        if (receiptnum !== '') {
            var rec = new FormData();
            rec.append("receiptnum", receiptnum);
            $.ajax({
                url:"ajax/sale_check_receiptnum.ajax.php",
                method: "POST",
                data: rec,
                cache: false,
                contentType: false,
                processData: false,
                async: false,
                dataType:"text",
                success:function(answer){
                    if (answer && answer.trim() !== '' && receiptnum != current_receipt) {
                        swal.fire({
                            title: 'Duplicate receipt # found! Change your entry.',
                            type: 'warning',
                            confirmButtonText: 'Got it!',
                            confirmButtonClass: 'btn btn-outline-success',
                            allowOutsideClick: false,
                            buttonsStyling: false
                        }).then(function(result){
                            if(result.value) {              
                                $('#txt-receiptnum').val('').focus();
                            }
                        })
                    }   
                }
            });    
        }
    });

    function new_sale(){
        swal.fire({
            title: 'Do you want to post new sales transaction?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Create!',
            cancelButtonText: 'No',
            confirmButtonClass: 'btn btn-outline-success',
            cancelButtonClass: 'btn btn-outline-danger',
            allowOutsideClick: false,
            buttonsStyling: false
        }).then(function(result) {
            if(result.value) {  
                initialize();
            }
        }); 	
    }   

    function initialize(){
        $("#btn-save").prop('disabled', false);

        if (!$('#chk-lockclient').is(':checked')) {
            $("#sel-customercode").val('').trigger('change');
        }

        if (!$('#chk-lockdate').is(':checked')) {
            $("#date-sdate").val('');
        }

        if (!$('#chk-locksalemode').is(':checked')) {
            $("#sel-salemode").val('').trigger('change');
        }

        // $("#sel-customercode").val('').trigger('change');
        // $("#date-sdate").val('');
        $("#txt-status").val('Sold');
        $("#txt-invno").val('');
        $("#txt-receiptnum").val('');
        $("#num-amount").val('0.00');
        $("#num-discount").val('0.00');
        $("#num-netamount").val('0.00');
        $("#txt-remarks").val('');

        $("#trans_type").val("New");
        $("#current_receipt").val("");
        // $('#btn-void').prop('disabled', true);
        $('#btn-void').hide();
    }

    function calculateNetAmount() {
        let amount = parseFloat($('#num-amount').val().replace(/,/g, '')) || 0;
        let discount = parseFloat($('#num-discount').val().replace(/,/g, '')) || 0;

        if ((discount >= amount && amount > 0.00)||(amount == 0.00 && discount > 0.00)) {
            swal.fire({
                title: 'Discount must not be equal or greater than invoice amount!.',
                type: 'warning',
                confirmButtonText: 'Got it!',
                confirmButtonClass: 'btn btn-outline-warning',
                allowOutsideClick: false,
                buttonsStyling: false
            }).then(function(result) {
                if(result.value) {  
                    $('#num-discount').val("0.00");
                    $('#num-netamount').val(numberWithCommas(amount.toFixed(2)));
                    return;
                }
            });
        }

        let netAmount = amount - discount;
        $('#num-netamount').val(numberWithCommas(netAmount.toFixed(2)));
    }

    $('#num-amount, #num-discount').on('change keyup', function() {
        calculateNetAmount();
    });

    function postsales(){
        $("#btn-save").prop('disabled', true);       
        let trans_type = $("#trans_type").val();
        let postedby = $("#txt-postedby").val();

        let customercode = $("#sel-customercode").val();

        let format_sdate = $("#date-sdate").val().split("/");
        format_sdate = format_sdate[2] + "-" + format_sdate[0] + "-" + format_sdate[1];
        let sdate = format_sdate;

        let status = $("#txt-status").val();
        let invno = $("#txt-invno").val();
        let receiptnum = $("#txt-receiptnum").val();
        let salemode = $("#sel-salemode").val();

        var txt_amount = $("#num-amount").val();
        let amount= parseFloat(txt_amount.replaceAll(",",""));

        var txt_discount = $("#num-discount").val();
        let discount= parseFloat(txt_discount.replaceAll(",",""));

        var txt_netamount = $("#num-netamount").val();
        let netamount= parseFloat(txt_netamount.replaceAll(",",""));

        let remarks = $("#txt-remarks").val();
        //alert(trans_type + ' ' + postedby + ' ' + customercode + ' ' + sdate + ' ' + status + ' ' + invno + ' ' + salemode + ' ' + netamount + ' ' + remarks);
        var sales = new FormData();
        sales.append("trans_type", trans_type);
        sales.append("invno", invno);
        sales.append("receiptnum", receiptnum);
        sales.append("sdate", sdate);
        sales.append("salemode", salemode);
        sales.append("customercode", customercode);
        sales.append("status", status);
        sales.append("postedby", postedby);
        sales.append("amount", amount);
        sales.append("discount", discount);
        sales.append("netamount", netamount);
        sales.append("remarks", remarks);
        $.ajax({
            url:"ajax/sale_save_record.ajax.php",
            method: "POST",
            data: sales,
            cache: false,
            contentType: false,
            processData: false,
            async: false,
            dataType:"text",
            success:function(answer){
                let invno = answer;                                
                $("#txt-invno").val(invno);
                swal.fire({
                    title: 'Sales transaction successfully saved!',
                    type: 'success',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function () {
                alert("Oops. Something went wrong!");
            },
            complete: function () {
                initialize();
            }
        });    
    }

    function formatDateOnBlur(inputSelector) {
        $(document).on('blur', inputSelector, function () {
            var dateValue = $(this).val().trim();
            
            // If the input is empty, do not consider it an error and exit the function early
            if (dateValue === "") {
                return;
            }
        
            // Restrict input to numbers and slashes only
            dateValue = dateValue.replace(/[^0-9\/]/g, '');
        
            // Check if the date is in the MM/DD/YYYY format or not
            var datePattern = /^(\d{1,2})\/(\d{1,2})\/(\d{2,4})$/;
            var match = dateValue.match(datePattern);
        
            var errorMessages = [];
        
            if (match) {
                var month = match[1].padStart(2, '0');   // Ensure month is two digits
                var day = match[2].padStart(2, '0');     // Ensure day is two digits
                var year = match[3];  // Don't modify the year, leave it as is
        
                // Validate the year
                if (year.length !== 4) {
                    errorMessages.push('Please enter a valid 4-digit year.');
                }
        
                // Validate the month
                if (month < 1 || month > 12) {
                    errorMessages.push('Please enter a valid month (01-12).');
                }
        
                // Validate the days in the month (considering leap years for February)
                var daysInMonth = {
                    '01': 31, '02': (year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0)) ? 29 : 28, 
                    '03': 31, '04': 30, '05': 31, '06': 30, '07': 31, '08': 31, '09': 30, 
                    '10': 31, '11': 30, '12': 31
                };
        
                // Validate the day for the given month
                if (day < 1 || day > daysInMonth[month]) {
                    errorMessages.push('Please enter a valid day for the given month.');
                }
        
                // If there are any error messages, display them in a single alert
                if (errorMessages.length > 0) {
                    alert(errorMessages.join('\n'));
                    $(this).val('');  // Clear the input field after alert
                    $(this).focus();  // Focus back on the input field after clearing
                    return;
                }
        
                // If everything is valid, format the date and update the input field
                var correctedDate = month + '/' + day + '/' + year;
                $(this).val(correctedDate);
            } else {
                // If the input does not match the MM/DD/YYYY format
                alert('Please enter the date in MM/DD/YYYY format.');
                $(this).val('');  // Clear the input field after alert
                $(this).focus();  // Focus back on the input field after clearing
            }
        });
    }
    
    formatDateOnBlur('#date-sdate');

    // --------------------------------------------
    $('#lst_date_range').daterangepicker({
        ranges:{
          'All'           : [moment('2010-01-01'), moment()],
          'Today'         : [moment(),moment()],
          'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
          'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
          'This Month'    : [moment().startOf('month'), moment().endOf('month')],
          'This Year'     : [moment().startOf('year'), moment().endOf('year')]
        }
    });

    $('#modal-search-sales').on('shown.bs.modal', function () {
        slst.search('').draw();
        slst.table().container().querySelector('.dataTables_filter input').focus(); 
        $("#lst-customercode").val('').trigger('change');
        $("#lst-salemode").val('').trigger('change');
        $("#lst-status").val('Sold').trigger('change');
        $("#lst-paystatus").val('<All>').trigger('change');

        $('#lst_date_range').data('daterangepicker').setStartDate(moment('2010-01-01'));
        $('#lst_date_range').data('daterangepicker').setEndDate(moment());
    });

    $("#lbl-lst-date-range").click(function(){
        $('#lst_date_range').data('daterangepicker').setStartDate(moment('2010-01-01'));
        $('#lst_date_range').data('daterangepicker').setEndDate(moment());
        
        slst.search('').draw();
        slst.table().container().querySelector('.dataTables_filter input').focus(); 
    });

    $("#lbl-lst-customercode").click(function(){
        $("#lst-customercode").val('').trigger('change');
    });

    $("#lbl-lst-salemode").click(function(){
        $("#lst-salemode").val('').trigger('change');
    });  

    $("#lbl-lst-status").click(function(){
        $("#lst-status").val('').trigger('change');
    });  

    $("#lbl-lst-paystatus").click(function(){
        $("#lst-paystatus").val('<All>').trigger('change');
    }); 
    
    $('#lst-customercode, #lst-salemode, #lst_date_range, #lst-status, #lst-paystatus').on("change", function() {
        let customercode = $("#lst-customercode").val();
        if (customercode == null){
            customercode = '';
        }
        let salemode = $("#lst-salemode").val();
        let status = $("#lst-status").val();
        
        var date_range = $("#lst_date_range").val();
        if (date_range != ''){
            var start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
            var end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
        } else {
            var start_date = '';
            var end_date = '';
        }
        let paystatus = $("#lst-paystatus").val();
        
        var data = new FormData();
        data.append("customercode", customercode);
        data.append("salemode", salemode);
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        data.append("status", status);
        data.append("paystatus", paystatus);
        
        $.ajax({
            url: "ajax/sales_list.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(answer) {
                $(".salesListTable").DataTable().clear(); // Clear previous table data
                var total_amount = 0.00;
                var total_paid = 0.00;
                var total_balance = 0.00;
    
                // Loop through the data and populate the table
                for (var i = 0; i < answer.length; i++) {
                    var si = answer[i];
    
                    var invno = si.invno;
                    var name = si.name;
                    var receiptnum = si.receiptnum;
                    var sale_date = si.sdate;
                    var saledate = sale_date.split("-");
                    var sdate = saledate[1] + "/" + saledate[2] + "/" + saledate[0];
    
                    var netamount = numberWithCommas(si.netamount);
                    var paid = numberWithCommas(si.paid);
                    var balance = numberWithCommas(si.balance);
    
                    total_amount += parseFloat(si.netamount);
                    total_paid += parseFloat(si.paid);
                    total_balance += parseFloat(si.balance);
    
                    var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnSale' invno='" + invno + "'><i class='icon-pencil3'></i></button></td>";
                    slst.row.add([sdate, receiptnum, name, netamount, paid, balance, button])
                }
                // $('#num-totalamount').val(numberWithCommas(total_amount.toFixed(2)));
                // slst.row.add(['', '', 'TOTAL AMOUNT', numberWithCommas(total_amount), numberWithCommas(total_paid), numberWithCommas(total_balance), '']);
                slst.draw();
            },
            beforeSend: function() {},
            complete: function() {
                // Apply padding styles after DataTable is drawn - adjust row height of data table
                $(".salesListTable td").css({
                    "padding-top": "5px",
                    "padding-bottom": "5px"
                });
            }
        });
    });

    // $("#btn-print").click(function(){
    //     let customercode = $("#lst-customercode").val();
    //     if (customercode == null){
    //         customercode = '';
    //     }
    //     let salemode = $("#lst-salemode").val();
    //     let status = $("#lst-status").val();
        
    //     var date_range = $("#lst_date_range").val();
    //     if (date_range != ''){
    //         var start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    //         var end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
    //     } else {
    //         var start_date = '';
    //         var end_date = '';
    //     }
    //     // let paystatus = $("#lst-paystatus").val();
    //     alert(customercode);
        
    //     window.open("extensions/tcpdf/pdf/salesprint.php?customercode="+customercode+"&salemode="+salemode+"&status="+status+"&start_date="+start_date+"&end_date="+end_date, "_blank");
    // }); 
    
    // Ensure that padding is applied whenever DataTable redraws (e.g., page switch or filtering)
    $(".salesListTable").on("draw.dt", function () {
        $(".salesListTable td").css({
            "padding-top": "5px",
            "padding-bottom": "5px"
        });
    });

    $(".salesListTable tbody").on("click", "button.btnSale", function(){
        $("#modal-search-sales").modal("hide");
        $("#trans_type").val("Update");
        var invno = $(this).attr("invno");
        var data = new FormData();
        data.append("invno", invno);
        $.ajax({
            url:"ajax/sale_get_record.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                $('#trans_type').val('Update');

                $("#sel-customercode").val(answer["customercode"]).trigger('change');

                let sale_date = answer["sdate"];
                let saledate = sale_date.split("-");
                saledate = saledate[1] + "/" + saledate[2] + "/" + saledate[0];
                $("#date-sdate").val(saledate);

                $("#txt-status").val(answer["status"]);
                $("#txt-invno").val(answer["invno"]);
                $("#txt-receiptnum").val(answer["receiptnum"]);
                $("#sel-salemode").val(answer["salemode"]).trigger('change');
                $("#num-amount").val(numberWithCommas(answer["amount"]));
                $("#num-discount").val(numberWithCommas(answer["discount"]));
                $("#num-netamount").val(numberWithCommas(answer["netamount"]));
                $("#txt-remarks").val(answer["remarks"]);

                // $('#btn-void').prop('disabled', false);
                $("#current_receipt").val(answer["receiptnum"]);
                $('#btn-void').show();
            }
        })
    }); 
});    