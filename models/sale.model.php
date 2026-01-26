<?php
date_default_timezone_set('Asia/Manila');
require_once "connection.php";
class ModelSale{
	static public function mdlAddSale($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $sale_id = $pdo->prepare("SELECT invno FROM sales ORDER BY id DESC LIMIT 1");

            $sale_id->execute();
		    $saleid = $sale_id -> fetchAll(PDO::FETCH_ASSOC);

		    $sale_number = $saleid[0]['invno'];
		    $sequence_code = strval(intval(substr($sale_number,-9)) + 1);
		    $salecode = str_repeat("0",9 - strlen($sequence_code)) . $sequence_code;

            $currentDateTime = date('Y-m-d H:i:s');

			$stmt = $pdo->prepare("INSERT INTO sales(invno, receiptnum, sdate, salemode, customercode, status, postedby, amount, discount, netamount, remarks, postinfo) VALUES (:invno, :receiptnum, :sdate, :salemode, :customercode, :status, :postedby, :amount, :discount, :netamount, :remarks, :postinfo)");

			$stmt->bindParam(":invno", $salecode, PDO::PARAM_STR);
			$stmt->bindParam(":receiptnum", $data["receiptnum"], PDO::PARAM_STR);
			$stmt->bindParam(":sdate", $data["sdate"], PDO::PARAM_STR);
			$stmt->bindParam(":salemode", $data["salemode"], PDO::PARAM_STR);	
			$stmt->bindParam(":customercode", $data["customercode"], PDO::PARAM_STR);
			$stmt->bindParam(":status", $data["status"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":amount", $data["amount"], PDO::PARAM_STR);
			$stmt->bindParam(":discount", $data["discount"], PDO::PARAM_STR);
            $stmt->bindParam(":netamount", $data["netamount"], PDO::PARAM_STR);
            $stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":postinfo", $currentDateTime, PDO::PARAM_STR);
			$stmt->execute();		

		    $pdo->commit();
		    return $salecode;
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}finally {
            if ($stmt !== null) {
                $stmt = null; // Free the prepared statement
            }
            if ($pdo !== null) {
                $pdo = null;  // Close the PDO connection
            }
        }	      
	}

	static public function mdlEditSale($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $currentDateTime = date('Y-m-d H:i:s');

			$stmt = $pdo->prepare("UPDATE sales
                                          SET receiptnum = :receiptnum,
                                              sdate = :sdate,
                                              salemode = :salemode,
                                              customercode = :customercode,
                                              status = :status,
                                              editby = :editby,
											  amount = :amount,
											  discount = :discount,
                                              netamount = :netamount,
                                              remarks = :remarks,
                                              editinfo = :editinfo
                                           WHERE invno = :invno");

            $stmt->bindParam(":invno", $data["invno"], PDO::PARAM_STR);
			$stmt->bindParam(":receiptnum", $data["receiptnum"], PDO::PARAM_STR);
			$stmt->bindParam(":sdate", $data["sdate"], PDO::PARAM_STR);
			$stmt->bindParam(":salemode", $data["salemode"], PDO::PARAM_STR);	
			$stmt->bindParam(":customercode", $data["customercode"], PDO::PARAM_STR);
			$stmt->bindParam(":status", $data["status"], PDO::PARAM_STR);
			$stmt->bindParam(":editby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":amount", $data["amount"], PDO::PARAM_STR);
			$stmt->bindParam(":discount", $data["discount"], PDO::PARAM_STR);
            $stmt->bindParam(":netamount", $data["netamount"], PDO::PARAM_STR);
            $stmt->bindParam(":remarks", $data["remarks"], PDO::PARAM_STR);
            $stmt->bindParam(":editinfo", $currentDateTime, PDO::PARAM_STR);
			$stmt->execute();		

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}finally {
            if ($stmt !== null) {
                $stmt = null; // Free the prepared statement
            }
            if ($pdo !== null) {
                $pdo = null;  // Close the PDO connection
            }
        }	      
	}    

    static public function mdlSalesInfoList($customercode, $salemode, $start_date, $end_date, $status, $paystatus){
		if ($customercode != ''){
			$customer_code = " AND (a.customercode = '$customercode')";
		}else{
			$customer_code = "";
		}

		if ($salemode != ''){
            $salemode = " AND (b.salemode = '$salemode')";
		}else{
			$salemode = "";
		}

		if(!empty($end_date)){
			$dates = " AND (b.sdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}	
        
        if ($status != ''){
            $status = " AND (b.status = '$status')";
		}else{
			$status = "";
		}

        if ($paystatus == '<All>'){
            $pay_status = '';
        }elseif ($paystatus == 'Paid'){
            $pay_status = 'HAVING balance = 0.00';
        }else{
            $pay_status = 'HAVING balance > 0.00';
        }

		$whereClause = "WHERE (b.status != 'Void')" . $customer_code . $salemode . $dates . $status;

		$stmt = (new Connection)->connect()->prepare("SELECT b.invno,b.sdate,b.receiptnum,b.salemode,a.customercode,a.name,b.netamount,
                                                      IFNULL(SUM(c.amount),0.00) AS paid,(b.netamount - IFNULL(SUM(c.amount),0.00)) AS balance
                                                      FROM customer AS a INNER JOIN sales AS b ON (a.customercode = b.customercode)
                                                      LEFT JOIN receivableitems AS c ON (b.invno = c.invno) $whereClause GROUP BY b.invno $pay_status
                                                      ORDER BY b.sdate");

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
	
	// Search Invoice
    static public function mdlSalesReport($reptype, $customercode, $salemode, $start_date, $end_date, $status, $paystatus){
		if ($customercode != ''){
			$customer_code = " AND (a.customercode = '$customercode')";
		}else{
			$customer_code = "";
		}

		if ($salemode != ''){
            $salemode = " AND (b.salemode = '$salemode')";
		}else{
			$salemode = "";
		}

		if(!empty($end_date)){
			$dates = " AND (b.sdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}	
        
        if ($status != ''){
            $status = " AND (b.status = '$status')";
		}else{
			$status = "";
		}

        if ($paystatus == '<All>'){
            $pay_status = '';
        }elseif ($paystatus == 'Paid'){
            $pay_status = 'HAVING balance = 0.00';
        }else{
            $pay_status = 'HAVING balance > 0.00';
        }

		$whereClause = "WHERE (b.status != 'Void')" . $customer_code . $salemode . $dates . $status;

		if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT b.invno,b.sdate,b.receiptnum,b.salemode,a.customercode,a.name,b.netamount,
														IFNULL(SUM(c.amount),0.00) AS paid,(b.netamount - IFNULL(SUM(c.amount),0.00)) AS balance
														FROM customer AS a INNER JOIN sales AS b ON (a.customercode = b.customercode)
														LEFT JOIN receivableitems AS c ON (b.invno = c.invno) $whereClause GROUP BY b.invno $pay_status
														ORDER BY b.sdate,a.name");
		}

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	
    
    static public function mdlShowSale($invno){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM sales WHERE invno = '$invno'");
		$stmt -> execute();
		return $stmt -> fetch();
	}

	static public function mdlPrintSales($reptype, $customercode, $salemode, $status, $start_date, $end_date){
		if ($customercode != ''){
			$customer_code = " AND (a.customercode = '$customercode')";
		}else{
			$customer_code = "";
		}

		if ($salemode != ''){
            $salemode = " AND (b.salemode = '$salemode')";
		}else{
			$salemode = "";
		}

		if(!empty($end_date)){
			$dates = " AND (b.sdate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}	
        
        if ($status != ''){
            $status = " AND (b.status = '$status')";
		}else{
			$status = "";
		}

		$whereClause = "WHERE (b.status != 'Void')" . $customer_code . $salemode . $dates . $status;

		if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT b.invno,b.sdate,b.receiptnum,b.salemode,a.customercode,a.name,b.netamount FROM customer AS a INNER JOIN sales AS b ON (a.customercode = b.customercode) $whereClause ORDER BY b.sdate,a.name");
		}

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlGetReceiptNumber($receiptnum){
		$stmt = (new Connection)->connect()->prepare("SELECT * FROM sales WHERE receiptnum = '$receiptnum'");
		$stmt -> execute();
		return $stmt -> fetch();
	}

	
	static public function mdlVoidSale($invno){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

			$inv_no = $invno;
			$sale_status = 'Void';

			$stmt = $pdo->prepare("UPDATE sales
                                          SET status = :status
                                          WHERE invno = :invno");

            $stmt->bindParam(":invno", $inv_no, PDO::PARAM_STR);
			$stmt->bindParam(":status", $sale_status, PDO::PARAM_STR);
			$stmt->execute();		

		    $pdo->commit();
		    return "ok";
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}finally {
            if ($stmt !== null) {
                $stmt = null; 
            }
            if ($pdo !== null) {
                $pdo = null;
            }
        }	      
	}    	
}


