<?php
class ControllerEmployees{
	static public function ctrCreateEmployee(){
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

		   	$data = array("empid"=>$_POST["txt-empid"],
				          "isactive"=>$isactive,
				          "lname"=>$_POST["txt-lname"],
				          "fname"=>$_POST["tns-fname"],
				          "mi"=>$_POST["txt-mi"],
				          "bday"=>$bday,
				          "gender"=>$_POST["sel-gender"],
				          "address"=>$_POST["tns-address"],
				          "mobile"=>$_POST["num-mobile"],
				          "idPos"=>$_POST["sel-position"],
				          "sssno"=>$_POST["num-sssno"],
				          "phino"=>$_POST["num-phino"],
				          "pagibig"=>$_POST["num-pagibig"],
				          "tin"=>$_POST["num-tin"],
				          "estatus"=>$_POST["sel-estatus"]);

		   	$answer = (new ModelEmployees)->mdlAddEmployee($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Employee profile has been successfully saved!",
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

	static public function ctrEditEmployee(){
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
		   				  "empid"=>$_POST["txt-empid"],
				          "isactive"=>$isactive,
				          "lname"=>$_POST["txt-lname"],
				          "fname"=>$_POST["tns-fname"],
				          "mi"=>$_POST["txt-mi"],
				          "bday"=>$bday,
				          "gender"=>$_POST["sel-gender"],
				          "address"=>$_POST["tns-address"],
				          "mobile"=>$_POST["num-mobile"],
				          "idPos"=>$_POST["sel-position"],
				          "sssno"=>$_POST["num-sssno"],
				          "phino"=>$_POST["num-phino"],
				          "pagibig"=>$_POST["num-pagibig"],
				          "tin"=>$_POST["num-tin"],
				          "estatus"=>$_POST["sel-estatus"]);

		   	$answer = (new ModelEmployees)->mdlEditEmployee($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Employee profile has been successfully updated!",
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

	static public function ctrShowEmployees($item, $value){
		$answer = (new ModelEmployees)->mdlShowEmployees($item, $value);
		return $answer;
	}

	static public function ctrShowEmployeeName($item, $value){
		$answer = (new ModelEmployees)->mdlShowEmployeeName($item, $value);
		return $answer;
	}	

	static public function ctrShowEmployeesPosition(){
		$answer = (new ModelEmployees)->mdlShowEmployeesPosition();
		return $answer;
	}	

	static public function ctrShowStatus(){
		$answer = (new ModelEmployees)->mdlShowStatus();
		return $answer;
	}

	static public function ctrShowGender(){
		$answer = (new ModelEmployees)->mdlShowGender();
		return $answer;
	}	

	static public function ctrShowPosition(){
		$answer = (new ModelEmployees)->mdlShowPosition();
		return $answer;
	}	

	static public function ctrShowDriverEmployees($item, $value){
		$table = "employees";
		$answer = (new ModelEmployees)->mdlShowDriverEmployees($table, $item, $value);
		return $answer;
	}
}
