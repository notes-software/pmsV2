<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../../layouts/head.php'; ?>

<style>
    .badge {
        margin: 1px;
        font-size: 87%;
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
                <li class="breadcrumb-item"><a href="<?= route('/') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Settings</li>
                <li class="breadcrumb-item active">Roles</li>
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
                                <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#create_roles_modal">Add roles</button>
                                <button type="button" id="delete_roles_btn" class="btn btn-default btn-sm" onclick="deleteRoles()">Delete selected roles</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="roles_tbl" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="no-sort" style="width: 15px;"></th>
                                <th class="no-sort" style="width: 15px;"></th>
                                <th style="width: 200px;">TITLE</th>
                                <th>PERMISSIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($roles as $role) {
                            ?>
                                <tr>
                                    <td class="no-sort text-center"><input type='checkbox' name='checkbox' value='<?= $role['id'] ?>'></td>
                                    <td class="no-sort text-center">
                                        <a style="color: #605e5e;" onclick="editRoles('<?= $role['id'] ?>')"><i class="far fa-edit"></i></a>
                                    </td>
                                    <td><?= $role['role'] ?></td>
                                    <td>
                                        <?php
                                        foreach (explode(',', $role['permission']) as $permission) {
                                            echo '<span class="badge badge-info">' . getPermissionName($permission) . '</span>';
                                        }
                                        ?>
                                    </td>
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

<?php include_once __DIR__ . '/create_roles_modal.php'; ?>
<?php include_once __DIR__ . '/edit_roles_modal.php';
?>

<script>
    $(function() {
        $("#roles_tbl").DataTable();

        $("#permissions").select2({
            width: '100%',
            placeholder: "-- Please Select --"
        });
    });

    function editRoles(id) {
        $.post(base_url + "/settings/roles/view/" + id, {}, function(data) {
            var roles = JSON.parse(data);
            $("#u_title").val(roles.role);
            $("#u_roles_id").val(roles.id);

            var prmssn_list_cloth = "";
            for (prmssn_ in roles.permissions) {
                var prmssn_list = roles.permissions[prmssn_];
                prmssn_list_cloth += '<option ' + prmssn_list.is_selected + ' value=' + prmssn_list.id + '>' + prmssn_list.title + '</option>';
            }

            $("#u_permissions").html(prmssn_list_cloth);
            $("#u_permissions").select2({
                width: '100%',
                placeholder: "-- Please Select --"
            });
            $("#edit_roles_modal").modal('show');
        });
    }

    function deleteRoles() {
        var checkedValues = $('input:checkbox:checked').map(function() {
            return this.value;
        }).get();

        id = [];
        if (checkedValues == "") {
            alertMe("warning", "No Selected Roles");
        } else {
            var retVal = confirm("Are you sure to delete?");
            if (retVal) {
                $("#delete_roles_btn").prop('disabled', true);
                $("#delete_roles_btn").html("<span class='fa fa-spin fa-spinner'></span> Loading ...");

                $.post(base_url + "/settings/roles/delete", {
                    id: checkedValues
                }, function(data) {
                    $("#delete_roles_btn").prop('disabled', true);
                    $("#delete_roles_btn").html("Delete selected roles");

                    location.reload();
                });
            }
        }
    }
</script>

<?php require  __DIR__ . '/../../layouts/footer.php'; ?>