<?php


/**
 * FUNCTIONS ADDED BY: Howard Grant
 */

 /**
  * Function to provide a list of equipment
  * @returns list of HTML Mark 'option'
  * @params $connect
  */
 function fill_equipment_list($connect)
 {
 	$query = "
 	SELECT * FROM equipment
 	WHERE equipment_status = 'available'
 	ORDER BY equipment_name ASC
 	";
 	$statement = $connect->prepare($query);
 	$statement->execute();
 	$result = $statement->fetchAll();
 	$output = '';
 	foreach($result as $row)
 	{
 		$output .= '<option value="'.$row["equipment_id"].'">'.$row["equipment_name"].'</option>';
 	}
 	return $output;
 }
 /*
 function fetch_equipment_details($product_id, $connect)
 {
 	$query = "
 	SELECT * FROM equipment
 	WHERE equipment_id = '".$product_id."'";
 	$statement = $connect->prepare($query);
 	$statement->execute();
 	$result = $statement->fetchAll();
 	foreach($result as $row)
 	{
 		$output['equipment_name'] = $row["equipment_name"];
 		$output['equipment_quantity'] = $row["equipment_quantity"];
 	}
 	return $output;
 }*/
 function available_equipment_quantity($connect, $product_id)
 {
 	$product_data = fetch_equipment_details($product_id, $connect);
 	/*
	$query = "
 	SELECT 	inventory_order_product.quantity FROM inventory_order_product
 	INNER JOIN inventory_order ON inventory_order.inventory_order_id = inventory_order_product.inventory_order_id
 	WHERE inventory_order_product.product_id = '".$product_id."' AND
 	inventory_order.inventory_order_status = 'active'
 	";
	*/
	$query = "
 	SELECT * FROM equipment WHERE equipment_status = 'available'
 	";
 	$statement = $connect->prepare($query);
 	$statement->execute();
 	$result = $statement->fetchAll();
 	$total = 0;
 	foreach($result as $row)
 	{
 		$total = $total + $row['equipment_quantity'];
 	}
 	//$available_quantity = intval($product_data['equipment_quantity']) - intval($total);
  $available_quantity = $total;
 	if($available_quantity == 0)
 	{
 		$update_query = "
 		UPDATE equipment SET
 		equipment_status = 'unavailable'
 		WHERE equipment_id = '".$product_id."'
 		";
 		$statement = $connect->prepare($update_query);
 		$statement->execute();
 	}
 	return $available_quantity;
 }
 ?>
