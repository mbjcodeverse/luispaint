$(function() {
    $(".select").select2({
        minimumResultsForSearch: Infinity,
    });

    $(".select-search").select2();

    $('#lst_date_range').data('daterangepicker').setStartDate(moment('2010-01-01'));
    $('#lst_date_range').data('daterangepicker').setEndDate(moment());

    $('#lst_date_range').daterangepicker({
        ranges:{
          'All'           : [moment('2010-01-01'), moment()],
          'Today'         : [moment(),moment()],
          'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
          'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
          'This Month'    : [moment().startOf('month'), moment().endOf('month')]
        }
    });

    $("#lbl-lst-date-range").click(function(){
        $('#lst_date_range').data('daterangepicker').setStartDate(moment('2010-01-01'));
        $('#lst_date_range').data('daterangepicker').setEndDate(moment());
    });

    $("#lbl-lst-customercode").click(function(){
        $("#lst-customercode").val('').trigger('change');
    });

    $("#lbl-lst-salemode").click(function(){
        $("#lst-salemode").val('').trigger('change');
    }); 

    $('#lst-reptype, #lst-customercode, #lst-salemode, #lst_date_range').on("change", function() {
        $("#btn-print-report").prop('disabled', false);
        $("#btn-export").prop('disabled', false);

        let reptype = $("#lst-reptype").val();
        let customercode = $("#lst-customercode").val();
        if (customercode == null){
            customercode = '';
        }
        let salemode = $("#lst-salemode").val();
        // let status = $("#lst-status").val();
        let status = 'Sold';
        
        var date_range = $("#lst_date_range").val();
        if (date_range != ''){
            var start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
            var end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
        } else {
            var start_date = '';
            var end_date = '';
        }
        // let paystatus = $("#lst-paystatus").val();
        let paystatus = '<All>';

        // alert(customercode + ' ' + salemode + ' ' + start_date + ' ' + end_date + ' ' + status + ' ' + paystatus);
        
        var data = new FormData();
        data.append("reptype", reptype);
        data.append("customercode", customercode);
        data.append("salemode", salemode);
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        data.append("status", status);
        data.append("paystatus", paystatus);
        
        $.ajax({
            url: "ajax/salesreport_list.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(answer) {
                $(".invoice_content").empty();
                var html = [];
                if (reptype == 1){
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                      html.push('<table class="table mx-auto w-auto">');
                        html.push('<thead>');
                          html.push('<tr>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Date</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;min-width:330px;">Customer</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Receipt #</th>');
                            html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Sale Mode</th>');
                            html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                          html.push('</tr>');
                        html.push('</thead>');

                        var total_amount = 0.00;
                        for(var i = 0; i < answer.length; i++) {
                            var si = answer[i];
    
                            var invno = si.invno;
                            var name = si.name;
                            var receiptnum = si.receiptnum;
                            var salemode = si.salemode;
                            var sale_date = si.sdate;
                            var saledate = sale_date.split("-");
                            var sdate = saledate[1] + "/" + saledate[2] + "/" + saledate[0];
            
                            var netamount = numberWithCommas(si.netamount);
            
                            total_amount += parseFloat(si.netamount);

                            html.push('<tr>');
                               html.push('<td style="text-align:left;">'+sdate+'</td>');
                               html.push('<td>'+name+'</td>');
                               html.push('<td style="text-align:left;">'+receiptnum+'</td>');
                               html.push('<td style="text-align:left;">'+salemode+'</td>');
                               html.push('<td style="text-align:right;border-left:1px solid white;">'+netamount+'</td>');
                            html.push('</tr>');
                        }

                        if (i > 0){
                            html.push('<tr>');
                              html.push('<td colspan="4" style="text-align:right;border-top:2px solid white;font-size:1.3em;font-weight:bold;border-right:2px solid white;border-bottom:2px solid white">TOTAL AMOUNT</td>');
                              html.push('<td style="text-align:right;border-top:2px solid white;font-size:1.3em;font-weight:bold;border-bottom:2px solid white">'+numberWithCommas(total_amount)+'</td>');
                            html.push('</tr>');    
                          }                    
  
                        html.push('</table>');
                    html.push('</div>');
                }
                $('.invoice_content').html(html.join('')); 
            }
        });
    });

    $("#btn-export").click(function(){
        exportToExcel();
    });
    
    $("#btn-print-report").click(function(){
        let reptype = $("#lst-reptype").val();
        let customercode = $("#lst-customercode").val();
        if (customercode == null){
            customercode = '';
        }
        let salemode = $("#lst-salemode").val();
        let status = 'Sold';
        
        var date_range = $("#lst_date_range").val();
        if (date_range != ''){
            var start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
            var end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
        } else {
            var start_date = '';
            var end_date = '';
        }
        let generatedby = $("#tns-generatedby").val();
        
        window.open("extensions/tcpdf/pdf/salesprint.php?reptype="+reptype+"&customercode="+customercode+"&salemode="+salemode+"&status="+status+"&start_date="+start_date+"&end_date="+end_date+"&generatedby="+generatedby, "_blank");
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