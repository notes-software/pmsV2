<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../../layouts/head.php'; ?>

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
                <li class="breadcrumb-item"><a href="<?= route('/project', $projectDetail['projectCode']) ?>">Project</a></li>
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
                        <a href="<?= route('/project', $projectDetail['projectCode']) ?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" data-original-title="Go back"><i class="fas fa-arrow-left p-1"></i></a>
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#create_branch_modal">Add project</button>
                    </div>
                </div>

                <h3 class="card-title"></h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">

                                <div class="card-tools">
                                    <div class="align-left">
                                        <div>
                                            Update your project information
                                        </div>
                                    </div>
                                </div>

                                <h3 class="card-title"></h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-7">
                                    <div class="form-group">
                                        <label for="ps_project_name">Project name</label>
                                        <input type="text" id="ps_project_name" class="form-control" placeholder="My awesome project" value="<?= $projectDetail["projectName"] ?>">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="ps_project_code">Project ID</label>
                                        <input type="text" id="ps_project_code" class="form-control" placeholder="My awesome project" value="<?= $projectDetail["projectCode"] ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="ps_project_code">Status</label>
                                        <h2><?= ($projectDetail["status"] == 1) ? "<span style='color: orange !important;'>Closed</span>" : "<span style='color: green !important;'>Active</span>"; ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="project_desc">Project description (optional)</label>
                                        <textarea id="project_desc" rows="3" class="form-control" placeholder="My awesome project"><?= $projectDetail["projectDescription"] ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col" style="display: flex;flex-direction: row;justify-content: space-between;">
                                    <?php
                                    if ($projectDetail["status"] != 1) {
                                    ?>
                                        <button class="btn btn-success btn-md" id="save_proj_btn" onclick="updateProject()">Save changes</button>
                                        <button class="btn btn-warning btn-md" id="close_proj_btn" onclick="closeProject()">Close project</button>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <h3>Remove project</h3>
                                    <p class="text-muted">Removing the project will delete all related resources including task, linked members etc. <br><b>Removed projects cannot be restored!</b></p>
                                    <a href="#" class="btn btn-danger btn-md" id="remove_proj_btn" onclick="removeProject()">Remove project</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

<script type="text/javascript">

</script>

<?php require  __DIR__ . '/../../layouts/footer.php'; ?>