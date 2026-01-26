<?php
class ControllerClients{
	static public function ctrCreateCustomer(){
		if(isset($_POST["tns-name"])&&($_POST["trans_type"] == 'New')){
			if (isset($_POST['chk-isactive'])){
			    $isactive=$_POST['chk-isactive'];
		    }else{
		    	$isactive="0";
		    }

		   	$data = array("customercode"=>$_POST["txt-customercode"],
		   		          "name"=>$_POST["tns-name"],
		   		          "description"=>$_POST["tns-description"],
		   		          "mobile"=>$_POST["num-mobile"],
				          "landline"=>$_POST["num-landline"],
				          "faxnum"=>$_POST["num-faxnum"],
				          "website"=>$_POST["tns-website"],
				          "contactperson"=>$_POST["tns-contactperson"],
				          "country"=>$_POST["sel-country"],
				          "isactive"=>$isactive,
				          "address"=>$_POST["tns-address"],
				          "tin"=>$_POST["tns-tin"],
				          "creditlimit" => str_replace(",","",$_POST["num-creditlimit"]));  

		   	$answer = (new ModelClients)->mdlAddCustomer($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Customer profile has been successfully saved!",
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

	static public function ctrEditCustomer(){
		if(isset($_POST["tns-name"])&&($_POST["trans_type"] == 'Update')){
			if (isset($_POST['chk-isactive'])){
			    $isactive='1';
		    }else{
		    	$isactive='0';
		    }

		   	$data = array("id"=>$_POST["num-id"],
                          "customercode"=>$_POST["txt-customercode"],
		   		          "name"=>$_POST["tns-name"],
		   		          "description"=>$_POST["tns-description"],
		   		          "mobile"=>$_POST["num-mobile"],
				          "landline"=>$_POST["num-landline"],
				          "faxnum"=>$_POST["num-faxnum"],
				          "website"=>$_POST["tns-website"],
				          "contactperson"=>$_POST["tns-contactperson"],
				          "country"=>$_POST["sel-country"],
				          "isactive"=>$isactive,
				          "address"=>$_POST["tns-address"],
				          "tin"=>$_POST["tns-tin"],
				          "creditlimit" => str_replace(",","",$_POST["num-creditlimit"]));   

		   	$answer = (new ModelClients)->mdlEditCustomer($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Customer profile has been successfully updated!",
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

	static public function ctrShowCustomer($item, $value){
		$answer = (new ModelClients)->mdlShowCustomer($item, $value);
		return $answer;
	}	

	static public function ctrShowCustomerList(){
		$answer = (new ModelClients)->mdlShowCustomerList();
		return $answer;
	}	
	
	static public function ctrShowActiveCustomerList(){
		$answer = (new ModelClients)->mdlShowActiveCustomerList();
		return $answer;
	}	

	static public function ctrShowCustomerInfo($item, $value){
		$answer = (new ModelClients)->mdlShowCustomerInfo($item, $value);
		return $answer;
	}
}
