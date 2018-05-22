<?php

//equipment_action.php

include('database_connection.php');

//include('function.php');
include('equipment_function.php');


if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add')
	{
    $query = "
		INSERT INTO equipment (equipment_name,equipment_quantity,equipment_description,equipment_status,equipment_enter_by)
		VALUES(:equipment_name, :equipment_quantity, :equipment_description, :equipment_status, :equipment_enter_by)
		 ";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':equipment_name'			=>	$_POST['equipment_name'],
				':equipment_quantity'				=>	$_POST['equipment_quantity'],
				':equipment_description'			=>	$_POST['equipment_description'],
				':equipment_status'	=>	'available',
				':equipment_enter_by'		=>	$_SESSION["user_id"]
			)
		);
		//$result = $statement->fetchAll();
		//if(isset($result))
		/**
		 * ADDED THIS BLOCK TO TEST IF A ROW WAS AFFFECTED WITH THE INSERT |Howard
		 */
		 $result = $statement->rowCount();
		if($result >= 1)
		{
			echo 'Equipment Added';
		}
	}

	if($_POST['btn_action'] == 'equipment_details')
	{
		$query = "
		SELECT * FROM equipment
		INNER JOIN user_details ON user_details.user_id = equipment.equipment_enter_by
		WHERE equipment.equipment_id = '".$_POST["equipment_id"]."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$output = '
		<div class="table-responsive">
			<table class="table table-boredered">
		';
		foreach($result as $row)
		{
			$status = '';
			if($row['equipment_status'] == 'available')
			{
				$status = '<span class="label label-success">Available</span>';
			}
			else
			{
				$status = '<span class="label label-danger">Unavailable</span>';
			}
			$output .= '
			<tr>
				<td>Equipment Name</td>
				<td>'.$row["equipment_name"].'</td>
			</tr>
			<tr>
				<td>Equipment Description</td>
				<td>'.$row["equipment_description"].'</td>
			</tr>
			<tr>
				<td>Available Quantity</td>
				<td>'.$row["equipment_quantity"].'</td>
			</tr>
			<tr>
				<td>Enter By</td>
				<td>'.$row["user_name"].'</td>
			</tr>
			<tr>
				<td>Status</td>
				<td>'.$status.'</td>
			</tr>
			';
		}
		$output .= '
			</table>
		</div>
		';
		echo $output;
	}

	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "
		SELECT * FROM equipment WHERE equipment_id = :equipment_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':equipment_id'	=>	$_POST["equipment_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['equipment_name'] = $row['equipment_name'];
			$output['equipment_description'] = $row['equipment_description'];
			$output['equipment_quantity'] = $row['equipment_quantity'];
		}
		echo json_encode($output);
	}

	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
		UPDATE equipment
		set
		equipment_name = :equipment_name,
		equipment_description = :equipment_description,
		equipment_quantity = :equipment_quantity
		WHERE equipment_id = :equipment_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':equipment_name'			=>	$_POST['equipment_name'],
				':equipment_description'	=>	$_POST['equipment_description'],
				':equipment_quantity'		=>	$_POST['equipment_quantity'],
				':equipment_id'			=>	$_POST['equipment_id']
			)
		);
		//$result = $statement->fetchAll();
		//if(isset($result))
		/**
		 * ADDED THIS BLOCK TO TEST IF A ROW WAS AFFFECTED WITH THE INSERT |Howard
		 */
		$result = $statement->rowCount();
		if($result >= 1)
		{
			echo 'Equipment Details Edited';
		}
	}

	if($_POST['btn_action'] == 'delete')
	{
		$status = 'available';
		if($_POST['status'] == 'available')
		{
			$status = 'unavailable';
		}
		$query = "
		UPDATE equipment
		SET equipment_status = :equipment_status
		WHERE equipment_id = :equipment_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':equipment_status'	=>	$status,
				':equipment_id'		=>	$_POST["equipment_id"]
			)
		);
		//$result = $statement->fetchAll();
		//if(isset($result))
		$result = $statement->rowCount();
		if($result >= 1)
		{
			echo 'equipment status change to ' . $status;
		}
	}

}


?>
