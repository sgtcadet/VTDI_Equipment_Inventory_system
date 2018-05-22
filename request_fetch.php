<?php

//request_fetch.php

include('database_connection.php');

include('function.php');

$query = '';

$output = array();

$query .= "
	SELECT * FROM inventory_request WHERE
";

if($_SESSION['type'] == 'user')
{
	//$query .= 'user_id = "'.$_SESSION["user_id"].'" AND ';
  $query .= 'user_id = "'.$_SESSION["user_id"].'";';
}
if($_SESSION['type'] == 'master')
{
  $query = "
  SELECT * FROM inventory_request;
  ";
}
/*else
{
  $query .= 'user_id = "'.$_SESSION["user_id"].'" ;';
}*/
/*
if(isset($_POST["search"]["value"]))
{
	$query .= '(inventory_request_id LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR inventory_request_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR inventory_request_total LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR inventory_request_status LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR inventory_request_date LIKE "%'.$_POST["search"]["value"].'%") ';
}
*/
/****************************************************
*         Find out what this is doing
*****************************************************/
/*
if(isset($_POST["request"]))
{
	$query .= 'ORDER BY '.$_POST['request']['0']['column'].' '.$_POST['request']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY inventory_request_id DESC ';
}*/
/*

if($_POST["length"] != -1)
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
  /*
	$payment_status = '';

	if($row['payment_status'] == 'cash')
	{
		$payment_status = '<span class="label label-primary">Cash</span>';
	}
	else
	{
		$payment_status = '<span class="label label-warning">Credit</span>';
	}
  */
	$status = '';
	if($row['inventory_request_status'] == 'pending') //pending
	{
		//$status = '<span class="label label-success">Pending</span>';
    $status = '<span class="label label-warning">Pending</span>';
	}else if($row['inventory_request_status'] == 'approved')
  {
    $status = '<span class="label label-success">Approved</span>';
  }
	else
	{
		$status = '<span class="label label-danger">Closed</span>'; //resolved
	}
	$sub_array = array();
	$sub_array[] = $row['inventory_request_id'];               //Id
  $sub_array[] = get_user_name($connect, $row['user_id']);   //Requstee name
	$sub_array[] = $status;                                    //status of request
	$sub_array[] = $row['inventory_request_date'];             //date requested
  $sub_array[] = $row['inventory_request_location'];         //location

	/*
  if($_SESSION['type'] == 'master')
	{
		$sub_array[] = get_user_name($connect, $row['user_id']);
	}*/
	$sub_array[] = '<a href="view_request.php?pdf=1&request_id='.$row["inventory_request_id"].'" class="btn btn-info btn-xs">View PDF</a>';
	$sub_array[] = '<button type="button" name="update" id="'.$row["inventory_request_id"].'" class="btn btn-warning btn-xs update">Update</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["inventory_request_id"].'" class="btn btn-danger btn-xs delete" data-status="'.$row["inventory_request_status"].'">Change State</button>';
	$data[] = $sub_array;
}

function get_total_all_records($connect)
{
	$statement = $connect->prepare("SELECT * FROM inventory_request");
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
