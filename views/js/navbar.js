$(function() {
   let branchcode = $("#branch_code").val();
   var branch_name = new FormData();
   branch_name.append("branchcode", branchcode);
   $.ajax({
       url:"ajax/get_branch_name.ajax.php",   
       method: "POST",                
       data: branch_name,                    
       cache: false,                  
       contentType: false,            
       processData: false,            
       dataType:"json",               
       success:function(answer){
          if (answer["bname"] != null){
            let bn = answer["bname"] + ' BRANCH';
            $('#current_branch').text(bn.toUpperCase());
          }else{
            $('#current_branch').text('CENTRAL OFFICE');
          }    
       }
   })

   var today = new Date();

   var month = today.toLocaleString('default', { month: 'long' });
   var dd = String(today.getDate()).padStart(2, '0');
   var yyyy = today.getFullYear();

   var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
   var dayName = days[today.getDay()]; 

   const myInterval = setInterval(myTimer, 1000);

  //  today = month.toUpperCase() + ' ' + dd + ', ' + yyyy + '  [ ' + dayName.toUpperCase() + ' ] ';
  //  $('#current_date').text(today);

  //  function myTimer() {
  //    const date = new Date();
  //    document.getElementById("current_time").innerHTML = date.toLocaleTimeString();
  //  } 
});  