<?php
//request.php

include('database_connection.php');

include_once('function.php');

if(!isset($_SESSION['type']))
{
	header('location:login.php');
}

include('header.php');


?>
	<link rel="stylesheet" href="css/datepicker.css">
	<script src="js/bootstrap-datepicker1.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

	<script>
	$(document).ready(function(){
		$('#inventory_request_date').datepicker({
			format: "yyyy-mm-dd",
			autoclose: true
		});
	});
  /*
  $(document).ready(function(){
		$('#inventory_request_to_time').datepicker({
			format: "yyyy-mm-dd",
			autoclose: true
		});
	});
  $(document).ready(function(){
		$('#inventory_request_from_time').datepicker({
			format: "yyyy-mm-dd",
			autoclose: true
		});
	});*/
	</script>

	<span id="alert_action"></span>
	<div class="row">
		<div class="col-lg-12">

			<div class="panel panel-default">
                <div class="panel-heading">
                	<div class="row">
                    	<div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            <h3 class="panel-title">Request Equipment</h3>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                            <button type="button" name="add" id="add_button" class="btn btn-success btn-xs">Add</button>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                	<table id="request_data" class="table table-brequested table-striped">
                		<thead>
							<tr>
								<th>Request ID</th>
								<th>Requestee Name</th><!--This should be the person | lecturer name-->
								<th>Request Status</th>
								<th>Requested Date</th><!--This should be the requested date-->
                <th>Location</th>
								<?php
								/*if($_SESSION['type'] == 'master')
								{
									echo '<th>Created By</th>';
								}*/
								?>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
                	</table>
                </div>
            </div>
        </div>
    </div>

    <div id="requestModal" class="modal fade">

    	<div class="modal-dialog">
    		<form method="post" id="request_form">
    			<div class="modal-content">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> Make request <?php fetch_pending_requests($connect);?></h4>
    				</div>
    				<div class="modal-body">
    					<div class="row">
              <!--
							<div class="col-md-6">
								<div class="form-group">
									<label>Enter Receiver Name</label>
									<input type="text" name="inventory_request_name" id="inventory_request_name" class="form-control" required />
								</div>
							</div>-->
							<!--<div class="col-md-6">-->
              <div class="col-md-4">
								<div class="form-group">
									<label>Date Needed</label>
									<input type="text" name="inventory_request_date" id="inventory_request_date" class="form-control" required />
								</div>
							</div>
              <div class="col-md-4">
								<div class="form-group">
									<label>Start time</label>
									<input type="time" name="inventory_request_from_time" id="inventory_request_from_time" class="form-control" required />
								</div>
							</div>
              <div class="col-md-4">
								<div class="form-group">
									<label>End time</label>
									<input type="time" name="inventory_request_to_time" id="inventory_request_to_time" class="form-control" required />
								</div>
							</div>
						</div>
            <div class="form-group">
              <label>Location</label>
              <input type="text" name="inventory_request_location" id="inventory_request_location" class="form-control" required />
            </div>
						<div class="form-group">
							<label>Any Comments</label>
							<textarea name="comment" id="comment" class="form-control"></textarea>
						</div>
						<div class="form-group">
							<label>Enter equipment Details</label>
							<hr />
							<span id="span_equipment_details"></span>
							<hr />
						</div>
            <!--
						<div class="form-group">
							<label>Select Payment Status</label>
							<select name="payment_status" id="payment_status" class="form-control">
								<option value="cash">Cash</option>
								<option value="credit">Credit</option>
                Hint: You can load options here
							</select>
						</div>-->
    				</div>
    				<div class="modal-footer">
    					<input type="hidden" name="inventory_request_id" id="inventory_request_id" />
    					<input type="hidden" name="btn_action" id="btn_action" />
    					<input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
    				</div>
    			</div>
    		</form>
    	</div>

    </div>

