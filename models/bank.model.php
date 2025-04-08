<?php
require_once "connection.php";
class ModelBank{
	static public function mdlAddBank($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $bank_id = $pdo->prepare("SELECT CONCAT('BA', LPAD((count(id)+1),4,'0')) as gen_id  FROM bank FOR UPDATE");

            $bank_id->execute();
		    $bank_code = $bank_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO bank(bankcode, bankname, bankaddress, banklandline, bankmobile, bankwebsite, bankcontact) VALUES (:bankcode, :bankname, :bankaddress, :banklandline, :bankmobile, :bankwebsite, :bankcontact)");

			$stmt->bindParam(":bankcode", $bank_code[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":bankname", $data["bankname"], PDO::PARAM_STR);
			$stmt->bindParam(":bankaddress", $data["bankaddress"], PDO::PARAM_STR);
			$stmt->bindParam(":banklandline", $data["banklandline"], PDO::PARAM_STR);
			$stmt->bindParam(":bankmobile", $data["bankmobile"], PDO::PARAM_STR);
			$stmt->bindParam(":bankwebsite", $data["bankwebsite"], PDO::PARAM_STR);
			$stmt->bindParam(":bankcontact", $data["bankcontact"], PDO::PARAM_STR);

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

	static public function mdlEditBank($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE bank SET bankcode = :bankcode, bankname = :bankname, bankaddress = :bankaddress, banklandline = :banklandline, bankmobile = :bankmobile, bankwebsite = :bankwebsite, bankcontact = :bankcontact WHERE id = :id");

			$stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
			$stmt->bindParam(":bankcode", $data["bankcode"], PDO::PARAM_STR);
			$stmt->bindParam(":bankname", $data["bankname"], PDO::PARAM_STR);
			$stmt->bindParam(":bankaddress", $data["bankaddress"], PDO::PARAM_STR);
			$stmt->bindParam(":banklandline", $data["banklandline"], PDO::PARAM_STR);
			$stmt->bindParam(":bankmobile", $data["bankmobile"], PDO::PARAM_STR);
			$stmt->bindParam(":bankwebsite", $data["bankwebsite"], PDO::PARAM_STR);
			$stmt->bindParam(":bankcontact", $data["bankcontact"], PDO::PARAM_STR);

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

	static public function mdlShowBank($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM bank WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowBankList(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM bank ORDER BY bankname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
}