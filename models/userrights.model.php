<?php
require_once "connection.php";
class ModelUserRights{
	static public function mdlAddUserRights($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $user_id = $pdo->prepare("SELECT userid FROM userrights ORDER BY id DESC LIMIT 1");

            $user_id->execute();
		    $userid = $user_id -> fetchAll(PDO::FETCH_ASSOC);

		    $user_number = $userid[0]['userid'];
		    $sequence_code = strval(intval(substr($user_number,-4)) + 1);
		    $usercode = 'U' . str_repeat("0",4 - strlen($sequence_code)) . $sequence_code;

			// $encryptpass = crypt($data["upassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
			$encryptpass = $data["upassword"];
			$stmt = $pdo->prepare("INSERT INTO userrights(userid, empid, invoices, receivable, reports, dashboard, clients, employees, branch, accessprivilege, username, upassword) VALUES (:userid, :empid, :invoices, :receivable, :reports, :dashboard, :clients, :employees, :branch, :accessprivilege, :username, :upassword)");	

			$stmt->bindParam(":userid", $usercode, PDO::PARAM_STR);
			$stmt->bindParam(":empid", $data["empid"], PDO::PARAM_STR);
			$stmt->bindParam(":invoices", $data["invoices"], PDO::PARAM_STR);	
			$stmt->bindParam(":receivable", $data["receivable"], PDO::PARAM_STR);
			$stmt->bindParam(":reports", $data["reports"], PDO::PARAM_STR);
			$stmt->bindParam(":dashboard", $data["dashboard"], PDO::PARAM_STR);
			$stmt->bindParam(":clients", $data["clients"], PDO::PARAM_STR);
			$stmt->bindParam(":employees", $data["employees"], PDO::PARAM_STR);	
			$stmt->bindParam(":branch", $data["branch"], PDO::PARAM_STR);
            $stmt->bindParam(":accessprivilege", $data["accessprivilege"], PDO::PARAM_STR);
            $stmt->bindParam(":username", $data["username"], PDO::PARAM_STR);
            $stmt->bindParam(":upassword", $encryptpass, PDO::PARAM_STR);
			$stmt->execute();	
		    $pdo->commit();

		    return $usercode;
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}
	}

    static public function mdlEditUserRights($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE userrights SET userid = :userid, empid = :empid, invoices = :invoices, receivable = :receivable, reports = :reports, dashboard = :dashboard, clients = :clients, employees = :employees, branch = :branch, accessprivilege = :accessprivilege WHERE userid = :userid");

            $stmt->bindParam(":userid", $data["userid"], PDO::PARAM_STR);
			$stmt->bindParam(":empid", $data["empid"], PDO::PARAM_STR);
			$stmt->bindParam(":invoices", $data["invoices"], PDO::PARAM_STR);	
			$stmt->bindParam(":receivable", $data["receivable"], PDO::PARAM_STR);
			$stmt->bindParam(":reports", $data["reports"], PDO::PARAM_STR);
			$stmt->bindParam(":dashboard", $data["dashboard"], PDO::PARAM_STR);
			$stmt->bindParam(":clients", $data["clients"], PDO::PARAM_STR);
			$stmt->bindParam(":employees", $data["employees"], PDO::PARAM_STR);	
			$stmt->bindParam(":branch", $data["branch"], PDO::PARAM_STR);
            $stmt->bindParam(":accessprivilege", $data["accessprivilege"], PDO::PARAM_STR);
			$stmt->execute();

			$userid = $data["userid"];
		    $pdo->commit();
		    return $userid;
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}
	}

    static public function mdlResetAccount($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			// $encryptpass = crypt($data["password"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
			$encryptpass = $data["password"];

			$stmt = $pdo->prepare("UPDATE userrights SET username = :username, upassword = :upassword WHERE userid = :userid");

            $stmt->bindParam(":userid", $data["userid"], PDO::PARAM_STR);
			$stmt->bindParam(":username", $data["username"], PDO::PARAM_STR);
			$stmt->bindParam(":upassword", $encryptpass, PDO::PARAM_STR);	
			$stmt->execute();

			$userid = $data["userid"];
		    $pdo->commit();
		    return $userid;
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}
	}	

    static public function mdlShowUserList(){
		$stmt = (new Connection)->connect()->prepare("SELECT a.id,a.empid,a.lname,a.fname,a.mi,b.positiondesc,c.userid FROM employees AS a INNER JOIN position AS b ON (a.idPos = b.id) INNER JOIN userrights AS c ON (a.empid = c.empid) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
	}
    
    static public function mdlShowUserRights($item, $value){
		if($item != null){
			$stmt = (new Connection)->connect()->prepare("SELECT * FROM userrights WHERE $item = :$item");
			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
		}else{
			$stmt = (new Connection)->connect()->prepare("SELECT * FROM userrights ORDER BY userid");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}
	}

	static public function mdlGetUserCredentials($tableUsers, $item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM $tableUsers WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
	}

	static public function mdlGetUserLogin($username, $upassword){
		// $encryptpass = crypt($upassword, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
		$encryptpass = $upassword;
		$stmt = (new Connection)->connect()->prepare("SELECT userid, username, upassword FROM userrights WHERE (username = '$username') AND (upassword = '$encryptpass')");
		$stmt -> execute();
		return $stmt -> fetch();
	}
}