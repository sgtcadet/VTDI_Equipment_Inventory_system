<?php
//profile.php

include('database_connection.php');

if(!isset($_SESSION['type']))
{
	header("location:login.php");
}

$query = "
SELECT * FROM user_details 
WHERE user_id = '".$_SESSION["user_id"]."'
";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$name = '';
$email = '';
$user_id = '';
foreach($result as $row)
{
	$name = $row['user_name'];
	$email = $row['user_email'];
}

include('header.php');

?>
<?php
	//TEMP
	//$conn = mysqli_connect("localhost","root","","appterz","8080");
	//if(isset($_POST['add_image']))
	if(isset($_POST['add_image']))
	{
		//echo "<script>alert('is set');</script>"; DEBUG BLOCK
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
			echo "<script>alert('Profile Image Added');</script>";
			echo '<div class="alert alert-success">Image Added</div>';
		}
	}

?>
		<div class="panel panel-default">
			<div class="panel-heading">Edit Profile</div>
			<div class="panel-body">
				<div>
					<form method="post" id="add_profile_image_form" enctype="multipart/form-data">
						<!--Profile image upload section-->
						<div class="form-group">
							<label for="profieImage">Select a profile image</label>
							<input type="file" name="profieImage" id="profieImage">
							<input type="submit" class="btn btn-info" id="add_image" name="add_image" value="Add Image" style="padding: .5em; margin:.5em 0em;">
						</div>
					</form>
				</div>
				<?php
					if(userHasImage($connect,$_SESSION["user_id"])){
				?>
					<div class="user_profile_img" style="padding: .5em 0em; margin: .5em 0em;"><!--style="padding: .5em; margin: .5em 0em;"-->
						<p>Your current Image:</p>
						<?php echo getUserImage($connect,$_SESSION["user_id"]);
							//getUserImage($connect,$_SESSION["user_id"]);
						?>
					</div>
				<?php }else{ echo "<p>No Image</p>";}?>
				<form method="post" id="edit_profile_form"> <!--ADDED Multipart form date-->
					<span id="message"></span>
					<div class="form-group">
						<label for="user_name">Name</label>
						<input type="text" name="user_name" id="user_name" class="form-control" value="<?php echo $name; ?>" required />
					</div>
					<div class="form-group">
						<label for="user_email">Email</label>
						<input type="email" name="user_email" id="user_email" class="form-control" required value="<?php echo $email; ?>" />
					</div>
					<hr />
					<label>Leave Password blank if you do not want to change</label>
					<div class="form-group">
						<label for="user_new_password">New Password</label>
						<input type="password" name="user_new_password" id="user_new_password" class="form-control" />
					</div>
					<div class="form-group">
						<label for="user_re_enter_password">Re-enter Password</label>
						<input type="password" name="user_re_enter_password" id="user_re_enter_password" class="form-control" />
						<span id="error_password"></span>	
					</div>
					<!--Profile image upload section
					<div class="form-group">
						<label for="profieImage">Select a profile image</label>
						<input type="file" name="profieImage" id="profieImage">
					</div>-->
					<div class="form-group">
						<input type="submit" name="edit_prfile" id="edit_prfile" value="Edit" class="btn btn-info" />
					</div>
				</form>
			</div>
		</div>

<script>
$(document).ready(function(){
	$('#edit_profile_form').on('submit', function(event){
		event.preventDefault();
		if($('#user_new_password').val() != '')
		{
			if($('#user_new_password').val() != $('#user_re_enter_password').val())
			{
				$('#error_password').html('<label class="text-danger">Password Not Match</label>');
				return false;
			}
			else
			{
				$('#error_password').html('');
			}
		}
		$('#edit_prfile').attr('disabled', 'disabled');
		var form_data = $(this).serialize();
		$('#user_re_enter_password').attr('required',false);
		$.ajax({
			url:"edit_profile.php",
			method:"POST",
			data:form_data,
			success:function(data)
			{
				$('#edit_prfile').attr('disabled', false);
				$('#user_new_password').val('');
				$('#user_re_enter_password').val('');
				$('#message').html(data);
			}
		})
	});
});

$(document).ready(function(){

	$('#add_image').click(function(){
		var image_name = $('#profieImage').val();
		console.log(image_name);
		//CHECK IF AN IMAGE IS SET
		//TODO : figure out out to do this on the server side
		if(image_name == '')
		{
			alert("You need to select a image!");
			return false;
		}
		else{
			var extension = $('#profieImage').val().split('.').pop().toLowerCase();
			console.log(extension);
			//checking extension
			if(jQuery.inArray(extension,['gif','png','jpg','jpeg']) == -1){
				alert('invalid image file');
				$('#profieImage').val('');
				return false;
				console.log('not image');
			}
		}
	});
	/*
	$('#add_image').on('submit', function(event){
		event.preventDefault();
		//GET IMAGE VALUE
		var image_name = $('#profieImage').val();
		console.log(image_name);
		//CHECK IF AN IMAGE IS SET
		//TODO : figure out out to do this on the server side
		if(image_name != '')
		{
			var extension = $('#profieImage').val().split('.').pop().toLowerCase();
			console.log(extension);
			//checking extension
			if(jQuery.inArray(extension,['gif','png','jpg','jpeg']) == -1){
				alert('invalid image file');
				$('#profieImage').val('');
				return false;
				console.log('not image');
			}
			var img_form_data = $(this).serialize();
			console.log("data serialize" + img_form_data);
			var imgData = new FormData($(this)[0]);
			console.log("data N serialize" + imgData);
			$.ajax({
				url:"edit_profile.php",
				method:"POST",
				data:imgData,
				contentType: false,
          		processData: false,
				success:function(data){
					//alert("sucess");
					$('#message').html(data);
				}
			});
			
		}
	});
	*/
});
</script>
<?php


?>


			
