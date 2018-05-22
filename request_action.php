<?php

//request_action.php

include('database_connection.php');

include('function.php');
//include('equipment_function.php');

if(isset($_POST['btn_action']))
{
  /*WORKING*/
	if($_POST['btn_action'] == 'Add')
	{
		$query = "
		INSERT INTO inventory_request (user_id, inventory_request_date, inventory_request_from_time, inventory_request_to_time, inventory_request_status, inventory_request_location, comment, inventory_request_created_date)
		VALUES ( :user_id, :inventory_request_date, :inventory_request_from_time, :inventory_request_to_time, :inventory_request_status, :inventory_request_location, :comment, :inventory_request_created_date)
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':user_id'						=>	$_SESSION["user_id"],
				':inventory_request_date'		=>	$_POST['inventory_request_date'],
				':inventory_request_from_time'			=>	$_POST['inventory_request_from_time'],
				':inventory_request_to_time'		=>	$_POST['inventory_request_to_time'],
				':inventory_request_status'		=>	'pending',
        ':inventory_request_location'		=>	$_POST['inventory_request_location'],
        ':comment'		=>	$_POST['comment'],
				':inventory_request_created_date'	=>	date("Y-m-d")
			)
		);
		//$result = $statement->fetchAll();
    $result = $statement->rowCount();

		$statement = $connect->query("SELECT LAST_INSERT_ID()");
		$inventory_request_id = $statement->fetchColumn();

		if(isset($inventory_request_id))
		{
			//$total_amount = 0;
			for($count = 0; $count<count($_POST["equipment_id"]); $count++)
			{
				//$equipment_details = fetch_equipment_details($_POST["equipment_id"][$count], $connect);
        $equipment_details = fetch_equipment_details($_POST["equipment_id"][$count], $connect);
				$sub_query = "
				INSERT INTO inventory_request_equipment (inventory_request_id, equipment_id, quantity) VALUES (:inventory_request_id, :equipment_id, :quantity)
				";
				$statement = $connect->prepare($sub_query);
				$statement->execute(
					array(
						':inventory_request_id'	=>	$inventory_request_id,
						':equipment_id'			=>	$_POST["equipment_id"][$count],
						':quantity'				=>	$_POST["quantity"][$count]
					)
				);
				//$base_price = $equipment_details['price'] * $_POST["quantity"][$count];
				//$tax = ($base_price/100)*$equipment_details['tax'];
				//$total_amount = $total_amount + ($base_price + $tax);
			}
      /*
      $result = $statement->fetchAll();
			if(isset($result))
			{
        echo 'Request Created...';
      }*/
      $result = $statement->rowCount();
   		if($result >= 1)
   		{
   			echo 'Request Created';
   		}
      /*
			$update_query = "
			UPDATE inventory_request
			SET inventory_request_total = '".$total_amount."'
			WHERE inventory_request_id = '".$inventory_request_id."'
			";
			$statement = $connect->prepare($update_query);
			$statement->execute();
			$result = $statement->fetchAll();
			if(isset($result))
			{
				echo 'request Created...';
				echo '<br />';
				echo $total_amount;
				echo '<br />';
				echo $inventory_request_id;
			}*/
		}
	}

	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "
		SELECT * FROM inventory_request WHERE inventory_request_id = :inventory_request_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':inventory_request_id'	=>	$_POST["inventory_request_id"]
			)
		);
		$result = $statement->fetchAll();
		$output = array();
		foreach($result as $row)
		{
			$output['user_id'] = $row['user_id'];
			$output['inventory_request_date'] = $row['inventory_request_date'];
      $output['inventory_request_from_time'] = $row['inventory_request_from_time'];
      $output['inventory_request_to_time'] = $row['inventory_request_to_time'];
      $output['inventory_request_status'] = $row['inventory_request_status'];
			$output['inventory_request_location'] = $row['inventory_request_location'];
			$output['comment'] = $row['comment'];
		}
		$sub_query = "
		SELECT * FROM inventory_request_equipment
		WHERE inventory_request_id = '".$_POST["inventory_request_id"]."'
		";
		$statement = $connect->prepare($sub_query);
		$statement->execute();
		$sub_result = $statement->fetchAll();
		$equipment_details = '';
		$count = '';
		foreach($sub_result as $sub_row)
		{
			$equipment_details .= '
			<script>
			$(document).ready(function(){
				$("#equipment_id'.$count.'").selectpicker("val", '.$sub_row["equipment_id"].');
				$(".selectpicker").selectpicker();
			});
			</script>
			<span id="row'.$count.'">
				<div class="row">
					<div class="col-md-8">
						<select name="equipment_id[]" id="equipment_id'.$count.'" class="form-control selectpicker" data-live-search="true" required>
							'.fill_equipment_list($connect).'
						</select>
						<input type="hidden" name="hidden_equipment_id[]" id="hidden_equipment_id'.$count.'" value="'.$sub_row["equipment_id"].'" />
					</div>
					<div class="col-md-3">
						<input type="text" name="quantity[]" class="form-control" value="'.$sub_row["quantity"].'" required />
					</div>
					<div class="col-md-1">
			';

			if($count == '')
			{
				$equipment_details .= '<button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs">+</button>';
			}
			else
			{
				$equipment_details .= '<button type="button" name="remove" id="'.$count.'" class="btn btn-danger btn-xs remove">-</button>';
			}
			$equipment_details .= '
						</div>
					</div>
				</div><br />
			</span>
			';
			$count = $count + 1;
		}
		$output['equipment_details'] = $equipment_details;
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		$delete_query = "
		DELETE FROM inventory_request_equipment
		WHERE inventory_request_id = '".$_POST["inventory_request_id"]."'
		";
		$statement = $connect->prepare($delete_query);
		$statement->execute();
		$delete_result = $statement->fetchAll();
		if(isset($delete_result))
		{
			$total_amount = 0;
			for($count = 0; $count < count($_POST["equipment_id"]); $count++)
			{
				$equipment_details = fetch_equipment_details($_POST["equipment_id"][$count], $connect);
				$sub_query = "
				INSERT INTO inventory_request_equipment (inventory_request_id, equipment_id, quantity) VALUES (:inventory_request_id, :equipment_id, :quantity)
				";
				$statement = $connect->prepare($sub_query);
				$statement->execute(
					array(
						':inventory_request_id'	=>	$_POST["inventory_request_id"],
						':equipment_id'			=>	$_POST["equipment_id"][$count],
						':quantity'				=>	$_POST["quantity"][$count]
					)
				);
				//$base_price = $equipment_details['price'] * $_POST["quantity"][$count];
				//$tax = ($base_price/100)*$equipment_details['tax'];
				//$total_amount = $total_amount + ($base_price + $tax);
			}
			$update_query = "
			UPDATE inventory_request
			SET user_id = :user_id,
			inventory_request_date = :inventory_request_date,
			inventory_request_from_time = :inventory_request_from_time,
			inventory_request_to_time = :inventory_request_to_time,
			inventory_request_status = :inventory_request_status,
      inventory_request_location = :inventory_request_location,
      comment = :comment
			WHERE inventory_request_id = :inventory_request_id
			";
			$statement = $connect->prepare($update_query);
			$statement->execute(
				array(
					':user_id'			=>	$_POST["user_id"],
					':inventory_request_date'			=>	$_POST["inventory_request_date"],
					':inventory_request_from_time'		=>	$_POST["inventory_request_from_time"],
					':inventory_request_to_time'		=>	$_post['inventory_request_to_time'],
					':inventory_request_status'				=>	$_POST["inventory_request_status"],
          'inventory_request_location'  	=>	$_POST["inventory_request_location"],
          'comment'  	=>	$_POST["comment"],
					':inventory_request_id'			=>	$_POST["inventory_request_id"]
				)
			);
			//$result = $statement->fetchAll();
      $result = $statement->rowCount();
			if(!empty($result))
			{
				echo 'request Edited...';
			}
		}
	}

/*WORKING*/
	if($_POST['btn_action'] == 'delete')
	{
		$status = 'pending';
		//if($_POST['status'] == 'approved' || $_POST['status'] == 'pending' )
    if($_POST['status'] == 'pending')
    {
      $status = 'approved';
    }
    if($_POST['status'] == 'approved')
		{
			$status = 'closed';
		}
    if($_POST['status'] == 'close'){$status = 'pending';}

		$query = "
		UPDATE inventory_request
		SET inventory_request_status = :inventory_request_status
		WHERE inventory_request_id = :inventory_request_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':inventory_request_status'	=>	$status,
				':inventory_request_id'		=>	$_POST["inventory_request_id"]
			)
		);
		//$result = $statement->fetchAll();
    $result = $statement->rowCount();
		if(!empty($result))
		{
			echo 'request status change to ' . $status;
		}
	}
}

?>
