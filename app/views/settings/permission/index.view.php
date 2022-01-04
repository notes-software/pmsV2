<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../../layouts/head.php'; ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0"><?= ucfirst($pageTitle) ?></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= route('/') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Settings</li>
                <li class="breadcrumb-item active">Permission</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    <?= alert_msg(); ?>
</section>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                        <div class="card-tools">
                            <div class="align-left">
                                <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#create_permission_modal">Add permission</button>
                                <button type="button" id="delete_permission_btn" class="btn btn-default btn-sm" onclick="deletePermission()">Delete selected permission</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="permission_tbl" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="no-sort" style="width: 15px;"></th>
                                <th class="no-sort" style="width: 15px;"></th>
                                <th>TITLE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($permissions as $permission) {
                            ?>
                                <tr>
                                    <td class="no-sort text-center"><input type='checkbox' name='checkbox' value='<?= $permission['id'] ?>'></td>
                                    <td class="no-sort text-center">
                                        <a style="color: #605e5e;" onclick="editPermission('<?= $permission['id'] ?>')"><i class="far fa-edit"></i></a>
                                    </td>
                                    <td><?= $permission['title'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</section>

<?php include_once __DIR__ . '/create_permission_modal.php'; ?>
<?php include_once __DIR__ . '/edit_permission_modal.php'; ?>

<script>
    $(function() {
        $("#permission_tbl").DataTable();
    });

    function editPermission(id) {
        $.post(base_url + "/settings/permission/view/" + id, {}, function(data) {
            var permission = JSON.parse(data);
            $("#u_title").val(permission.title);
            $("#u_permission_id").val(permission.id);
            $("#edit_permission_modal").modal('show');
        });
    }

    function deletePermission() {
        var checkedValues = $('input:checkbox:checked').map(function() {
            return this.value;
        }).get();

        id = [];
        if (checkedValues == "") {
            alertMe("warning", "No Selected Permission");
        } else {
            var retVal = confirm("Are you sure to delete?");
            if (retVal) {
                $("#delete_permission_btn").prop('disabled', true);
                $("#delete_permission_btn").html("<span class='fa fa-spin fa-spinner'></span> Loading ...");

                $.post(base_url + "/settings/permission/delete", {
                    id: checkedValues
                }, function(data) {
                    $("#delete_permission_btn").prop('disabled', true);
                    $("#delete_permission_btn").html("Delete selected permission");

                    location.reload();
                });
            }
        }
    }
</script>

<?php require  __DIR__ . '/../../layouts/footer.php'; ?>