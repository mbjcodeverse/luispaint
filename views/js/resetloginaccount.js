$(function() {
    $('input[type="text"], textarea').css('border', '1px solid rgba(255, 255, 255, 0.3)');
    $('input[type="password"], textarea').css('border', '1px solid rgba(255, 255, 255, 0.3)');

    $("#txt-userid").val('');

    $('#new-username').prop('disabled', true);
    $('#new-password').prop('disabled', true);
    $('#btn-resetaccount').prop('disabled', true);

    $('#temp-username').focus();

    $("#btn-validate").click(function(){
        var upassword = $("#temp-password").val().trim();
        var username = $('#temp-username').val().trim();
        
        if (username === '' || upassword === '') {
            swal.fire({
                title: 'Temporary user name and password must not be empty!',
                type: 'warning',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 2000
            })
        }else{
            var credential = new FormData();
            credential.append("username", username);
            credential.append("upassword", upassword);
            $.ajax({
                url:"ajax/get_login_credential.ajax.php",
                method: "POST",
                data: credential,
                cache: false,
                contentType: false,
                processData: false,
                async: false,
                dataType:"json",
                success:function(answer){
                   if (username == answer["username"]){
                      $("#txt-userid").val(answer["userid"]);

                      $('#temp-username').prop('disabled', true);
                      $('#temp-password').prop('disabled', true);
                      $('#btn-validate').prop('disabled', true);

                      $('#new-username').prop('disabled', false);
                      $('#new-password').prop('disabled', false);
                      $('#btn-resetaccount').prop('disabled', false);

                      $('#new-username').focus();
                   }else{
                      swal.fire({
                        title: 'Invalid login credential entry!',
                        type: 'error',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 2000
                      })  
                      $('#temp-username').val('');  
                      $('#temp-password').val('');  
                      $('#temp-username').focus();              
                   }                 
                }
            })
        }
    }); 

    $("#btn-resetaccount").click(function(){
      swal.fire({
        title: 'Do you want to reset your login credential?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, reset!',
        cancelButtonText: 'No',
        confirmButtonClass: 'btn btn-outline-success',
        cancelButtonClass: 'btn btn-outline-danger',
        allowOutsideClick: false,
        buttonsStyling: false
      }).then(function(result) {
        if(result.value) {  
            reset_account();
        }
      });
    }); 
    
    function reset_account(){
      $("#btn-resetaccount").prop('disabled', true);  
      var userid = $("#txt-userid").val();
      var username = $("#new-username").val();
      var password = $("#new-password").val();
      var user = new FormData();
      user.append("userid", userid);
      user.append("username", username);
      user.append("password", password);
      $.ajax({
        url:"ajax/reset_account.ajax.php",
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
           swal.fire({
              title: 'User login credential successfully resetted!',
              type: 'success',
              allowOutsideClick: false,
              showConfirmButton: false,
              timer: 1500
           })
           $("#txt-userid").val('');

           $("#temp-username").val('');
           $("#temp-password").val('');

           $('#temp-username').prop('disabled', false);
           $('#temp-password').prop('disabled', false);
           $('#btn-validate').prop('disabled', false);

           $("#new-username").val('');
           $("#new-password").val('');

           $('#new-username').prop('disabled', true);
           $('#new-password').prop('disabled', true);
           $('#btn-resetaccount').prop('disabled', true);

           $('#password-strength-label').hide();
           $('#strength-level').css('width', '0');
           $('#strength-level').css('background-color', '#fff');
       
           $('#temp-username').focus();
        }
      })   	
    }

    $('#new-username, #new-password').focus(function() {
        $(this).css({
          'border': '3px solid rgba(255, 215, 0, 0.7)'
        });
    }).blur(function() {
        $(this).css({
          'border': '1px solid rgba(255, 255, 255, 0.3)'
        });
    });

    $('#temp-username, #temp-password,#new-username, #new-password').on('keypress', function(e) {
        // If the pressed key is a space (key code 32)
        if (e.which == 32) {
            e.preventDefault();  // Prevent the space from being typed
        }
    });

    $('#new-password').on('input', function () {
        var password = $(this).val();
        // If password is empty, hide the strength label
        if (password === "") {
            $('#password-strength-label').hide();
            $('#strength-level').css('width', '0');
            $('#strength-level').css('background-color', '#fff');
        } else {
            // If password is not empty, show the label and update the strength bar
            var strength = getPasswordStrength(password);
            updateStrengthBar(strength);
            updateStrengthLabel(strength);
            $('#password-strength-label').show();
        }
    });

    function getPasswordStrength(password) {
        var strength = 0;

        // Check length of password
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;

        // Check for lowercase letters
        if (/[a-z]/.test(password)) strength++;

        // Check for uppercase letters
        if (/[A-Z]/.test(password)) strength++;

        // Check for numbers
        if (/\d/.test(password)) strength++;

        // Check for special characters
        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;

        return strength;
    }

    function updateStrengthBar(strength) {
        var strengthPercentage = (strength / 6) * 100;
        $('#strength-level').css('width', strengthPercentage + '%');

        // Color the strength bar based on strength
        if (strength < 2) {
          $('#strength-level').css('background-color', 'red');
        } else if (strength >= 2 && strength < 4) {
          $('#strength-level').css('background-color', 'orange');
        } else {
          $('#strength-level').css('background-color', 'lime');
        }
    }

    function updateStrengthLabel(strength) {
        var label = '';

        if (strength < 2) {
          label = 'Weak';
        } else if (strength >= 2 && strength < 4) {
          label = 'Moderate';
        } else {
          label = 'Strong';
        }

        $('#password-strength-label').text(label);
    }

    $('#toggle-eye').on('click', function () {
        var passwordField = $('#new-password');
        var fieldType = passwordField.attr('type');

        if (fieldType === 'password') {
          passwordField.attr('type', 'text');
          $(this).html('&#128064;');  // Change icon to open eye
        } else {
          passwordField.attr('type', 'password');
          $(this).html('&#128065;');  // Change icon to closed eye
        }
    });

    $('#temp-toggle-eye').on('click', function () {
        var passwordField = $('#temp-password');
        var fieldType = passwordField.attr('type');

        if (fieldType === 'password') {
          passwordField.attr('type', 'text');
          $(this).html('&#128064;');  // Change icon to open eye
        } else {
          passwordField.attr('type', 'password');
          $(this).html('&#128065;');  // Change icon to closed eye
        }
    });
});    