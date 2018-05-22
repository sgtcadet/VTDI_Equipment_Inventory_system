<?php
//equipment.php

include('database_connection.php');
include_once('function.php');
//include_once('equipment_function.php');

if(!isset($_SESSION["type"]))
{
    header('location:login.php');
}

if($_SESSION['type'] != 'master')
{
    header('location:index.php');
}

include('header.php');


?>
        <span id='alert_action'></span>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
                    <div class="panel-heading">
                    	<div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            	<h3 class="panel-title">Equipment List</h3>
                            </div>

                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align='right'>
                                <button type="button" name="add" id="add_button" class="btn btn-success btn-xs">Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row"><div class="col-sm-12 table-responsive">
                            <table id="equipment_data" class="table table-bordered table-striped">
                                <thead><tr>
                                    <th>ID</th>
                                    <th>Equipment Name</th>
                                    <th>Quantity</th>
                                    <th>Enter By</th>
                                    <th>Status</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr></thead>
                            </table>
                        </div></div>
                    </div>
                </div>
			</div>
		</div>

        <div id="equipmentModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="equipment_form">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-plus"></i>Add Equipment</h4>
                        </div>
                        <div class="modal-body">
                          <!--
                            <div class="form-group">
                                <label>Select Category</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php //echo fill_category_list($connect);?>
                                </select>
                            </div>-->
                            <!--
                            <div class="form-group">
                                <label>Select Brand</label>
                                <select name="brand_id" id="brand_id" class="form-control" required>
                                    <option value="">Select Brand</option>
                                </select>
                            </div> -->
                            <div class="form-group">
                                <label>Enter Equipment Name</label>
                                <input type="text" name="equipment_name" id="equipment_name" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label>Enter Equipment Description</label>
                                <textarea name="equipment_description" id="equipment_description" class="form-control" rows="5" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Enter Equipment Quantity</label>
                                <div class="input-group">
                                    <input type="text" name="equipment_quantity" id="equipment_quantity" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" />
                                    <!--
                                    <span class="input-group-addon">
                                        <select name="equipment_unit" id="equipment_unit" required>
                                            <option value="">Select Unit</option>
                                            <option value="Bags">Bags</option>
                                            <option value="Bottles">Bottles</option>
                                            <option value="Box">Box</option>
                                            <option value="Dozens">Dozens</option>
                                            <option value="Feet">Feet</option>
                                            <option value="Gallon">Gallon</option>
                                            <option value="Grams">Grams</option>
                                            <option value="Inch">Inch</option>
                                            <option value="Kg">Kg</option>
                                            <option value="Liters">Liters</option>
                                            <option value="Meter">Meter</option>
                                            <option value="Nos">Nos</option>
                                            <option value="Packet">Packet</option>
                                            <option value="Rolls">Rolls</option>
                                        </select>
                                    </span>
                                    -->
                                </div>
                            </div>
                            <!--
                            <div class="form-group">
                                <label>Enter equipment Base Price</label>
                                <input type="text" name="equipment_base_price" id="equipment_base_price" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" />
                            </div>
                            <div class="form-group">
                                <label>Enter equipment Tax (%)</label>
                                <input type="text" name="equipment_tax" id="equipment_tax" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+" />
                            </div>
                          -->
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="equipment_id" id="equipment_id" />
                            <input type="hidden" name="btn_action" id="btn_action" />
                            <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="equipmentdetailsModal" class="modal fade">
            <div class="modal-dialog">
                <form method="post" id="equipment_form">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title"><i class="fa fa-plus"></i> Equipment Details</h4>
                        </div>
                        <div class="modal-body">
                            <Div id="equipment_details"></Div>
                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

<script>
$(document).ready(function(){
    var equipmentdataTable = $('#equipment_data').DataTable({
        "processing":true,
        "serverSide":true,
        "order":[],
        "ajax":{
            url:"equipment_fetch.php",
            type:"POST"
        },
        "columnDefs":[
            {
                "targets":[5, 6, 7],
                "orderable":false,
            },
        ],
        "pageLength": 10
    });

    $('#add_button').click(function(){
      //alert("Add button pressed!!");
        $('#equipmentModal').modal('show');
        $('#equipment_form')[0].reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Add New Equipment");
        $('#action').val("Add");
        $('#btn_action').val("Add");
    });
    /*
    $('#category_id').change(function(){
        var category_id = $('#category_id').val();
        var btn_action = 'load_brand';
        $.ajax({
            url:"equipment_action.php",
            method:"POST",
            data:{category_id:category_id, btn_action:btn_action},
            success:function(data)
            {
                $('#brand_id').html(data);
            }
        });
    });
    */
    $(document).on('submit', '#equipment_form', function(event){
        event.preventDefault();
        $('#action').attr('disabled', 'disabled');
        var form_data = $(this).serialize();
        $.ajax({
            url:"equipment_action.php",
            method:"POST",
            data:form_data,
            success:function(data)
            {
                $('#equipment_form')[0].reset();
                $('#equipmentModal').modal('hide');
                $('#alert_action').fadeIn().html('<div class="alert alert-success">'+data+'</div>');
                $('#action').attr('disabled', false);
                equipmentdataTable.ajax.reload();
            }
        })
    });

    $(document).on('click', '.view', function(){
        var equipment_id = $(this).attr("id");
        var btn_action = 'equipment_details';
        $.ajax({
            url:"equipment_action.php",
            method:"POST",
            data:{equipment_id:equipment_id, btn_action:btn_action},
            success:function(data){
                $('#equipmentdetailsModal').modal('show');
                $('#equipment_details').html(data);
            }
        })
    });

    $(document).on('click', '.update', function(){
        var equipment_id = $(this).attr("id");
        var btn_action = 'fetch_single';
        $.ajax({
            url:"equipment_action.php",
            method:"POST",
            data:{equipment_id:equipment_id, btn_action:btn_action},
            dataType:"json",
            success:function(data){
                $('#equipmentModal').modal('show');
                /*
                $('#category_id').val(data.category_id);
                $('#brand_id').html(data.brand_select_box);
                $('#brand_id').val(data.brand_id);*/
                $('#equipment_name').val(data.equipment_name);
                $('#equipment_description').val(data.equipment_description);
                $('#equipment_quantity').val(data.equipment_quantity);
                /*
                $('#equipment_unit').val(data.equipment_unit);
                $('#equipment_base_price').val(data.equipment_base_price);
                $('#equipment_tax').val(data.equipment_tax);*/
                $('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit equipment");
                $('#equipment_id').val(equipment_id);
                $('#action').val("Edit");
                $('#btn_action').val("Edit");
            }
        })
    });

    $(document).on('click', '.delete', function(){
        var equipment_id = $(this).attr("id");
        var status = $(this).data("status");
        var btn_action = 'delete';
        if(confirm("Are you sure you want to change status?"))
        {
            $.ajax({
                url:"equipment_action.php",
                method:"POST",
                data:{equipment_id:equipment_id, status:status, btn_action:btn_action},
                success:function(data){
                    $('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
                    equipmentdataTable.ajax.reload();
                }
            });
        }
        else
        {
            return false;
        }
    });

});
</script>
