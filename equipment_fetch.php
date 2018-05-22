<?php

//product_fetch.php

include('database_connection.php');
//include('equipment_function.php');
include('function.php');

$query = '';

$output = array();
/*$query .= "
	SELECT * FROM product
INNER JOIN brand ON brand.brand_id = product.brand_id
INNER JOIN category ON category.category_id = product.category_id
INNER JOIN user_details ON user_details.user_id = product.product_enter_by
";*/
$query .= "
	SELECT * FROM equipment
INNER JOIN user_details ON user_details.user_id = equipment.equipment_enter_by
";

/**TO Do: motify this search so that it searches for equipments**/
/*
if(isset($_POST["search"]["value"]))
{
	$query .= 'WHERE brand.brand_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR category.category_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR product.product_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR product.product_quantity LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR user_details.user_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR product.product_id LIKE "%'.$_POST["search"]["value"].'%" ';
}
*/
/*
if(isset($_POST['order']))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY product_id DESC ';
}

if($_POST['length'] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
*/
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
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
	$sub_array = array();
	$sub_array[] = $row['equipment_id'];
	$sub_array[] = $row['equipment_name'];
  $sub_array[] = $row['equipment_quantity'];
	$sub_array[] = $row['user_name'];
	$sub_array[] = $status;
	$sub_array[] = '<button type="button" name="view" id="'.$row["equipment_id"].'" class="btn btn-info btn-xs view">View</button>';
	$sub_array[] = '<button type="button" name="update" id="'.$row["equipment_id"].'" class="btn btn-warning btn-xs update">Update</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["equipment_id"].'" class="btn btn-danger btn-xs delete" data-status="'.$row["equipment_status"].'">Change State</button>';
	$data[] = $sub_array;
}

function get_total_all_records($connect)
{
	$statement = $connect->prepare('SELECT * FROM equipment');
	$statement->execute();
	return $statement->rowCount();
}

$output = array(
	"draw"    			=> 	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect),
	"data"    			=> 	$data
);

echo json_encode($output);

?>
