$('#modal-search-users').on('shown.bs.modal', function () {
   var tableUsers = $('.userRightsTable').DataTable();
   tableUsers.columns.adjust();
});


$(function() {
    $('input[type="text"], textarea').css('border', '1px solid rgba(255, 255, 255, 0.3)');

    // select styling
   $(".select").select2({
    minimumResultsForSearch: Infinity,
 });

 // select with search
 $(".select-search").select2();


    $("#txt-username").val(generateTempUsername());
    $("#txt-upassword").val(generateTempPassword());

    $("#btn-new").click(function(){
        new_user_rights();
    }); 

    $("#btn-save").click(function(){
        save_user_rights();
    });

    $('.userRightsTable tbody').on('dblclick', 'tr', function () {
        var userid = $(this).attr("userId");
        var data = new FormData();
        data.append("userid", userid);
        $.ajax({
            url:"ajax/get_user_rights_record.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                $("#txt-userid").val(answer["userid"]);
                $("#sel-empid").val(answer["empid"]).trigger('change');
                $("#sel-invoices").val(answer["invoices"]).trigger('change');
                $("#sel-receivable").val(answer["receivable"]).trigger('change');
                $("#sel-reports").val(answer["reports"]).trigger('change');
                $("#sel-dashboard").val(answer["dashboard"]).trigger('change');
                $("#sel-clients").val(answer["clients"]).trigger('change');
                $("#sel-employees").val(answer["employees"]).trigger('change');
                $("#sel-branch").val(answer["branch"]).trigger('change');
                $("#sel-accessprivilege").val(answer["accessprivilege"]).trigger('change');

                $("#trans_type").val("Update");
                $("#modal-search-users").modal('hide');
            }
        })
    });     

    function new_user_rights(){
        swal.fire({
           title: 'Do you want to create new user access rights?',
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
        $('#trans_type').val('New');
        $("#txt-userid").val('');

        $("#txt-username").val(generateTempUsername());
        $("#txt-upassword").val(generateTempPassword());

        $("#sel-empid").val('').trigger('change');
        $("#sel-invoices").val('').trigger('change');
        $("#sel-receivable").val('').trigger('change');
        $("#sel-reports").val('').trigger('change');
        $("#sel-dashboard").val('').trigger('change');
        $("#sel-clients").val('').trigger('change');
        $("#sel-employees").val('').trigger('change');
        $("#sel-branch").val('').trigger('change');
        $("#sel-accessprivilege").val('').trigger('change');
    }

    function save_user_rights(){
        var empty_value = false;
        $('select').each(function() {
            if ($(this).val() === '') {
               empty_value = true;
            }
        });

        if (empty_value === false){
            swal.fire({
                title: 'Do you want to save user access rights?',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, save!',
                cancelButtonText: 'No',
                confirmButtonClass: 'btn btn-outline-success',
                cancelButtonClass: 'btn btn-outline-danger',
                allowOutsideClick: false,
                buttonsStyling: false
            }).then(function(result) {
                if(result.value) {  
                    post_user_rights();
                }
            });
        }else{
            swal.fire({
                title: 'Incomplete data entry!',
                type: 'error',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 1500
            })
        } 	
    } 

    function post_user_rights(){
        $("#btn-save").prop('disabled', true);  
        var trans_type = $("#trans_type").val(); 

        var userid = $("#txt-userid").val();
        var username = $("#txt-username").val();
        var upassword = $("#txt-upassword").val();

        var empid = $("#sel-empid").val();
        var invoices = $("#sel-invoices").val();
        var receivable = $("#sel-receivable").val();
        var reports = $("#sel-reports").val();
        var dashboard = $("#sel-dashboard").val();
        var clients = $("#sel-clients").val();
        var employees = $("#sel-employees").val();
        var branch = $("#sel-branch").val();
        var accessprivilege = $("#sel-accessprivilege").val();
              
        var user = new FormData();
        user.append("trans_type", trans_type);
        user.append("userid", userid);
        user.append("empid", empid);
        user.append("invoices", invoices);
        user.append("receivable", receivable);
        user.append("reports", reports);
        user.append("dashboard", dashboard);
        user.append("clients", clients);
        user.append("employees", employees);   
        user.append("branch", branch); 
        user.append("accessprivilege", accessprivilege);   
        user.append("username", username);
        user.append("upassword", upassword);    
        $.ajax({
           url:"ajax/user_rights_save_record.ajax.php",
           method: "POST",
           data: user,
           cache: false,
           contentType: false,
           processData: false,
           async: false,
           dataType:"text",
           success:function(answer){
              $("#btn-save").prop('disabled', false);                        
           },
           error: function () {
              alert("Oops. Something went wrong!");
           },
           complete: function () {
              if (trans_type == 'New'){
                window.open("extensions/tcpdf/pdf/tempcredential.php?username="+username+"&upassword="+upassword, "_blank"); 
              }

              swal.fire({
                 title: 'User access rights successfully saved!',
                 type: 'success',
                 allowOutsideClick: false,
                 showConfirmButton: false,
                 timer: 1500
              })
              initialize(); 
           }
        })   	
    }

    // function generateTempUsername(length = 8) {
    //     const prefix = 'user_';
    //     const randomString = [...Array(length)].map(() => Math.floor(Math.random() * 16).toString(16)).join('');
    //     return prefix + randomString;
    // }
    
    // // Generate a temporary password
    // function generateTempPassword(length = 12) {
    //     const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-+=';
    //     let password = '';
    //     for (let i = 0; i < length; i++) {
    //         password += chars.charAt(Math.floor(Math.random() * chars.length));
    //     }
    //     return password;
    // }

    // Generate a temporary username (only letters and numbers)
    function generateTempUsername(length = 8) {
        const prefix = 'user';
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        const randomString = [...Array(length)].map(() => chars.charAt(Math.floor(Math.random() * chars.length))).join('');
        return prefix + randomString;
    }

    // Generate a temporary password (only letters and numbers)
    function generateTempPassword(length = 12) {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let password = '';
        for (let i = 0; i < length; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return password;
    }

    var invoices_access = $("#invoices-access").val();
    if (invoices_access == 'ViewOnly'){
      $('select').prop('disabled', true);

      $('input[type="text"]').prop('readonly', true);
      $('textarea').prop('readonly', true);

      $('button').prop('disabled', true);

      $('#btn-search').prop('disabled', false);
    }
});    