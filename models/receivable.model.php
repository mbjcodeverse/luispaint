<?php
require_once 'connection.php';
class ModelReceivable{
	static public function mdlSaveReceivablePayment($data){
		$db = new Connection();
		$pdo = $db->connect();
        try{
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            $payment_id = $pdo->prepare("SELECT CONCAT('AR', LPAD((count(id)+1),8,'0')) as gen_id FROM receivable FOR UPDATE");

            $payment_id->execute();
		    $paymentid = $payment_id -> fetchAll(PDO::FETCH_ASSOC);
		    $paystatus = "Paid";

			$resetted = 'F';
			$uploaded = 'F';
			$paycode = $paymentid[0]['gen_id'];
			$stmt = $pdo->prepare("INSERT INTO receivable(paynum, paydate, paymode, checkdesc, bankcode, checknum, checkdate, amount, customercode, postedby, postdate, paystatus, paymentlist, resetted, uploaded) VALUES (:paynum, :paydate, :paymode, :checkdesc, :bankcode, :checknum, :checkdate, :amount, :customercode, :postedby, :postdate, :paystatus, :paymentlist, :resetted, :uploaded)");

			$stmt->bindParam(":paynum", $paymentid[0]['gen_id'], PDO::PARAM_STR);
			$stmt->bindParam(":paydate", $data["paydate"], PDO::PARAM_STR);
			$stmt->bindParam(":paymode", $data["paymode"], PDO::PARAM_STR);
			$stmt->bindParam(":checkdesc", $data["checkdesc"], PDO::PARAM_STR);
			$stmt->bindParam(":bankcode", $data["bankcode"], PDO::PARAM_STR);
			$stmt->bindParam(":checknum", $data["checknum"], PDO::PARAM_STR);
			$stmt->bindParam(":checkdate", $data["checkdate"], PDO::PARAM_STR);
			$stmt->bindParam(":amount", $data["amount"], PDO::PARAM_STR);
			$stmt->bindParam(":customercode", $data["customercode"], PDO::PARAM_STR);
			$stmt->bindParam(":postedby", $data["postedby"], PDO::PARAM_STR);
			$stmt->bindParam(":postdate", $data["postdate"], PDO::PARAM_STR);
			$stmt->bindParam(":paystatus", $paystatus, PDO::PARAM_STR);
			$stmt->bindParam(":paymentlist", $data["paymentlist"], PDO::PARAM_STR);
			$stmt->bindParam(":resetted", $resetted, PDO::PARAM_STR);
            $stmt->bindParam(":uploaded", $uploaded, PDO::PARAM_STR);
			$stmt->execute();

			$payment_list = json_decode($data["paymentlist"]);
			foreach($payment_list as $payment){
				$p = $pdo->prepare("INSERT INTO receivableitems(paynum, invno, amount, uploaded) VALUES (:paynum, :invno, :amount, :uploaded)");
				$p->bindParam(":paynum", $paymentid[0]['gen_id'], PDO::PARAM_STR);
				$p->bindParam(":invno", $payment->invno, PDO::PARAM_STR);
				$p->bindParam(":amount", $payment->amount, PDO::PARAM_STR);
				$p->bindParam(":uploaded", $uploaded, PDO::PARAM_STR);
				$p->execute();
			}

		    $pdo->commit();
		    return $paycode;
		}catch (Exception $e){
			$pdo->rollBack();
			return "error";
		}	
		$pdo = null;	
		$stmt = null;
	}