<script type="text/javascript">
    $(document).ready(function(){

    	var requestdataTable = $('#request_data').DataTable({
			"processing":true,
			"serverSide":true,
			"request":[],
			"ajax":{
				url:"request_fetch.php",
				type:"POST"
			},
			<?php
			if($_SESSION["type"] == 'master')
			{
			?>
			"columnDefs":[
				{
					//"targets":[5, 6, 7, 8],
          "targets":[5, 6, 7],
					"requestable":false,
				},
			],
			<?php
			}
			else
			{
			?>
			"columnDefs":[
				{
					"targets":[5, 6, 7],
					"requestable":false,
				},
			],
			<?php
			}
			?>
			"pageLength": 10
		});

		$('#add_button').click(function(){
			$('#requestModal').modal('show');
			$('#request_form')[0].reset();
			$('.modal-title').html("<i class='fa fa-plus'></i> Create request");
			$('#action').val('Add');
			$('#btn_action').val('Add');
			$('#span_equipment_details').html('');
			add_equipment_row();
		});

		function add_equipment_row(count = '')
		{
			var html = '';
			html += '<span id="row'+count+'"><div class="row">';
			html += '<div class="col-md-8">';
			html += '<select name="equipment_id[]" id="equipment_id'+count+'" class="form-control selectpicker" data-live-search="true" required>';
			html += '<?php echo fill_equipment_list($connect); ?>';
			html += '</select><input type="hidden" name="hidden_equipment_id[]" id="hidden_equipment_id'+count+'" />';
			html += '</div>';
			html += '<div class="col-md-3">';
			html += '<input type="text" name="quantity[]" class="form-control" required />';
			html += '</div>';
			html += '<div class="col-md-1">';
			if(count == '')
			{
				html += '<button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs">+</button>';
			}
			else
			{
				html += '<button type="button" name="remove" id="'+count+'" class="btn btn-danger btn-xs remove">-</button>';
			}
			html += '</div>';
			html += '</div></div><br /></span>';
			$('#span_equipment_details').append(html);

			$('.selectpicker').selectpicker();
		}

		var count = 0;

		$(document).on('click', '#add_more', function(){
			count = count + 1;
			add_equipment_row(count);
		});
		$(document).on('click', '.remove', function(){
			var row_no = $(this).attr("id");
			$('#row'+row_no).remove();
		});

		$(document).on('submit', '#request_form', function(event){
			event.preventDefault();
			$('#action').attr('disabled', 'disabled');
			var form_data = $(this).serialize();
			$.ajax({
				url:"request_action.php",
				method:"POST",
				data:form_data,
				success:function(data){
					$('#request_form')[0].reset();
					$('#requestModal').modal('hide');
					$('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
					$('#action').attr('disabled', false);
					requestdataTable.ajax.reload();
				}
			});
		});

		$(document).on('click', '.update', function(){
			var inventory_request_id = $(this).attr("id");
			var btn_action = 'fetch_single';
			$.ajax({
				url:"request_action.php",
				method:"POST",
				data:{inventory_request_id:inventory_request_id, btn_action:btn_action},
				dataType:"json",
				success:function(data)
				{
					$('#requestModal').modal('show');
					$('#user_id').val(data.user_id);
					$('#inventory_request_date').val(data.inventory_request_date);
					$('#inventory_request_from_time').val(data.inventory_request_from_time);
          $('#inventory_request_to_time').val(data.inventory_request_to_time);
          $('#inventory_request_status').val(data.inventory_request_status);
          $('#inventory_request_location').val(data.inventory_request_location);
          $('#comment').val(data.comment);
					$('#span_equipment_details').html(data.equipment_details);
					$('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit request");
					$('#inventory_request_id').val(inventory_request_id);
					$('#action').val('Edit');
					$('#btn_action').val('Edit');
				}
			})
		});

		$(document).on('click', '.delete', function(){
			var inventory_request_id = $(this).attr("id");
			var status = $(this).data("status");
			var btn_action = "delete";
			if(confirm("Are you sure you want to change status?"))
			{
				$.ajax({
					url:"request_action.php",
					method:"POST",
					data:{inventory_request_id:inventory_request_id, status:status, btn_action:btn_action},
					success:function(data)
					{
						$('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
						requestdataTable.ajax.reload();
					}
				})
			}
			else
			{
				return false;
			}
		});

    });
</script>
