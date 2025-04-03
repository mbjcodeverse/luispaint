<?php
require_once "connection.php";
class ModelEmployees{
	static public function mdlAddEmployee($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $emp_id = $pdo->prepare("SELECT CONCAT('EM', LPAD((count(id)+1),5,'0')) as gen_id  FROM employees FOR UPDATE");

            $emp_id->execute();
		    $empid = $emp_id -> fetchAll(PDO::FETCH_ASSOC);

			$stmt = $pdo->prepare("INSERT INTO employees(empid, isactive, lname, fname, mi, bday, gender, address, mobile, idPos, sssno, phino, pagibig, tin, estatus) VALUES (:empid, :isactive, :lname, :fname, :mi, :bday, :gender, :address, :mobile, :idPos, :sssno, :phino, :pagibig, :tin, :estatus)");

			$stmt->bindParam(":empid", $empid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":lname", $data["lname"], PDO::PARAM_STR);
			$stmt->bindParam(":fname", $data["fname"], PDO::PARAM_STR);
			$stmt->bindParam(":mi", $data["mi"], PDO::PARAM_STR);
			$stmt->bindParam(":bday", $data["bday"], PDO::PARAM_STR);
			$stmt->bindParam(":gender", $data["gender"], PDO::PARAM_STR);
			$stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);
			$stmt->bindParam(":mobile", $data["mobile"], PDO::PARAM_STR);
			$stmt->bindParam(":idPos", $data["idPos"], PDO::PARAM_INT);
			$stmt->bindParam(":sssno", $data["sssno"], PDO::PARAM_STR);
			$stmt->bindParam(":phino", $data["phino"], PDO::PARAM_STR);
			$stmt->bindParam(":pagibig", $data["pagibig"], PDO::PARAM_STR);
			$stmt->bindParam(":tin", $data["tin"], PDO::PARAM_STR);
			$stmt->bindParam(":estatus", $data["estatus"], PDO::PARAM_STR);

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

	static public function mdlEditEmployee($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE employees SET empid = :empid, isactive = :isactive, lname = :lname, fname = :fname, mi = :mi, bday = :bday, gender = :gender, address = :address, mobile = :mobile, idPos = :idPos, sssno = :sssno, phino = :phino, pagibig = :pagibig, tin = :tin, estatus = :estatus WHERE id = :id");

			$stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
			$stmt->bindParam(":empid", $data["empid"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":lname", $data["lname"], PDO::PARAM_STR);
			$stmt->bindParam(":fname", $data["fname"], PDO::PARAM_STR);
			$stmt->bindParam(":mi", $data["mi"], PDO::PARAM_STR);
			$stmt->bindParam(":bday", $data["bday"], PDO::PARAM_STR);
			$stmt->bindParam(":gender", $data["gender"], PDO::PARAM_STR);
			$stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);
			$stmt->bindParam(":mobile", $data["mobile"], PDO::PARAM_STR);
			$stmt->bindParam(":idPos", $data["idPos"], PDO::PARAM_INT);
			$stmt->bindParam(":sssno", $data["sssno"], PDO::PARAM_STR);
			$stmt->bindParam(":phino", $data["phino"], PDO::PARAM_STR);
			$stmt->bindParam(":pagibig", $data["pagibig"], PDO::PARAM_STR);
			$stmt->bindParam(":tin", $data["tin"], PDO::PARAM_STR);
			$stmt->bindParam(":estatus", $data["estatus"], PDO::PARAM_STR);

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

	static public function mdlShowEmployees($item, $value){
		if($item != null){
			$stmt = (new Connection)->connect()->prepare("SELECT * FROM employees WHERE $item = :$item");
			$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
			$stmt -> execute();
			return $stmt -> fetch();
		}else{
			$stmt = (new Connection)->connect()->prepare("SELECT * FROM employees ORDER BY lname, fname");
			$stmt -> execute();
			return $stmt -> fetchAll();
		}
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowEmployeeName($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM employees WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowEmployeesPosition(){
		$stmt = (new Connection)->connect()->prepare("SELECT a.id,a.lname,a.fname,a.mi,b.positiondesc,a.mobile,a.estatus FROM employees AS a INNER JOIN position AS b ON (a.idPos = b.id) ORDER BY a.lname,a.fname");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

    /*=============================================
	SHOW POSITION
	=============================================*/
	static public function mdlShowPosition(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM position ORDER BY positiondesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;	
	}

	static public function mdlShowStatus(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM estatus ORDER BY id");
		$stmt -> execute();
		return $stmt -> fetchAll();
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

	static public function mdlUpdateEmployee($table, $item1, $value1, $value){
		$stmt = (new Connection)->connect()->prepare("UPDATE $table SET $item1 = :$item1 WHERE id = :id");
		$stmt -> bindParam(":".$item1, $value1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $value, PDO::PARAM_STR);
		if($stmt -> execute()){
			return "ok";
		}else{
			return "error";	
		}
		$stmt -> close();
		$stmt = null;
	}
}