	static public function mdlShowReceivableList(){	
		$stmt = (new Connection)->connect()->prepare("SELECT customercode, name FROM customer");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

	static public function mdlShowReceivablePendingChecks($customercode){
		$stmt = (new Connection)->connect()->prepare("SELECT a.paydate,a.paynum,b.bankname,a.checkdate,a.checknum,a.amount FROM receivable AS a INNER JOIN bank AS b ON (a.bankcode = b.bankcode) INNER JOIN customer AS c ON (a.customercode = c.customercode) WHERE (a.customercode = '$customercode') AND (a.checkdesc = 'Post-dated')");

		$stmt -> execute();
			return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;		
	}

	// static public function mdlShowCustomerReceivableList($customercode) {
	// 	$stmt = null;
	// 	$connection = null;
	// 	try {
	// 		$connection = (new Connection)->connect();
	// 		$stmt = $connection->prepare("SELECT a.sdate, a.receiptnum, a.invno, a.salemode,
	// 		                                            a.netamount, a.customercode,
	// 											        SUM(IFNULL(d.amount, 0.00)) AS total_paid 
	// 												FROM sales AS a
	// 												LEFT JOIN receivableitems AS d ON (a.invno = d.invno)
	// 												LEFT JOIN receivable AS e ON (d.paynum = e.paynum)
	// 												WHERE (a.status = 'Sold') 
	// 													AND (a.customercode = :customercode)
	// 													AND ((e.paynum IS NULL) OR (e.paystatus = 'Paid'))
	// 												GROUP BY a.invno 
	// 												HAVING (a.netamount != total_paid)
	// 												ORDER BY a.sdate, a.invno");
			
	// 		$stmt->bindParam(':customercode', $customercode, PDO::PARAM_STR);
			
	// 		$stmt->execute();
	// 		return $stmt->fetchAll();
	// 	} catch (PDOException $e) {
	// 		echo "Error: " . $e->getMessage();
	// 		return [];
	// 	} finally {
	// 		// Ensure the statement and connection are properly closed
	// 		if ($stmt !== null) {
	// 			$stmt->closeCursor(); // Close the cursor to free up resources
	// 			$stmt = null;          // Set to null explicitly
	// 		}
	// 		if ($connection !== null) {
	// 			$connection = null;    // Close the database connection explicitly
	// 		}
	// 	}
	// }

	static public function mdlShowCustomerReceivableList($customercode) {
		$stmt = null;
		$connection = null;
		try {
			$connection = (new Connection)->connect();
			$stmt = $connection->prepare("SELECT a.sdate, a.receiptnum, a.invno, a.salemode,a.amount,a.discount,
			                                            a.netamount, a.customercode,
					SUM(
						CASE 
							WHEN e.paymode = 'Check' AND e.checkdesc = 'Post-dated'
							THEN d.amount 
							ELSE 0.00 
						END
					) AS pending_amount,
					SUM(
						CASE 
							WHEN (e.paymode = 'Cash' OR (e.paymode = 'Check' AND e.checkdesc = 'On-date'))
							THEN d.amount
							ELSE 0.00
						END
					) AS posted_amount,

					SUM(IFNULL(d.amount, 0.00)) AS total_paid 
					FROM sales AS a
					LEFT JOIN receivableitems AS d ON (a.invno = d.invno)
					LEFT JOIN receivable AS e ON (d.paynum = e.paynum)
					WHERE (a.status = 'Sold') 
						AND (a.customercode = :customercode)
						AND ((e.paynum IS NULL) OR (e.paystatus = 'Paid'))
					GROUP BY a.invno 
					HAVING (a.netamount != total_paid)
					ORDER BY a.sdate, a.receiptnum");
			
			$stmt->bindParam(':customercode', $customercode, PDO::PARAM_STR);
			
			$stmt->execute();
			return $stmt->fetchAll();
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
			return [];
		} finally {
			// Ensure the statement and connection are properly closed
			if ($stmt !== null) {
				$stmt->closeCursor(); // Close the cursor to free up resources
				$stmt = null;          // Set to null explicitly
			}
			if ($connection !== null) {
				$connection = null;    // Close the database connection explicitly
			}
		}
	}
	

    // Total POSTED and PENDING Receivable Payment - per Customer Delivery
	static public function mdlGetTotalPostedPendingPayment($invno){
		$stmt = (new Connection)->connect()->prepare("
		 SELECT 'Posted' AS pay_type,SUM(ifnull(c.amount,0.00)) AS total_amount
			FROM sales AS b
			INNER JOIN receivableitems AS c ON (b.invno = c.invno)
			INNER JOIN receivable AS d ON (c.paynum = d.paynum)
			WHERE (b.status = 'Sold') AND (b.invno = '$invno') AND (d.paystatus = 'Paid') AND ((d.paymode = 'Cash') OR ((d.paymode = 'Check') AND (d.checkdesc = 'On-date')))
           UNION ALL
         SELECT 'Pending' AS pay_type,SUM(ifnull(c.amount,0.00)) AS total_amount
            FROM sales AS b
            INNER JOIN receivableitems AS c ON (b.invno = c.invno)
            INNER JOIN receivable AS d ON (c.paynum = d.paynum)
            WHERE (b.status = 'Sold') AND (b.invno = '$invno') AND (d.paystatus = 'Paid') AND (d.paymode = 'Check') AND (d.checkdesc = 'Post-dated')");

        $stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
	    $stmt = null;
	}

    // Receivable Reset Display - NOT YET Resetted
	static public function mdlReceivableReset($branchcode, $postedby){
		$stmt = (new Connection)->connect()->prepare("SELECT b.name,e.paydate,c.invno,e.paymode,e.checkdate,e.checknum,e.checkdesc,f.amount
													  FROM branch AS a INNER JOIN sales AS c ON (a.branchcode = c.branchcode)
																	   INNER JOIN customer AS b ON (b.customercode = c.customercode)
																	   INNER JOIN receivableitems AS f ON (f.invno = c.invno)
																	   INNER JOIN receivable AS e ON (e.paynum = f.paynum)
																	   WHERE (e.resetted = 'F') AND (e.paystatus = 'Paid') AND
																	         (e.branchcode = '$branchcode') AND (e.postedby = '$postedby')
																	   ORDER BY b.name");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}

    // Receivable Reset Display - NOT YET Resetted - Product Account ----------------- (PENDING)
	static public function mdlReceivableResetAccount($branchcode, $postedby){
		$stmt = (new Connection)->connect()->prepare("SELECT b.accountdesc,SUM(f.tamount) AS tamount
													  FROM prodaccount AS b INNER JOIN masterproducts AS a ON (b.accountcode = a.accountcode)
													                        INNER JOIN salesitems AS f ON (a.prodid = f.prodid)
																			INNER JOIN sales AS e ON (f.invno = e.invno)
																			WHERE (e.branchcode = '$branchcode') AND
																			(e.postedby = '$postedby') AND (e.resetted = 'F') AND
																			(e.status = 'Sold') AND (e.salemode = 'Credit')
																			GROUP BY b.accountdesc ORDER BY b.accountdesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
	
    // Receivable Reset PRINT
	static public function mdlPrintReceivableReset($resetcode){
		$stmt = (new Connection)->connect()->prepare("SELECT b.name,e.paydate,c.invno,e.paymode,e.checkdate,e.checknum,e.checkdesc,f.amount
													  FROM branch AS a INNER JOIN sales AS c ON (a.branchcode = c.branchcode)
																	   INNER JOIN customer AS b ON (b.customercode = c.customercode)
																	   INNER JOIN receivableitems AS f ON (f.invno = c.invno)
																	   INNER JOIN receivable AS e ON (e.paynum = f.paynum)
																	   WHERE (e.resetcode = '$resetcode') AND (e.paystatus = 'Paid')
																	   ORDER BY b.name");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}
	
    // Receivable Reset - PRINT - Product Account ----------------- (PENDING)
	static public function mdlPrintReceivableResetAccount($resetcode){
		$stmt = (new Connection)->connect()->prepare("SELECT b.accountdesc,SUM(f.tamount) AS tamount
													  FROM prodaccount AS b INNER JOIN masterproducts AS a ON (b.accountcode = a.accountcode)
													                        INNER JOIN salesitems AS f ON (a.prodid = f.prodid)
																			INNER JOIN sales AS e ON (f.invno = e.invno)
																			WHERE (e.resetcode = '$resetcode')
																			GROUP BY b.accountdesc ORDER BY b.accountdesc");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlShowReceivableReport($paydate, $customercode, $paymode, $reptype){
		if ($customercode != ''){
			$customer_code = " AND (c.customercode = '$customercode')";
		}else{
			$customer_code = "";
		}	

		if ($paymode != ''){
		    $pay_mode = " AND (e.paymode = '$paymode')";
		}else{
			$pay_mode = "";
		}		

		if(!empty($paydate)){
			$date = " AND (e.paydate <= '$paydate')";
		}else{
			$date = "";
		}					

		$whereClause = "WHERE (c.invno != '') AND (c.status = 'Sold')" . $pay_mode . $date . $customer_code;

		$whereClauseA = "WHERE (c.status = 'Sold') AND ((e.paydate <= '$paydate') OR ((e.paydate IS NULL) AND (c.sdate <= '$paydate')))" . $pay_mode . $customer_code;

		$whereClauseB = "WHERE (c.invno != '')" . $pay_mode . $customer_code . " AND (c.status = 'Sold') AND (e.paydate > '$paydate') AND (c.sdate <= '$paydate')";
        
	    if (($reptype == 1) || ($reptype == 2) || ($reptype == 3)){
			$stmt = (new Connection)->connect()->prepare("SELECT 'A] With/Without Link <= Paydate' as detail,b.customercode, b.name,c.sdate,c.invno,c.receiptnum,c.netamount,e.paydate,IFNULL(SUM(f.amount),0.00) as amount,c.netamount - IFNULL(SUM(f.amount),0.00) AS balance,FLOOR(DATEDIFF(CURDATE(),c.sdate)) AS age FROM customer AS b INNER JOIN sales AS c ON (b.customercode = c.customercode) LEFT JOIN receivableitems AS f ON (c.invno = f.invno) LEFT JOIN receivable AS e ON (e.paynum = f.paynum) $whereClauseA GROUP BY c.invno HAVING (balance > 0.00)
			UNION
			SELECT 'B] With Link > Paydate' as detail,b.customercode, b.name,c.sdate,c.invno,c.receiptnum,c.netamount,NULL as paydate,0.00 as amount,c.netamount AS balance,FLOOR(DATEDIFF(CURDATE(),c.sdate)) AS age FROM customer AS b INNER JOIN sales AS c ON (b.customercode = c.customercode) INNER JOIN receivableitems AS f ON (c.invno = f.invno) LEFT JOIN receivable AS e ON (e.paynum = f.paynum) $whereClauseB GROUP BY c.invno ORDER BY name,sdate,receiptnum,detail");	    	
	    } elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT c.id,c.sdate,c.invno, c.status,IFNULL(e.name,'') as name,IFNULL(f.brandname,'') as brandname,b.prodname,d.qty,d.price,SUM(d.tamount) as tamount FROM category as a INNER JOIN masterproducts as b ON (a.categorycode = b.categorycode) LEFT JOIN brand as f ON (b.brandcode = f.brandcode) INNER JOIN salesitems AS d ON (b.prodid = d.prodid) INNER JOIN sales as c ON (c.invno = d.invno) LEFT JOIN customer as e ON (c.customercode = e.customercode) $whereClause GROUP BY c.id,b.prodname WITH ROLLUP");
		}

		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlCustomerTotalReceivable($customercode){
		$stmt = (new Connection)->connect()->prepare("SELECT 'A] With/Without Link <= Paydate' as detail,b.customercode, b.name, b.creditlimit,c.sdate,c.invno,c.netamount,e.paydate,IFNULL(SUM(f.amount),0.00) as amount,c.netamount - IFNULL(SUM(f.amount),0.00) AS balance,FLOOR(DATEDIFF(CURDATE(),c.sdate)) AS age FROM customer AS b INNER JOIN sales AS c ON (b.customercode = c.customercode) LEFT JOIN receivableitems AS f ON (c.invno = f.invno) LEFT JOIN receivable AS e ON (e.paynum = f.paynum) WHERE (c.salemode = 'Credit') AND (c.status = 'Sold') AND ((e.paydate <= CURDATE()) OR ((e.paydate IS NULL) AND (c.sdate <= CURDATE()))) AND (c.customercode = '$customercode') GROUP BY c.invno HAVING (balance > 0.00)
			UNION
SELECT 'B] With Link > Paydate' as detail,b.customercode, b.name, b.creditlimit,c.sdate,c.invno,c.netamount,NULL as paydate,0.00 as amount,c.netamount AS balance,FLOOR(DATEDIFF(CURDATE(),c.sdate)) AS age FROM customer AS b INNER JOIN sales AS c ON (b.customercode = c.customercode) INNER JOIN receivableitems AS f ON (c.invno = f.invno) LEFT JOIN receivable AS e ON (e.paynum = f.paynum)WHERE (c.salemode = 'Credit') AND (c.customercode = '$customercode') AND (c.status = 'Sold') AND (e.paydate > CURDATE()) AND (c.sdate <= CURDATE())
 GROUP BY c.invno ORDER BY name,invno,detail");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	

	static public function mdlGetClientPayment($paynum){
		$stmt = (new Connection)->connect()->prepare("SELECT a.name,b.paydate,b.paymode,IFNULL(d.bankname,'') as bankname,b.checkdesc,b.checknum,b.checkdate,b.amount,b.particulars,b.postdate,b.maturedate,c.invno,e.sdate,e.receiptnum,c.amount as amount_posted FROM customer as a INNER JOIN receivable as b ON (a.customercode = b.customercode) LEFT JOIN bank as d ON (b.bankcode = d.bankcode) INNER JOIN receivableitems as c ON (b.paynum = c.paynum) INNER JOIN sales as e ON (c.invno = e.invno) WHERE b.paynum = '$paynum'");
		$stmt -> execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;
	}	
}