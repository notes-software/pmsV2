<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../layouts/head.php'; ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0"><?= ucfirst($pageTitle) ?></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= route('/receiving') ?>">Settings</a></li>
                <li class="breadcrumb-item active">Settings</li>
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
                                <div>SYSTEM PERMISSION</div>
                            </div>
                        </div>

                        <div class="card-tools">
                            <div class="align-right">
                                <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#create_permission_modal">Add permission</button>
                                <button type="button" id="delete_branch_btn" class="btn btn-default btn-sm" onclick="deleteBranch()">Delete selected permission</button>
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
                                <th>FULLNAME</th>
                                <th>EMAIL</th>
                                <th>USERNAME</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($users as $user) {
                            ?>
                                <tr>
                                    <td class="no-sort text-center"><input type='checkbox' name='checkbox' value=''></td>
                                    <td class="no-sort text-center">
                                        <a style="color: #605e5e;"><i class="far fa-edit"></i></a>
                                    </td>
                                    <td><?= $user['fullname'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= $user['username'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                        <div class="card-tools">
                            <div class="align-left">
                                <div>SYSTEM ROLES</div>
                            </div>
                        </div>

                        <div class="card-tools">
                            <div class="align-right">
                                <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#create_branch_modal">Add role</button>
                                <button type="button" id="delete_branch_btn" class="btn btn-default btn-sm" onclick="deleteBranch()">Delete selected role</button>
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
                                <th>FULLNAME</th>
                                <th>EMAIL</th>
                                <th>USERNAME</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($users as $user) {
                            ?>
                                <tr>
                                    <td class="no-sort text-center"><input type='checkbox' name='checkbox' value=''></td>
                                    <td class="no-sort text-center">
                                        <a style="color: #605e5e;"><i class="far fa-edit"></i></a>
                                    </td>
                                    <td><?= $user['fullname'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= $user['username'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                                <div class="card-tools">
                                    <div class="align-left">
                                        <div>SYSTEM USERS</div>
                                    </div>
                                </div>

                                <div class="card-tools">
                                    <div class="align-right">
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#create_branch_modal">Add user</button>
                                        <button type="button" id="delete_branch_btn" class="btn btn-default btn-sm" onclick="deleteBranch()">Delete selected user</button>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($users as $user) {
                                    ?>
                                        <tr>
                                            <td class="no-sort text-center"><input type='checkbox' name='checkbox' value=''></td>
                                            <td class="no-sort text-center">
                                                <a style="color: #605e5e;"><i class="far fa-edit"></i></a>
                                            </td>
                                            <td><?= $user['fullname'] ?></td>
                                            <td><?= $user['email'] ?></td>
                                            <td><?= $user['username'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(function() {
        $("#permission_tbl").DataTable();
        $("#roles_tbl").DataTable();
        $("#users_tbl").DataTable();
    });
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>