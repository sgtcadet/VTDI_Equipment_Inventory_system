<?php
//header.php
//include('database_connection.php');
include_once('function.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>VTDI Lab Tech's Inventory System</title>
		<script src="js/jquery-1.10.2.min.js"></script>
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<script src="js/jquery.dataTables.min.js"></script>
		<script src="js/dataTables.bootstrap.min.js"></script>
		<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
		<script src="js/bootstrap.min.js"></script>
		<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
-->
	</head>
	<?php 
	if($_SESSION['type'] == 'master')
	{
	?>
		<script type="text/javascript">
			$(document).ready(function(){
				var html = '';
				html += '<span class="badge">';
				html += '<?php fetch_pending_requests($connect);?>';
				html += '</span>';
			$('#request_link').append(html);
		});

	</script>
	<?php }?>
	<body>
		<br />
		<div class="container">
			<h2 align="center">VTDI Lab Tech's Inventory System</h2>
			<nav class="navbar navbar-inverse">
				<div class="container-fluid">
					<div class="navbar-header">
						<a href="index.php" class="navbar-brand">Home</a>
					</div>
					<ul class="nav navbar-nav">
					<?php
					if($_SESSION['type'] == 'master')
					{
					?>

						<li><a href="user.php">User</a></li>
						<li><a href="equipment.php">Equipment</a></li>

					<?php
					}
					?>
						<li><a href="request.php" id="request_link">Request Equipment</a></li>
						<li><a href="contact.php">Contact</a></li>
						
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"></span> <?php echo $_SESSION["user_name"]; ?></a>
							<ul class="dropdown-menu">
								<li><a href="profile.php">Profile</a></li>
								<li><a href="logout.php">Logout</a></li>
							</ul>
						</li>
					</ul>

				</div>
			</nav>
