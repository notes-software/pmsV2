<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../layouts/head.php'; ?>

<style>
    .welcome-msg {
        margin-top: 10%;
    }

    .note-title {
        background-color: transparent;
        border: none;
        padding: 0;
        outline: none;
        overflow: hidden;
        resize: none;
        vertical-align: top;
        width: 100%;
    }

    .cardNote {
        border: 0.5px solid #a3a3a38f
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
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#create_branch_modal">Add project</button>
                    </div>
                </div>

                <h3 class="card-title"></h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">

                <?php
                foreach ($projects as $project) :
                    $percentage = getProjectPercentage(getFinishTask($project['project_code']), getTotalTask($project['project_code']));
                ?>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-secondary">
                            <!-- <span class="info-box-icon"><i class="far fa-bookmark"></i></span> -->
                            <div class="info-box-content">
                                <span class="info-box-text"><?= getProjectName($project['project_code'], 'projectName') ?></span>
                                <span class="info-box-text"><?= $project['project_code'] ?></span>

                                <div class="progress" data-toggle='tooltip' data-placement='bottom' data-original-title='<?= $percentage ?>%'>
                                    <div class="progress-bar" style="width: <?= $percentage ?>%"></div>
                                </div>
                                <span class="info-box-text">
                                    <a href="<?= route('/project/' . $project['project_code']) ?>" class="btn btn-block btn-secondary btn-xs"><i class="fas fa-eye"></i> View Details</a>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>

                <?php endforeach; ?>

            </div>
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