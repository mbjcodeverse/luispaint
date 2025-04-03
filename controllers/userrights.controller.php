<?php
class ControllerUserRights{
	static public function ctrAddUserRights($data){
		$answer = (new ModelUserRights)->mdlAddUserRights($data);
	 	return $answer;
    }

	// Update EXISTING RECORD
	static public function ctrEditUserRights($data){
		$answer = (new ModelUserRights)->mdlEditUserRights($data);
		return $answer;
	}   

	static public function ctrResetAccount($data){
		$answer = (new ModelUserRights)->mdlResetAccount($data);
		return $answer;
	} 	
    
    static public function ctrShowUserList(){
		$answer = (new ModelUserRights)->mdlShowUserList();
		return $answer;
	}	

    static public function ctrShowUserRights($item, $value){
		$answer = (new ModelUserRights)->mdlShowUserRights($item, $value);
		return $answer;
	}

	static public function ctrUserLogin(){
		if (isset($_POST["loginUser"])) {
				// $encryptpass = crypt($_POST["loginPass"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
				$encryptpass = $_POST["loginPass"];
				$table = 'userrights';
				$item = 'username';
				$value = $_POST["loginUser"];
				$answer = (new ModelUserRights)->mdlGetUserCredentials($table, $item, $value);

				if(!empty($answer) && $answer["username"] == $_POST["loginUser"] && $answer["upassword"] == $encryptpass){
					$_SESSION["loggedIn"] = "ok";
					$_SESSION["id"] = $answer["id"];
					
					$_SESSION["empid"] = $answer["empid"];
					$_SESSION["userid"] = $answer["userid"];
					
					$_SESSION["invoices"] = $answer["invoices"];
					$_SESSION["clients"] = $answer["clients"];
					$_SESSION["receivable"] = $answer["receivable"];
					$_SESSION["reports"] = $answer["reports"];
					$_SESSION["dashboard"] = $answer["dashboard"];
					$_SESSION["clients"] = $answer["clients"];
					$_SESSION["employees"] = $answer["employees"];
					$_SESSION["branch"] = $answer["branch"];
					$_SESSION["accessprivilege"] = $answer["accessprivilege"];

					$_SESSION["photo"] = $answer["photo"];
					$_SESSION["dashboard"] = $answer["dashboard"];

					if ($_SESSION["dashboard"] == "Full"){
						$_SESSION["show_dashboard"] = true;
						echo '<script>
								window.location = "home";
							  </script>';
					}else{
						$_SESSION["show_dashboard"] = false;
						echo '<script>
								window.location = "default";
							  </script>';
					}
				}else{
					echo '<br><div style="text-align:center;" class="alert alert-danger">User or password incorrect</div>';
				}
			
		}
	}

	static public function ctrGetUserLogin($username, $upassword){
		$answer = (new ModelUserRights)->mdlGetUserLogin($username, $upassword);
		return $answer;
	}  
}