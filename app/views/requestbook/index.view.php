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
                <li class="breadcrumb-item"><a href="<?= route('/') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active"><?= ucfirst($pageTitle) ?></li>
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
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#create_branch_modal">Add request</button>
                        <button type="button" id="delete_branch_btn" class="btn btn-default btn-sm" onclick="deleteBranch()">Delete selected request</button>
                    </div>
                </div>

                <h3 class="card-title"></h3>
            </div>
        </div>
        <div class="card-body">
            <table id="branch_tbl" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="no-sort" style="width: 15px;"></th>
                        <th class="no-sort" style="width: 15px;"></th>
                        <th>BRANCH NAME</th>
                        <th style="width: 90px;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($requests != null) {
                        foreach ($requests as $request) {
                            $status = ($request['status'] == 0) ? '<span style="color: green;">Active</span>' : '<span style="color: red;">Inactive</span>';
                    ?>
                            <tr>
                                <td class="no-sort text-center"><input type='checkbox' name='checkbox' value='<?= $request['id'] ?>'></td>
                                <td class="no-sort text-center">
                                    <a style="color: #605e5e;" onclick="editBranch('<?= $request['id'] ?>')"><i class="far fa-edit"></i></a>
                                </td>
                                <td><?= $request['name'] ?></td>
                                <td style="width: 90px;"><?= $status ?></td>
                            </tr>
                    <?php }
                    } ?>
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

<?php include_once __DIR__ . '/create_request_modal.php'; ?>
<?php include_once __DIR__ . '/edit_request_modal.php'; ?>

<script type="text/javascript">
    $(function() {
        $("#branch_tbl").DataTable();
    });

    function editBranch(id) {
        $.post(base_url + "/branch/view/" + id, {}, function(data) {
            var branch = JSON.parse(data);
            $("#u_branch_name").val(branch.name);
            $("#u_branch_id").val(branch.id);
            $("#edit_branch_modal").modal('show');
        });
    }

    function deleteBranch() {
        var checkedValues = $('input:checkbox:checked').map(function() {
            return this.value;
        }).get();

        id = [];
        if (checkedValues == "") {
            alertMe("Aw Snap!", "No Selected Branch", "warning");
        } else {
            var retVal = confirm("Are you sure to delete?");
            if (retVal) {
                $("#delete_branch_btn").prop('disabled', true);
                $("#delete_branch_btn").html("<span class='fa fa-spin fa-spinner'></span> Loading ...");

                $.post(base_url + "/branch/delete", {
                    id: checkedValues
                }, function(data) {
                    $("#delete_branch_btn").prop('disabled', true);
                    $("#delete_branch_btn").html("Delete selected branch");

                    location.reload();
                });
            }
        }
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>