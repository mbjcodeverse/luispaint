<?php
require_once "connection.php";
class ModelClients{
	static public function mdlAddClient($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $client_id = $pdo->prepare("SELECT CONCAT('C', LPAD((count(id)+1),6,'0')) as gen_id FROM client FOR UPDATE");

            $client_id->execute();
		    $clientid = $client_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO client(clientid, isactive, lname, fname, mi, bday, address, landline, mobile, email, spouse) VALUES (:clientid, :isactive, :lname, :fname, :mi, :bday, :address, :landline, :mobile, :email, :spouse)");

            $last_name = ucwords($data["lname"]);
            $first_name = ucwords($data["fname"]);
            $mid_initial = strtoupper($data["mi"]);

			$stmt->bindParam(":clientid", $clientid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":lname", $last_name, PDO::PARAM_STR);
			$stmt->bindParam(":fname", $first_name, PDO::PARAM_STR);
			$stmt->bindParam(":mi", $mid_initial, PDO::PARAM_STR);
			$stmt->bindParam(":bday", $data["bday"], PDO::PARAM_STR);
			// $stmt->bindParam(":gender", $data["gender"], PDO::PARAM_STR);
			$stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);
            $stmt->bindParam(":landline", $data["landline"], PDO::PARAM_STR);
			$stmt->bindParam(":mobile", $data["mobile"], PDO::PARAM_STR);
			$stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
			$stmt->bindParam(":spouse", $data["spouse"], PDO::PARAM_STR);

			$stmt->execute();
		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}

	static public function mdlEditClient($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE client SET clientid = :clientid, isactive = :isactive, lname = :lname, fname = :fname, mi = :mi, bday = :bday, address = :address, landline = :landline, mobile = :mobile, email = :email, spouse = :spouse WHERE id = :id");

            $last_name = ucwords($data["lname"]);
            $first_name = ucwords($data["fname"]);
            $mid_initial = strtoupper($data["mi"]);

			$stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
			$stmt->bindParam(":clientid", $data["clientid"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":lname", $last_name, PDO::PARAM_STR);
			$stmt->bindParam(":fname", $first_name, PDO::PARAM_STR);
			$stmt->bindParam(":mi", $mid_initial, PDO::PARAM_STR);
			$stmt->bindParam(":bday", $data["bday"], PDO::PARAM_STR);
			// $stmt->bindParam(":gender", $data["gender"], PDO::PARAM_STR);
			$stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);
            $stmt->bindParam(":landline", $data["landline"], PDO::PARAM_STR);
			$stmt->bindParam(":mobile", $data["mobile"], PDO::PARAM_STR);
			$stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
			$stmt->bindParam(":spouse", $data["spouse"], PDO::PARAM_STR);
			$stmt->execute();  

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}	

	static public function mdlShowClients($item, $value){
		if($item != null){
			$stmt = (new Connection)->connect()->prepare("SELECT * FROM client WHERE $item = :$item");
			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
		}else{
			$stmt = (new Connection)->connect()->prepare("SELECT * FROM client ORDER BY lname, fname");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowClientName($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM clients WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowGender(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM gender ORDER BY id");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}

	static public function mdlShowEmployeesList(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM client ORDER BY lname,fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}    

	// static public function mdlUpdateClient($table, $item1, $value1, $value){
	// 	$stmt = (new Connection)->connect()->prepare("UPDATE $table SET $item1 = :$item1 WHERE id = :id");
	// 	$stmt -> bindParam(":".$item1, $value1, PDO::PARAM_STR);
	// 	$stmt -> bindParam(":id", $value, PDO::PARAM_STR);
	// 	if($stmt -> execute()){
	// 		return "ok";
	// 	}else{
	// 		return "error";	
	// 	}
	// 	$stmt -> close();
	// 	$stmt = null;
	// }
}