<?php
require_once 'connection.php';
class ModelHome{
	static public function mdlLotAllList(){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM lotinfo ORDER BY id");
		$stmt -> execute();
		return $stmt -> fetchAll();
	}

	static public function mdlLotCategoryList($categorycode){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM lotinfo WHERE categorycode = '$categorycode' ORDER BY id");
		$stmt -> execute();
		return $stmt -> fetchAll();
	}

	static public function mdlPostLotLocation($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            // PO # not included
			$stmt = $pdo->prepare("UPDATE lotinfo SET latitude = :latitude, longitude = :longitude WHERE id = :id");

			$stmt->bindParam(":latitude", $data["latitude"], PDO::PARAM_STR);
			$stmt->bindParam(":longitude", $data["longitude"], PDO::PARAM_STR);
			$stmt->bindParam(":id", $data["id"], PDO::PARAM_INT);
			
			$stmt->execute();
			$id = $data["id"];
		    $pdo->commit();
		    return $id;
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
	}

	static public function mdlGetNearestLot($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$userLat = floatval($data["latitude"]);		// clicked Latitude from the map
            $userLng = floatval($data["longitude"]);		// clicked Longitude from the map

			$stmt = $pdo->prepare("SELECT d.lotid, d.latitude, d.longitude, 
			(6371 * ACOS(COS(RADIANS(:userLat)) * COS(RADIANS(d.latitude)) * 
			COS(RADIANS(d.longitude) - RADIANS(:userLng)) + 
			SIN(RADIANS(:userLat)) * SIN(RADIANS(d.latitude)))) AS distance, 
			a.clientid,a.lname,a.fname,a.mi,b.saleid,b.salestatus,b.scode,b.purdate,b.certnum,b.certdate,b.beneficiary,b.relation,b.councilor,b.remarks,b.salecode,IFNULL(c.decedentlist,'') as decedentlist
			FROM client as a INNER JOIN sales as b ON (a.clientid = b.clientid)
							 LEFT JOIN interment as c ON (b.saleid = c.saleid)
			                 INNER JOIN lotinfo as d ON (b.lotid = d.lotid)
			HAVING distance <= 0.001 
			ORDER BY distance 
			LIMIT 1");

			// $stmt = $pdo->prepare("SELECT d.lotid, d.latitude, d.longitude, 
			// (6371 * ACOS(COS(RADIANS(:userLat)) * COS(RADIANS(d.latitude)) * 
			// COS(RADIANS(d.longitude) - RADIANS(:userLng)) + 
			// SIN(RADIANS(:userLat)) * SIN(RADIANS(d.latitude)))) AS distance, 
			// a.clientid,a.lname,a.fname,a.mi,b.saleid,b.salestatus,b.scode,b.purdate,b.certnum,b.certdate,b.beneficiary,b.relation,b.councilor,b.remarks,b.salecode,IFNULL(c.decedentlist,'') as decedentlist
			// FROM client as a INNER JOIN sales as b ON (a.clientid = b.clientid)
			// 				LEFT JOIN interment as c ON (b.saleid = c.saleid)
			// 				INNER JOIN lotinfo as d ON (b.lotid = d.lotid)
			// HAVING distance <= 0.001 
			// ORDER BY distance 
			// LIMIT 1");

			$stmt->bindParam(":userLng", $userLng, PDO::PARAM_STR);
			$stmt->bindParam(":userLat", $userLat, PDO::PARAM_STR);
			
			$stmt->execute();
		    $pdo->commit();
		    return $stmt -> fetch();
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
	}	
	
	static public function mdlShowDecedentList($saleid){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM interment WHERE saleid = '$saleid' ORDER BY id");
		$stmt -> execute();
		return $stmt -> fetchAll();
	}
}