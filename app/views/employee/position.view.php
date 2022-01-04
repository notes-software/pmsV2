<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../layouts/head.php'; ?>

<style>
    .welcome-msg {
        margin-top: 10%;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0"><?= ucfirst($pageTitle) ?></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= route('/employee') ?>">Employee</a></li>
                <li class="breadcrumb-item active">Position</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    <?= alert_msg(); ?>
</section>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">

                <div class="card-tools">
                    <div class="align-left">
                        <a href="<?= route('/employee') ?>" type="button" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#create_position_modal">Add Position</button>
                        <button type="button" id="delete_position_btn" class="btn btn-default btn-sm" onclick="deletePosition()">Delete selected position</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="employee_tbl" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="no-sort" style="width: 15px;"></th>
                        <th class="no-sort" style="width: 15px;"></th>
                        <th>POSITION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($positions as $position) {
                    ?>
                        <tr>
                            <td class="no-sort text-center"><input type='checkbox' name='checkbox' value='<?= $position['id'] ?>'></td>
                            <td class="no-sort text-center">
                                <a style="color: #605e5e;" onclick="editPosition('<?= $position['id'] ?>')"><i class="far fa-edit"></i></a>
                            </td>
                            <td><?= $position['position'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <!-- <div class="card-footer">
            Footer
        </div> -->
        <!-- /.card-footer-->
    </div>
    <!-- /.card -->
</section>

<?php include_once __DIR__ . '/create_position_modal.php'; ?>
<?php include_once __DIR__ . '/edit_position_modal.php'; ?>

<script type="text/javascript">
    $(function() {
        $("#employee_tbl").DataTable();
    });

    function editPosition(id) {
        $.post(base_url + "/employee/position/view/" + id, {}, function(data) {
            var position = JSON.parse(data);
            $("#u_position_name").val(position.position);
            $("#u_position_id").val(position.id);
            $("#edit_position_modal").modal('show');
        });
    }

    function deletePosition() {
        var checkedValues = $('input:checkbox:checked').map(function() {
            return this.value;
        }).get();

        id = [];
        if (checkedValues == "") {
            alertMe("Aw Snap!", "No Selected Position", "warning");
        } else {
            var retVal = confirm("Are you sure to delete?");
            if (retVal) {
                $("#delete_position_btn").prop('disabled', true);
                $("#delete_position_btn").html("<span class='fa fa-spin fa-spinner'></span> Loading ...");

                $.post(base_url + "/employee/position/delete", {
                    id: checkedValues
                }, function(data) {
                    $("#delete_position_btn").prop('disabled', true);
                    $("#delete_position_btn").html("Delete selected position");

                    location.reload();
                });
            }
        }
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>