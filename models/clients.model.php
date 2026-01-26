<?php
require_once "connection.php";
class ModelClients{
	// static public function mdlAddCustomer($data){
	// 	$db = new Connection();
	// 	$pdo = $db->connect();
    //     try{
    //     	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //         $pdo->beginTransaction();

    //         $cust_id = $pdo->prepare("SELECT CONCAT('C', LPAD((count(id)+1),5,'0')) as gen_id  FROM customer FOR UPDATE");

    //         $cust_id->execute();
	// 	    $custid = $cust_id -> fetchAll(PDO::FETCH_ASSOC);

	// 		$stmt = $pdo->prepare("INSERT INTO customer(customercode, name, description, mobile, landline, faxnum, website, contactperson, country, isactive, address, tin,  creditlimit) VALUES (:customercode, :name, :description, :mobile, :landline, :faxnum, :website, :contactperson, :country, :isactive, :address, :tin, :creditlimit)");

	// 		$stmt->bindParam(":customercode", $custid[0]['gen_id'], PDO::PARAM_STR);
	// 		$stmt->bindParam(":name", $data["name"], PDO::PARAM_STR);
	// 		$stmt->bindParam(":description", $data["description"], PDO::PARAM_STR);
	// 		$stmt->bindParam(":mobile", $data["mobile"], PDO::PARAM_STR);
	// 		$stmt->bindParam(":landline", $data["landline"], PDO::PARAM_STR);
	// 		$stmt->bindParam(":faxnum", $data["faxnum"], PDO::PARAM_STR);
	// 		$stmt->bindParam(":website", $data["website"], PDO::PARAM_STR);
	// 		$stmt->bindParam(":contactperson", $data["contactperson"], PDO::PARAM_STR);
	// 		$stmt->bindParam(":country", $data["country"], PDO::PARAM_STR);
	// 		$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
	// 		$stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);
	// 		$stmt->bindParam(":tin", $data["tin"], PDO::PARAM_STR);
	// 		$stmt->bindParam(":creditlimit", $data["creditlimit"], PDO::PARAM_STR);

	// 		$stmt->execute();
	// 	    $pdo->commit();
	// 	    return "ok";
	// 	}catch (Exception $e){
	// 		$pdo->rollBack();
	// 		return "error";
	// 	}finally {
	// 		// Explicitly close resources to prevent memory leaks
	// 		$pdo = null;  // Close the PDO connection
	// 		$stmt = null; // Close the prepared statement
	// 		$cust_id = null; // Close the prepared statement for customer ID generation
	// 	}	
	// }

	static public function mdlAddCustomer($data) {
		$db = new Connection();
		$pdo = $db->connect();
		
		try {
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->beginTransaction();
	
			// Generate customer ID using FOR UPDATE to ensure isolation
			$cust_id = $pdo->prepare("SELECT CONCAT('C', LPAD((count(id)+1),5,'0')) as gen_id FROM customer FOR UPDATE");
			$cust_id->execute();
			$custid = $cust_id->fetchAll(PDO::FETCH_ASSOC);
	
			// Prepare insert statement
			$stmt = $pdo->prepare("INSERT INTO customer(customercode, name, description, mobile, landline, faxnum, website, contactperson, country, isactive, address, tin, creditlimit) VALUES (:customercode, :name, :description, :mobile, :landline, :faxnum, :website, :contactperson, :country, :isactive, :address, :tin, :creditlimit)");
	
			// Define the fields and their types
			$fields = [
				'customercode' => ['value' => $custid[0]['gen_id'], 'type' => PDO::PARAM_STR],
				'name' => ['value' => $data['name'], 'type' => PDO::PARAM_STR],
				'description' => ['value' => $data['description'], 'type' => PDO::PARAM_STR],
				'mobile' => ['value' => $data['mobile'], 'type' => PDO::PARAM_STR],
				'landline' => ['value' => $data['landline'], 'type' => PDO::PARAM_STR],
				'faxnum' => ['value' => $data['faxnum'], 'type' => PDO::PARAM_STR],
				'website' => ['value' => $data['website'], 'type' => PDO::PARAM_STR],
				'contactperson' => ['value' => $data['contactperson'], 'type' => PDO::PARAM_STR],
				'country' => ['value' => $data['country'], 'type' => PDO::PARAM_STR],
				'isactive' => ['value' => $data['isactive'], 'type' => PDO::PARAM_INT], 
				'address' => ['value' => $data['address'], 'type' => PDO::PARAM_STR],
				'tin' => ['value' => $data['tin'], 'type' => PDO::PARAM_STR],
				'creditlimit' => ['value' => $data['creditlimit'], 'type' => PDO::PARAM_STR]
			];
	
			// Loop through the fields and bind them dynamically with the correct types
			foreach ($fields as $key => $field) {
				$stmt->bindParam(":$key", $field['value'], $field['type']);
			}
	
			$stmt->execute();
			$pdo->commit();
			return "ok";
		} catch (Exception $e) {
			$pdo->rollBack();
			error_log("Error in mdlAddCustomer: " . $e->getMessage()); // Detailed logging
			return "error";
		} finally {
			// Explicitly close resources to prevent memory leaks
			$pdo = null;  // Close the PDO connection
			$stmt = null; // Close the prepared statement
			$cust_id = null; // Close the prepared statement for customer ID generation
		}
	}
	
	static public function mdlEditCustomer($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE customer SET customercode = :customercode, name = :name, description = :description, mobile = :mobile, landline = :landline, faxnum = :faxnum, website = :website, contactperson = :contactperson, country = :country, isactive = :isactive, address = :address, tin = :tin, creditlimit = :creditlimit WHERE id = :id");

			$stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
			$stmt->bindParam(":customercode", $data["customercode"], PDO::PARAM_STR);
			$stmt->bindParam(":name", $data["name"], PDO::PARAM_STR);
			$stmt->bindParam(":description", $data["description"], PDO::PARAM_STR);
			$stmt->bindParam(":mobile", $data["mobile"], PDO::PARAM_STR);
			$stmt->bindParam(":landline", $data["landline"], PDO::PARAM_STR);
			$stmt->bindParam(":faxnum", $data["faxnum"], PDO::PARAM_STR);
			$stmt->bindParam(":website", $data["website"], PDO::PARAM_STR);
			$stmt->bindParam(":contactperson", $data["contactperson"], PDO::PARAM_STR);
			$stmt->bindParam(":country", $data["country"], PDO::PARAM_STR);
			$stmt->bindParam(":isactive", $data["isactive"], PDO::PARAM_INT);
			$stmt->bindParam(":address", $data["address"], PDO::PARAM_STR);
			$stmt->bindParam(":tin", $data["tin"], PDO::PARAM_STR);
			$stmt->bindParam(":creditlimit", $data["creditlimit"], PDO::PARAM_STR);

			$stmt->execute();  

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}finally {
			// Explicitly close resources to prevent memory leaks
			$pdo = null;  		// Close the PDO connection
			$stmt = null; 		// Close the prepared statement
		}	
		// $pdo = null;	
		// $stmt = null;
	}	

	static public function mdlShowCustomer($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM customer WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowCustomerList(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM customer ORDER BY name");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowActiveCustomerList(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM customer WHERE (isactive = 1) ORDER BY name");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowCustomerInfo($item, $value){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM customer WHERE $item = :$item");
		$stmt -> bindParam(":".$item, $value, PDO::PARAM_STR);
		$stmt -> execute();
		return $stmt -> fetch();
		$stmt -> close();
		$stmt = null;
	}
}