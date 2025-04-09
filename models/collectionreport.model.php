<?php
require_once 'connection.php';
class ModelCollection{
    static public function mdlShowCollectionReport($start_date, $end_date, $customercode, $paymode, $reptype){
		if ($customercode != ''){
			$customer_code = " AND (e.customercode = '$customercode')";
		}else{
			$customer_code = "";
		}	

		if ($paymode != ''){
		    $pay_mode = " AND (e.paymode = '$paymode')";
		}else{
			$pay_mode = "";
		}		

        if(!empty($end_date)){
			$dates = " AND (e.paydate BETWEEN '$start_date' AND '$end_date')";
		}else{
			$dates = "";
		}								

		$whereClause = "WHERE (e.paynum != '') AND (e.paystatus = 'Paid')" . $pay_mode . $dates . $customer_code;
        
	    if ($reptype == 1){
			$stmt = (new Connection)->connect()->prepare("SELECT b.name,SUM(e.amount) AS amount FROM customer AS b INNER JOIN receivable AS e ON (b.customercode = e.customercode) $whereClause GROUP BY b.name ORDER BY b.name");	    	
	    } elseif ($reptype == 2){
			$stmt = (new Connection)->connect()->prepare("SELECT b.customercode,b.name,e.paydate,e.paymode,SUM(e.amount) AS amount,e.checkdesc,e.checknum,e.checkdate,IFNULL(c.bankname,'') as bank FROM customer AS b INNER JOIN receivable AS e ON (b.customercode = e.customercode) LEFT JOIN bank AS c ON (e.bankcode = c.bankcode) $whereClause GROUP BY b.name,e.paydate WITH ROLLUP");
		} elseif ($reptype == 3){
			$stmt = (new Connection)->connect()->prepare("SELECT b.customercode,b.name,e.paydate,e.paynum,e.paymode,e.amount,e.checkdesc,e.checknum,e.checkdate,IFNULL(c.bankname,'') as bank,f.invno,f.amount AS posted_amount FROM customer AS b INNER JOIN receivable AS e ON (b.customercode = e.customercode) INNER JOIN receivableitems AS f ON (e.paynum = f.paynum) LEFT JOIN bank AS c ON (e.bankcode = c.bankcode) $whereClause ORDER BY b.name,e.paydate,e.paynum");
		} elseif ($reptype == 4){			
			if(!empty($end_date)){
				$date_counter = " AND (sdate BETWEEN '$start_date' AND '$end_date')";
				$date_credit = " AND (paydate BETWEEN '$start_date' AND '$end_date')";
			}else{
				$dates = "";
			}

			$whereClause_counter = "WHERE (salemode = 'Counter') AND (status != 'Void')" . $date_counter;
			$whereClause_credit = "WHERE (paystatus = 'Paid')" . $date_credit;

			$stmt = (new Connection)->connect()->prepare("SELECT sdate AS cdate,'Counter' AS salemode,'' AS paymode,'' AS checkdesc,SUM(netamount) AS amount FROM sales $whereClause_counter GROUP BY sdate
			UNION ALL
			SELECT paydate AS cdate,'Credit' AS salemode,paymode,checkdesc,SUM(amount) AS amount FROM receivable $whereClause_credit GROUP BY paydate,paymode,checkdesc
			ORDER BY cdate,salemode");
		} elseif ($reptype == 5){
			if ($customercode != ''){
				$customer_code = " AND (customercode = '$customercode')";
			}else{
				$customer_code = "";
			}
			
			if(!empty($end_date)){
				$date_counter = " AND (sdate BETWEEN '$start_date' AND '$end_date')";
				$date_credit = " AND (paydate BETWEEN '$start_date' AND '$end_date')";
			}else{
				$dates = "";
			}

			$whereClause_counter = "WHERE (salemode = 'Counter') AND (status != 'Void')" . $date_counter;
			$whereClause_credit = "WHERE (paystatus = 'Paid')" . $date_credit . $customer_code;
						
			$stmt = (new Connection)->connect()->prepare("SELECT sdate AS tdate,CONCAT(MONTHNAME(sdate),' ',YEAR(sdate)) AS cdate,'Counter' AS salemode,'' AS paymode,'' AS checkdesc,SUM(netamount) AS amount FROM sales $whereClause_counter GROUP BY YEAR(sdate),MONTH(sdate)
			UNION ALL
			SELECT paydate AS tdate,CONCAT(MONTHNAME(paydate),' ',YEAR(paydate)) AS cdate,'Credit' AS salemode,paymode,checkdesc,SUM(amount) AS amount FROM receivable $whereClause_credit GROUP BY YEAR(paydate),MONTH(paydate),paymode,checkdesc
			ORDER BY YEAR(tdate),MONTH(tdate),salemode");
		} elseif ($reptype == 6){
			// $branch = " AND (branchcode = '$branchcode')";

			if ($branchcode != ''){
			  $branch = " AND (branchcode = '$branchcode')";
			}else{
			  $branch = "";
			}

			if ($customercode != ''){
				$customer_code = " AND (customercode = '$customercode')";
			}else{
				$customer_code = "";
			}
			
			if(!empty($end_date)){
				$date_counter = " AND (sdate BETWEEN '$start_date' AND '$end_date')";
				$date_collection = " AND (paydate BETWEEN '$start_date' AND '$end_date')";
			}else{
				$dates = "";
			}

			$whereClause_counter = "WHERE (status != 'Void')" . $branch . $date_counter . $customer_code;
			$whereClause_collection = "WHERE (paystatus = 'Paid')" . $branch . $date_collection . $customer_code;

			$stmt = (new Connection)->connect()->prepare("SELECT sdate AS cdate,salemode,'' AS paymode,'' AS checkdesc,SUM(netamount) AS amount FROM sales $whereClause_counter GROUP BY sdate,salemode
			UNION ALL
			SELECT paydate AS cdate,'Collection' AS salemode,paymode,checkdesc,SUM(amount) AS amount FROM receivable $whereClause_collection GROUP BY paydate,paymode,checkdesc
			ORDER BY cdate,salemode");
		} elseif ($reptype == 7){
			// $branch = " AND (b.branchcode = '$branchcode')";
			if ($branchcode != ''){
			  $branch = " AND (b.branchcode = '$branchcode')";
			}else{
			  $branch = "";
			}

			if ($customercode != ''){
				$customer_code = " AND (b.customercode = '$customercode')";
			}else{
				$customer_code = "";
			}
			
			if(!empty($end_date)){
				$date_sales = " AND (b.sdate BETWEEN '$start_date' AND '$end_date')";
				$date_collection = " AND (b.paydate BETWEEN '$start_date' AND '$end_date')";
			}else{
				$dates = "";
			}

			$whereClause_sales = "WHERE (b.status != 'Void')" . $branch . $date_sales . $customer_code;
			$whereClause_collection = "WHERE (b.paystatus = 'Paid')" . $branch . $date_collection . $customer_code;

			$stmt = (new Connection)->connect()->prepare("SELECT a.bname AS branch_name,b.salemode,'' AS paymode,'' AS checkdesc,SUM(b.netamount) AS amount FROM branch AS a INNER JOIN sales AS b ON (a.branchcode = b.branchcode) $whereClause_sales GROUP BY a.bname,b.salemode
			UNION ALL
			SELECT a.bname AS branch_name,'Collection' AS salemode,b.paymode,b.checkdesc,SUM(b.amount) AS amount FROM branch AS a INNER JOIN receivable AS b ON (a.branchcode = b.branchcode) $whereClause_collection GROUP BY a.bname,b.paymode,b.checkdesc ORDER BY branch_name");
		} elseif ($reptype == 8){
			if ($branchcode != ''){
			  $branch = " AND (a.branchcode = '$branchcode')";
			}else{
			  $branch = "";
			}

			if ($customercode != ''){
				$customer_code = " AND (a.customercode = '$customercode')";
			}else{
				$customer_code = "";
			}
			
			if(!empty($end_date)){
				$date_counter = " AND (a.sdate BETWEEN '$start_date' AND '$end_date')";
				$date_collection = " AND (paydate BETWEEN '$start_date' AND '$end_date')";
			}else{
				$dates = "";
			}

			$whereClause_counter = "WHERE (a.status != 'Void')" . $branch . $date_counter . $customer_code;
			$whereClause_collection = "WHERE (a.paystatus = 'Paid')" . $branch . $date_collection . $customer_code;

			$stmt = (new Connection)->connect()->prepare("SELECT a.sdate AS cdate,a.salemode,c.vatdesc,'' AS paymode,'' AS checkdesc,SUM(b.tamount) AS amount FROM sales AS a INNER JOIN salesitems AS b ON (a.invno = b.invno) INNER JOIN masterproducts AS c ON (b.prodid = c.prodid) $whereClause_counter GROUP BY a.sdate,a.salemode,c.vatdesc
			UNION ALL
			SELECT a.paydate AS cdate,'Collection' AS salemode,'' AS vatdesc,a.paymode,a.checkdesc,SUM(a.amount) AS amount FROM receivable AS a $whereClause_collection GROUP BY a.paydate,a.paymode,a.checkdesc
			ORDER BY cdate,salemode,vatdesc");
		} elseif ($reptype == 9){
			if ($branchcode != ''){
			  $branch = " AND (b.branchcode = '$branchcode')";
			}else{
			  $branch = "";
			}

			if ($customercode != ''){
				$customer_code = " AND (b.customercode = '$customercode')";
			}else{
				$customer_code = "";
			}
			
			if(!empty($end_date)){
				$date_sales = " AND (b.sdate BETWEEN '$start_date' AND '$end_date')";
				$date_collection = " AND (b.paydate BETWEEN '$start_date' AND '$end_date')";
			}else{
				$dates = "";
			}

			$whereClause_sales = "WHERE (b.status != 'Void')" . $branch . $date_sales . $customer_code;
			$whereClause_collection = "WHERE (b.paystatus = 'Paid')" . $branch . $date_collection . $customer_code;

			$stmt = (new Connection)->connect()->prepare("SELECT a.bname AS branch_name,b.salemode,d.vatdesc,'' AS paymode,'' AS checkdesc,SUM(c.tamount) AS amount FROM branch AS a INNER JOIN sales AS b ON (a.branchcode = b.branchcode) INNER JOIN salesitems AS c ON (b.invno = c.invno) INNER JOIN masterproducts AS d ON (c.prodid = d.prodid) $whereClause_sales GROUP BY a.bname,b.salemode,d.vatdesc
			UNION ALL
			SELECT a.bname AS branch_name,'Collection' AS salemode,'' AS vatdesc,b.paymode,b.checkdesc,SUM(b.amount) AS amount FROM branch AS a INNER JOIN receivable AS b ON (a.branchcode = b.branchcode) $whereClause_collection GROUP BY a.bname,b.paymode,b.checkdesc ORDER BY branch_name;");
		} 

		$stmt -> execute();
		return $stmt -> fetchAll();
	}
}