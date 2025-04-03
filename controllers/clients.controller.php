<?php
class ControllerClients{
	static public function ctrCreateClient(){
		if(isset($_POST["txt-lname"])&&isset($_POST["tns-fname"])&&($_POST["trans_type"] == 'New')){
			if (isset($_POST['chk-isactive'])){
			    $isactive=$_POST['chk-isactive'];
		    }else{
		    	$isactive="0";
		    }

		    if ($_POST['date-bday'] != ''){
		    	$bday = date('Y-m-d', strtotime($_POST["date-bday"]));
		    }else{
		        $bday = '0000-00-00';
		    }

		   	$data = array("clientid"=>$_POST["txt-clientid"],
				          "isactive"=>$isactive,
				          "lname"=>$_POST["txt-lname"],
				          "fname"=>$_POST["tns-fname"],
				          "mi"=>$_POST["txt-mi"],
				          "bday"=>$bday,
                        //   "gender"=>$_POST["sel-gender"],
				          "address"=>$_POST["tns-address"],
                          "landline"=>$_POST["num-landline"],
				          "mobile"=>$_POST["num-mobile"],
                          "email"=>$_POST["tns-email"],
                          "spouse"=>$_POST["tns-spouse"]);

		   	$answer = (new ModelClients)->mdlAddClient($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Client profile has been successfully saved!",
		                type: "success",
		                showConfirmButton: true,
				        confirmButtonText: "Ok",
				        confirmButtonClass: "btn btn-light btn-lg",
				        allowOutsideClick: false
		                }).then(function(result){
								if (result.value) {
								  $("#btn-new").click();
				 				}
	                });
				</script>';
			}
		}
	}

	static public function ctrEditClient(){
		if(isset($_POST["txt-lname"])&&isset($_POST["tns-fname"])&&($_POST["trans_type"] == 'Update')){
			if (isset($_POST['chk-isactive'])){
			    $isactive='1';
		    }else{
		    	$isactive='0';
		    }

		    if ($_POST['date-bday'] != ''){
		    	$bday = date('Y-m-d', strtotime($_POST["date-bday"]));
		    }else{
		        $bday = '0000-00-00';
		    }

		   	$data = array("id"=>$_POST["num-id"],
                          "clientid"=>$_POST["txt-clientid"],
                          "isactive"=>$isactive,
                          "lname"=>$_POST["txt-lname"],
                          "fname"=>$_POST["tns-fname"],
                          "mi"=>$_POST["txt-mi"],
                          "bday"=>$bday,
                        //   "gender"=>$_POST["sel-gender"],
                          "address"=>$_POST["tns-address"],
                          "landline"=>$_POST["num-landline"],
                          "mobile"=>$_POST["num-mobile"],
                          "email"=>$_POST["tns-email"],
                          "spouse"=>$_POST["tns-spouse"]);

		   	$answer = (new ModelClients)->mdlEditClient($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Client profile has been successfully updated!",
		                type: "success",
		                showConfirmButton: true,
				        confirmButtonText: "Ok",
				        confirmButtonClass: "btn btn-light btn-lg",
				        allowOutsideClick: false
		                }).then(function(result){
								if (result.value) {
								  $("#btn-new").click();
				 				}
	                });
				</script>';
			}
		}
	}	

	static public function ctrShowClients($item, $value){
		$answer = (new ModelClients)->mdlShowClients($item, $value);
		return $answer;
	}

	static public function ctrShowClientName($item, $value){
		$answer = (new ModelClients)->mdlShowClientName($item, $value);
		return $answer;
	}

	static public function ctrShowGender(){
		$answer = (new ModelClients)->mdlShowGender();
		return $answer;
	}
    
	static public function ctrShowEmployeesList(){
		$answer = (new ModelClients)->mdlShowEmployeesList();
		return $answer;
	}    

	// static public function ctrShowPosition(){
	// 	$answer = (new ModelClients)->mdlShowPosition();
	// 	return $answer;
	// }	

	// static public function ctrShowDriverClients($item, $value){
	// 	$table = "employees";
	// 	$answer = (new ModelClients)->mdlShowDriverClients($table, $item, $value);
	// 	return $answer;
	// }
}
