$(function() {
   $('input[type="text"], textarea').css('border', '1px solid rgba(255, 255, 255, 0.3)');

   $('#txt-bankname').focus();  

   $("#btn-new").click(function(){
      $("#txt-bankname").val('');
      $("#txt-bankcode").val('');
      $("#tns-bankaddress").val('');
      $("#tns-bankcontact").val('');
      $("#num-banklandline").val('');
      $("#num-bankmobile").val('');
      $("#tns-bankwebsite").val('');

      $('#trans_type').val('New');
      $('#num-id').val('');
      $('#txt-bankname').focus();
   });

   $('#form-bank input[id^="num"]').on("keypress", function (e) {
      return _helper.isNumericDash(e) ? true : e.preventDefault();
   });

   // $('#form-bank input[id^="txt"]').on("keypress", function (e) {
   //    return _helper.isString(e) ? true : e.preventDefault();
   // });

   $('#form-bank input[id^="tns"]').on("keypress", function (e) {
      return _helper.allChars(e) ? true : e.preventDefault();
   });   

   $('.bankTable tbody').on('dblclick', 'tr', function () {
	  var idBank = $(this).attr("idBank");
	  var data = new FormData();
      data.append("idBank", idBank);
      $.ajax({
     	 url:"ajax/get_bank_record.ajax.php",
      	 method: "POST",
      	 data: data,
      	 cache: false,
      	 contentType: false,
      	 processData: false,
      	 dataType:"json",
      	 success:function(answer){
      	 	$("#num-id").val(answer["id"]);
            $("#txt-bankname").val(answer["bankname"]);
      	 	$("#txt-bankcode").val(answer["bankcode"]);
            $("#tns-bankaddress").val(answer["bankaddress"]);
            $("#tns-bankcontact").val(answer["bankcontact"]);
            $("#num-banklandline").val(answer["banklandline"]);
            $("#num-bankmobile").val(answer["bankmobile"]);
      	 	$("#tns-bankwebsite").val(answer["bankwebsite"]);

      	 	$("#trans_type").val("Update");
          	$("#modal-search-bank").modal('hide');
      	}
      })
   }); 
});
