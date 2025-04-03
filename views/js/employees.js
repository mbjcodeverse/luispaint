// $('.employeesTable tbody tr').dblclick(function(){
//   var idEmployee = $(this).attr("idEmployee");
//   window.location = "index.php?route=employeeupdate&idEmployee="+idEmployee;
// });


// Adjust column automatically when modal form is fired
$('#modal-search-employees').on('shown.bs.modal', function () {
   var tableEmployees = $('.employeesTable').DataTable();
   tableEmployees.columns.adjust();
});

$(function() {
   $('input[type="text"], textarea').css('border', '1px solid rgba(255, 255, 255, 0.3)');

   $(".datepicker").datepicker();
   $(".datepicker").datepicker("option", "dateFormat", "mm/dd/yy"); 

   // select styling
   $(".select").select2({
      minimumResultsForSearch: Infinity,
   });

   // select with search
   $(".select-search").select2();

   $('#form-employee input[id^="num"]').on("keypress", function (e) {
      return _helper.isNumericDash(e) ? true : e.preventDefault();
   });

   $('#form-employee input[id^="txt"]').on("keypress", function (e) {
      return _helper.isString(e) ? true : e.preventDefault();
   });

   $('#form-employee input[id^="tns"]').on("keypress", function (e) {
      return _helper.allChars(e) ? true : e.preventDefault();
   }); 

   $('.employeesTable tbody').on('dblclick', 'tr', function () {
   // $('.employeesTable tbody tr').dblclick(function(){
	  var idEmployee = $(this).attr("idEmployee");
	  // window.location = "index.php?route=employeeadd";
	  // window.location = "index.php?route=employeeupdate&idEmployee="+idEmployee;
	  var data = new FormData();
      data.append("idEmployee", idEmployee);
      $.ajax({
     	 url:"ajax/get_employee_record.ajax.php",
      	 method: "POST",
      	 data: data,
      	 cache: false,
      	 contentType: false,
      	 processData: false,
      	 dataType:"json",
      	 success:function(answer){
      	 	$("#num-id").val(answer["id"]);
      	 	$("#txt-empid").val(answer["empid"]);

      	 	// $("#chk-isactive").val(answer["isactive"]).trigger('change');
      	 	if (answer["isactive"] == '1'){
      	 		$("#chk-isactive").prop( "checked", true);
      	 		$("#chk-isactive").val('1');
      	 	}else{
      	 		$("#chk-isactive").prop( "checked", false);
      	 		$("#chk-isactive").val('0');
      	 	}

            // (answer["isactive"] == '1') ?  $("#chk-isactive").attr('checked', 'checked') : '';
      	 	
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
            $("#num-mobile").val(answer["mobile"]);
      	 	$("#sel-position").val(answer["idPos"]).trigger('change');
      	 	$("#num-sssno").val(answer["sssno"]);
      	 	$("#num-phino").val(answer["phino"]);
      	 	$("#num-pagibig").val(answer["pagibig"]);
      	 	$("#num-tin").val(answer["tin"]);
      	 	$("#sel-estatus").val(answer["estatus"]).trigger('change');

      	 	$("#trans_type").val("Update");
          	$("#modal-search-employees").modal('hide');
      	}
      })
   });
   
   var employees_access = $("#employees-access").val();
   if (employees_access == 'ViewOnly'){
     $('select').prop('disabled', true);
     $('input[type="checkbox"]').prop('disabled', true);

     $('input[type="text"]').prop('readonly', true);
     $('textarea').prop('readonly', true);

     $('button').prop('disabled', true);

     $('#btn-search').prop('disabled', false);
   }
});
