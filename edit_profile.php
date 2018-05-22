<?php

//edit_profile.php

include('database_connection.php');

if(isset($_POST['user_name']))
{
	if($_POST["user_new_password"] != '')
	{
		$query = "
		UPDATE user_details SET 
			user_name = '".$_POST["user_name"]."', 
			user_email = '".$_POST["user_email"]."', 
			user_password = '".password_hash($_POST["user_new_password"], PASSWORD_DEFAULT)."' 
			WHERE user_id = '".$_SESSION["user_id"]."'
		";
	}
	else
	{
		$query = "
		UPDATE user_details SET 
			user_name = '".$_POST["user_name"]."', 
			user_email = '".$_POST["user_email"]."'
			WHERE user_id = '".$_SESSION["user_id"]."'
		";
	}
	/*
	if(isset($_FILES['profileImage']))
	{
		$targetFile = 
	}
	*/
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	if(isset($result))
	{
		echo '<div class="alert alert-success">Profile Edited</div>';
	}
}

//if(isset($_POST['add_image'])){
if(isset($_POST['profieImage'])){
	$file = addslashes(file_get_contents($_FILES['profieImage']['tmp_name']));
	$query = "
		UPDATE user_details SET
		image = '".$file."'
		WHERE user_id = '".$_SESSION["user_id"]."';
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->rowCount();
	if($result >= 1){
		//echo "<script>alert('Image Added in DB');</script>";
		echo '<div class="alert alert-success">Image Added</div>';
	}
}
?>