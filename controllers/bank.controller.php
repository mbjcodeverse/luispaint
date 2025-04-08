<?php
class ControllerBank{
	static public function ctrCreateBank(){
		if(isset($_POST["txt-bankname"])&&($_POST["trans_type"] == 'New')){
		   	$data = array("bankcode"=>$_POST["txt-bankcode"],
				          "bankname"=>$_POST["txt-bankname"],
				          "bankaddress"=>$_POST["tns-bankaddress"],
				          "banklandline"=>$_POST["num-banklandline"],
				          "bankmobile"=>$_POST["num-bankmobile"],
				          "bankwebsite"=>$_POST["tns-bankwebsite"],
				          "bankcontact"=>$_POST["tns-bankcontact"]);  

		   	$answer = (new ModelBank)->mdlAddBank($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Bank details has been successfully saved!",
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

	static public function ctrEditBank(){
		if(isset($_POST["txt-bankname"])&&($_POST["trans_type"] == 'Update')){

		   	$data = array("id"=>$_POST["num-id"],
		   				  "bankcode"=>$_POST["txt-bankcode"],
				          "bankname"=>$_POST["txt-bankname"],
				          "bankaddress"=>$_POST["tns-bankaddress"],
				          "banklandline"=>$_POST["num-banklandline"],
				          "bankmobile"=>$_POST["num-bankmobile"],
				          "bankwebsite"=>$_POST["tns-bankwebsite"],
				          "bankcontact"=>$_POST["tns-bankcontact"]); 
 
		   	$answer = (new ModelBank)->mdlEditBank($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Bank details has been successfully updated!",
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

	static public function ctrShowBank($item, $value){
		$answer = (new ModelBank)->mdlShowBank($item, $value);
		return $answer;
	}		

	static public function ctrShowBankList(){
		$answer = (new ModelBank)->mdlShowBankList();
		return $answer;
	}
}
