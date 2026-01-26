$(function() {
   $('input[type="text"], textarea').css('border', '1px solid rgba(255, 255, 255, 0.3)');
   
   $('#tns-tin').mask('000-000-000-000');

   $(".select").select2({
      minimumResultsForSearch: Infinity,
   });

   $(".select-search").select2();

   $('#form-customer input[id^="num"]').on("keypress", function (e) {
      return _helper.isNumericDash(e) ? true : e.preventDefault();
   });

   $('#form-customer input[id^="txt"]').on("keypress", function (e) {
      return _helper.isString(e) ? true : e.preventDefault();
   });

   $('#form-customer input[id^="tns"]').on("keypress", function (e) {
      return _helper.allChars(e) ? true : e.preventDefault();
   });   

   $("#sel-country").val("PH").trigger('change');
   $('#tns-name').focus();

   $("#btn-new").click(function(){
     $('#txt-customercode').val('');
     $('#tns-name').val('');
     $('#tns-description').val('');
     $('#num-mobile').val('');
     $('#num-landline').val(''); 
     $('#num-faxnum').val('');  
     $('#tns-website').val(''); 
     $('#tns-contactperson').val('');
     $("#sel-country").val("PH").trigger('change');
     $("#chk-isactive").prop( "checked", true); 
     $('#tns-address').val(''); 
     $('#tns-tin').val(''); 
     $('#num-creditlimit').val('0.00');

     $('#trans_type').val('New');
     $('#num-id').val('');
     $('#tns-name').focus();
   });  
   
   // If the dblclick event is fired rapidly in succession
   // (due to accidental double clicks or quick clicks),
   // it could trigger multiple AJAX requests unnecessarily.
   // You can debounce or throttle the event handler to avoid multiple requests.
   $('.customerTable tbody').on('dblclick', 'tr', _.debounce(function () {
      var idCustomer = $(this).attr("idCustomer");
      var data = new FormData();
      data.append("idCustomer", idCustomer);
      $.ajax({
          url: "ajax/get_client_record.ajax.php",
          method: "POST",
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          dataType: "json",
          success: function(answer) {
            // Minimize the number of jQuery DOM manipulations by grouping them together.
            // Each time you access the DOM (e.g., $("#num-id")), the browser has to query
            // the DOM, which can be slow if done repeatedly.
            // You can group updates to the DOM and apply them all at once:
            var data = {
               "#num-id": answer["id"],
               "#txt-customercode": answer["customercode"],
               "#tns-name": answer["name"],
               "#tns-description": answer["description"],
               "#num-mobile": answer["mobile"],
               "#num-landline": answer["landline"],
               "#num-faxnum": answer["faxnum"],
               "#tns-website": answer["website"],
               "#tns-contactperson": answer["contactperson"],
               "#tns-address": answer["address"],
               "#tns-tin": answer["tin"],
               "#num-creditlimit": numberWithCommas(answer["creditlimit"])
            };
   
            for (var selector in data) {
               $(selector).val(data[selector]);
            }
   
            if (answer["isactive"] === 1) {
               $("#chk-isactive").prop("checked", true).val('1');
            } else {
               $("#chk-isactive").prop("checked", false).val('0');
            }
   
            $("#sel-country").val(answer["country"]).trigger('change');
            $("#trans_type").val("Update");
            $("#modal-search-customer").modal('hide');
         }
      });
   }, 300));  // 300ms delay

   // $('.customerTable tbody').on('dblclick', 'tr', function () {
   //  var idCustomer = $(this).attr("idCustomer");
   //  var data = new FormData();
   //    data.append("idCustomer", idCustomer);
   //    $.ajax({
   //     url:"ajax/get_client_record.ajax.php",
   //       method: "POST",
   //       data: data,
   //       cache: false,
   //       contentType: false,
   //       processData: false,
   //       dataType:"json",
   //       success:function(answer){
   //        $("#num-id").val(answer["id"]);
   //        $("#txt-customercode").val(answer["customercode"]);

   //        if (answer["isactive"] == '1'){
   //          $("#chk-isactive").prop( "checked", true);
   //          $("#chk-isactive").val('1');
   //        }else{
   //          $("#chk-isactive").prop( "checked", false);
   //          $("#chk-isactive").val('0');
   //        }
          
   //        $("#tns-name").val(answer["name"]);
   //        $("#tns-description").val(answer["description"]);
   //        $("#num-mobile").val(answer["mobile"]);
   //        $("#num-landline").val(answer["landline"]);
   //        $("#num-faxnum").val(answer["faxnum"]);
   //        $("#tns-website").val(answer["website"]);
   //        $("#tns-contactperson").val(answer["contactperson"]);
   //        $("#sel-country").val(answer["country"]).trigger('change');
   //        $("#tns-address").val(answer["address"]);
   //        $("#tns-tin").val(answer["tin"]);
   //        $("#num-creditlimit").val(numberWithCommas(answer["creditlimit"]));

   //        $("#trans_type").val("Update");
   //        $("#modal-search-customer").modal('hide');
   //      }
   //    })
   // }); 
   $("#btn-print").click(function(){      
      window.open("extensions/tcpdf/pdf/clientlist.php", "_blank");
   });
});
