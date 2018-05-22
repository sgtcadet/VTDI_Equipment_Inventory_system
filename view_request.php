<?php

//view_order.php

if(isset($_GET["pdf"]) && isset($_GET['request_id']))
{
	require_once 'pdf.php';
	include('database_connection.php');
	include('function.php');
	if(!isset($_SESSION['type']))
	{
		header('location:login.php');
	}
	$output = '';
	$statement = $connect->prepare("
		SELECT * FROM inventory_request
		WHERE inventory_request_id = :inventory_request_id
		LIMIT 1
	");
	$statement->execute(
		array(
			':inventory_request_id'       =>  $_GET["request_id"]
		)
	);
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$output .= '
		<table width="100%" border="1" cellpadding="5" cellspacing="0">
			<tr>
				<td colspan="2" align="center" style="font-size:18px"><b>Request Form</b></td>
			</tr>
			<tr>
				<td colspan="2">
				<table width="100%" cellpadding="5">
					<tr>
						<td width="65%">
							Equipment Request,<br />
							<b>Inventory Request</b><br />
							Requestee Name : '.get_user_name($connect,$row["user_id"]).'<br />
							Request Location : '.$row["inventory_request_location"].'<br />
						</td>
						<td width="35%">
							Date:'.$row['inventory_request_date'].'<br />
						</td>
					</tr>
				</table>
				<br />
				<table width="100%" border="1" cellpadding="5" cellspacing="0">
					<tr>
						<th rowspan="2">Request No.</th>
						<th rowspan="2">Euipment</th>
						<th rowspan="2">Quantity</th>
					</tr>
					<tr>
						<th></th>
						<th></th>
					</tr>
		';
		$statement = $connect->prepare("
			SELECT * FROM inventory_request_equipment
			WHERE inventory_request_id = :inventory_request_id
		");
		$statement->execute(
			array(
				':inventory_request_id'       =>  $_GET["request_id"]
			)
		);
		$product_result = $statement->fetchAll();
		$count = 0;
		$total = 0;
		$total_actual_amount = 0;
		$total_tax_amount = 0;
		foreach($product_result as $sub_row)
		{
			$count = $count + 1;
			$product_data = fetch_equipment_details($sub_row['equipment_id'], $connect);
			//$actual_amount = $sub_row["quantity"] * $sub_row["price"];
			//$tax_amount = ($actual_amount * $sub_row["tax"])/100;
			//$total_product_amount = $actual_amount + $tax_amount;
			//$total_actual_amount = $total_actual_amount + $actual_amount;
			//$total_tax_amount = $total_tax_amount + $tax_amount;
			//$total = $total + $total_product_amount;
			$output .= '
				<tr>
					<td>'.$row['inventory_request_id'].'</td>
					<td>'.$product_data['equipment_name'].'</td>
					<td>'.$sub_row["quantity"].'</td>
				</tr>
			';
		}
		$output .= '
		<tr>
			
		</tr>
		';
		$output .= '
						</table>
						<br />
						<br />
						<br />
						<br />
						<br />
						<br />
						<p align="right">----------------------------------------<br />Receiver Signature</p>
						<br />
						<br />
						<br />
					</td>
				</tr>
			</table>
		';
	}
	$pdf = new Pdf();
	$file_name = 'Request-'.$row["inventory_request_id"].'.pdf';
	$pdf->loadHtml($output);
	$pdf->render();
	$pdf->stream($file_name, array("Attachment" => false));
}

?>
