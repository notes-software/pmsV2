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
        <?php if (!gate_denies('project_add_access')) : ?>
            <div class="card-header">
                <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">

                    <div class="card-tools">
                        <div class="align-left">

                            <button type="button" class="btn btn-default btn-sm" onclick="openModalNewProject()">Add project</button>

                        </div>
                    </div>

                    <h3 class="card-title"></h3>
                </div>
            </div>
        <?php endif; ?>
        <div class="card-body pt-2">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist" style="flex-direction: row-reverse;">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="active-tab" data-toggle="tab" href="#active" role="tab" aria-controls="active" aria-selected="true">Active</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="archive-tab" data-toggle="tab" href="#archive" role="tab" aria-controls="archive" aria-selected="false">Archived</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                            <div class="row mt-3">
                                <?php
                                // dd($projects);
                                foreach ($projects as $project) :
                                    $percentage = getProjectPercentage(getFinishTask($project['project_code']), getTotalTask($project['project_code']));

                                    if (!empty($project['projects'])) {
                                        $projData = (!empty($project['projects'][0])) ? $project['projects'][0] : $project['projects'];

                                        if ($projData["status"] != 1) {

                                            $status = ($projData["status"] == 1) ? "<span style='color: orange !important;'>Closed</span>" : "<span style='color: green !important;'>Active</span>";

                                            if ($projData["status"] == 1) {
                                                $status = "Closed";
                                                $statColor = "orange";
                                            } else {
                                                $status = "Active";
                                                $statColor = "success";
                                            }
                                ?>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="info-box bg-gray-dark">
                                                    <!-- <span class="info-box-icon"><i class="far fa-bookmark"></i></span> -->
                                                    <div class="info-box-content" style="overflow: hidden;text-overflow: ellipsis;">

                                                        <span class="info-box-text"><?= getProjectName($projData['projectCode'], 'projectName') ?></span>
                                                        <div class="info-box-text d-flex" style="flex-direction: row;justify-content: space-between;">
                                                            <small class="info-box-text">[ <?= $projData['projectCode'] ?> ] <?= $percentage ?>%</small>
                                                            <div class="info-box-text"><span class="badge rounded-pill bg-<?= $statColor ?>"><?= $status ?></span></div>
                                                        </div>

                                                        <div class="progress" data-toggle='tooltip' data-placement='bottom' data-original-title='<?= $percentage ?>%'>
                                                            <div class="progress-bar" style="width: <?= $percentage ?>%"></div>
                                                        </div>
                                                        <span class="info-box-text">
                                                            <a href="<?= route('/project/' . $project['project_code']) ?>" class="btn btn-block btn-dark btn-xs"><i class="fas fa-eye"></i> View Details</a>
                                                        </span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>

                                <?php }
                                    }
                                endforeach; ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="archive" role="tabpanel" aria-labelledby="archive-tab">
                            <div class="row mt-3">
                                <?php
                                // dd($projects);
                                foreach ($projects as $project) :
                                    $percentage = getProjectPercentage(getFinishTask($project['project_code']), getTotalTask($project['project_code']));

                                    if (!empty($project['projects'])) {
                                        $projData = (!empty($project['projects'][0])) ? $project['projects'][0] : $project['projects'];


                                        if ($projData["status"] == 1) {

                                            $status = ($projData["status"] == 1) ? "<span style='color: orange !important;'>Closed</span>" : "<span style='color: green !important;'>Active</span>";

                                            if ($projData["status"] == 1) {
                                                $status = "Closed";
                                                $statColor = "orange";
                                            } else {
                                                $status = "Active";
                                                $statColor = "success";
                                            }
                                ?>
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="info-box bg-gray-dark">
                                                    <!-- <span class="info-box-icon"><i class="far fa-bookmark"></i></span> -->
                                                    <div class="info-box-content" style="overflow: hidden;text-overflow: ellipsis;">

                                                        <span class="info-box-text"><?= getProjectName($projData['projectCode'], 'projectName') ?></span>
                                                        <div class="info-box-text d-flex" style="flex-direction: row;justify-content: space-between;">
                                                            <small class="info-box-text">[ <?= $projData['projectCode'] ?> ] <?= $percentage ?>%</small>
                                                            <div class="info-box-text"><span class="badge rounded-pill bg-<?= $statColor ?>"><?= $status ?></span></div>
                                                        </div>

                                                        <div class="progress" data-toggle='tooltip' data-placement='bottom' data-original-title='<?= $percentage ?>%'>
                                                            <div class="progress-bar" style="width: <?= $percentage ?>%"></div>
                                                        </div>
                                                        <span class="info-box-text">
                                                            <a href="<?= route('/project/' . $project['project_code']) ?>" class="btn btn-block btn-dark btn-xs"><i class="fas fa-eye"></i> View Details</a>
                                                        </span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>

                                <?php }
                                    }
                                endforeach; ?>
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

<?php include_once __DIR__ . '/add_project_modal.php'; ?>
<?php include_once __DIR__ . '/edit_task_modal.php'; ?>

<script type="text/javascript">
    $(function() {});

    function openModalNewProject() {
        $("#modal-add-project").modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }

    function saveNewProject() {
        var project_name = $("#project_name_modal").val();
        var project_description = $("#project_description_modal").val();
        var project_cost = $("#project_cost").val();
        var project_deadline = $("#project_deadline").val();

        if (project_name == '' || project_description == '' || project_deadline == '') {
            alertMe("warning", "Input all fields");
        } else {
            $.post(base_url + "/project/add", {
                project_name: project_name,
                project_description: project_description,
                project_cost: project_cost,
                project_deadline: project_deadline
            }, function(data) {
                if (data == 1) {
                    $("#modal-add-project").modal('hide');
                    location.reload();
                } else {
                    alertMe("danger", "Error saving project");
                }
            });
        }
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>