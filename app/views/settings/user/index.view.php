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
                <li class="breadcrumb-item active">Users</li>
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
                                <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#create_users_modal">Add user</button>
                                <button type="button" id="delete_users_btn" class="btn btn-default btn-sm" onclick="deleteUser()">Delete selected user</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="users_tbl" class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="no-sort" style="width: 15px;"></th>
                                <th class="no-sort" style="width: 15px;"></th>
                                <th>FULLNAME</th>
                                <th>EMAIL</th>
                                <th>USERNAME</th>
                                <th>ACCESS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($users as $user) {
                            ?>
                                <tr>
                                    <td class="no-sort text-center"><input type='checkbox' name='checkbox' value='<?= $user['id'] ?>'></td>
                                    <td class="no-sort text-center">
                                        <a style="color: #605e5e;" onclick="editUser('<?= $user['id'] ?>')"><i class="far fa-edit"></i></a>
                                    </td>
                                    <td><?= $user['fullname'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= $user['username'] ?></td>
                                    <td><?= (!empty($user['roles'])) ? ((!empty($user['roles'][0])) ? $user['roles'][0]['role'] : $user['roles']['role']) : ''; ?></td>
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

<?php include_once __DIR__ . '/create_users_modal.php'; ?>
<?php include_once __DIR__ . '/edit_users_modal.php'; ?>

<script>
    $(function() {
        $("#users_tbl").DataTable();
    });

    function editUser(id) {
        $.post(base_url + "/settings/users/view/" + id, {}, function(data) {
            var user = JSON.parse(data);
            $("#u_users_id").val(user.id);
            $("#u_email").val(user.email);

            $("#u_name").val(user.fullname);
            $("#u_name").select2().trigger('change');

            $("#u_username").val(user.username);

            $("#u_roles").val(user.role_id);
            $("#u_roles").select2().trigger('change');

            $("#edit_users_modal").modal('show');
        });
    }

    function deleteUser() {
        var checkedValues = $('input:checkbox:checked').map(function() {
            return this.value;
        }).get();

        id = [];
        if (checkedValues == "") {
            alertMe("warning", "No Selected User");
        } else {
            var retVal = confirm("Are you sure to delete?");
            if (retVal) {
                $("#delete_users_btn").prop('disabled', true);
                $("#delete_users_btn").html("<span class='fa fa-spin fa-spinner'></span> Loading ...");

                $.post(base_url + "/settings/users/delete", {
                    id: checkedValues
                }, function(data) {
                    $("#delete_users_btn").prop('disabled', true);
                    $("#delete_users_btn").html("Delete selected permission");

                    location.reload();
                });
            }
        }
    }
</script>

<?php require  __DIR__ . '/../../layouts/footer.php'; ?>