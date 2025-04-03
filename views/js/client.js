// Adjust column automatically when modal form is fired
$('#modal-search-clients').on('shown.bs.modal', function () {
   var tableclients = $('.clientsTable').DataTable();
   tableclients.search('').draw();  // clear datatable filter textbox
   tableclients.table().container().querySelector('.dataTables_filter input').focus(); // set focus
   tableclients.columns.adjust();
});
    
$(function() {
   $('input[type="text"], textarea').css('border', '1px solid rgba(255, 255, 255, 0.3)');
   $('select').css('border', '1px solid rgba(255, 255, 255, 0.3) !important');

   $(".datepicker").datepicker();
   $(".datepicker").datepicker("option", "dateFormat", "mm/dd/yy"); 

   // select styling
   $(".select").select2({
      minimumResultsForSearch: Infinity,
   });

   // select with search
   $(".select-search").select2();

   $('#form-client input[id^="num"]').on("keypress", function (e) {
      return _helper.isNumericDash(e) ? true : e.preventDefault();
   });

   $('#form-client input[id^="txt"]').on("keypress", function (e) {
      return _helper.isString(e) ? true : e.preventDefault();
   });

   $('#form-client input[id^="tns"]').on("keypress", function (e) {
      return _helper.allChars(e) ? true : e.preventDefault();
   }); 

   $('.clientsTable tbody').on('dblclick', 'tr', function () {
      var idClient = $(this).attr("idClient");
      var data = new FormData();
      data.append("idClient", idClient);
      $.ajax({
         url:"ajax/get_client_record.ajax.php",
         method: "POST",
         data: data,
         cache: false,
         contentType: false,
         processData: false,
         dataType:"json",
         success:function(answer){
               $("#num-id").val(answer["id"]);
               $("#txt-clientid").val(answer["clientid"]);

               if (answer["isactive"] == '1'){
                  $("#chk-isactive").prop( "checked", true);
                  $("#chk-isactive").val('1');
               }else{
                  $("#chk-isactive").prop( "checked", false);
                  $("#chk-isactive").val('0');
               }
               
               $("#txt-lname").val(answer["lname"]);
               $("#tns-fname").val(answer["fname"]);
               $("#txt-mi").val(answer["mi"]);

               let bday = answer["bday"].split("-");
               bday = bday[1] + "/" + bday[2] + "/" + bday[0];
               if (bday == '00/00/0000'){    	
                  bday = '';
               }
               $("#date-bday").val(bday);
   
               $("#sel-gender").val(answer["gender"]).trigger('change');
               $("#tns-address").val(answer["address"]);
               $("#num-landline").val(answer["landline"]);
               $("#num-mobile").val(answer["mobile"]);
               $("#tns-email").val(answer["email"]);
               $("#tns-spouse").val(answer["spouse"]);

               $("#trans_type").val("Update");
               $("#modal-search-clients").modal('hide');
         }
      })
   }); 

   var clients_access = $("#clients-access").val();
   if (clients_access == 'ViewOnly'){
     $('select').prop('disabled', true);
     $('input[type="checkbox"]').prop('disabled', true);

     $('input[type="text"]').prop('readonly', true);
     $('textarea').prop('readonly', true);

     $('button').prop('disabled', true);

     $('#btn-search').prop('disabled', false);
   }
});
    