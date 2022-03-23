<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../../layouts/head.php';

$userID = Auth::user('id');
$projectCode = (empty($projectDetail['projectCode'])) ? 0 : $projectDetail['projectCode'];
?>

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

    .ch-padd-hover:hover {
        background: #747f8d3d !important;
        border-radius: 3px;
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
                    </div>
                </div>

                <h3 class="card-title"></h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="card" style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 42%);">
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
                                        <h5><?= ($projectDetail["status"] == 1) ? "<span style='color: orange !important;'>Closed</span>" : "<span style='color: green !important;'>Active</span>"; ?></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="ps_project_cost">Cost Estimate</label>
                                        <input type="number" id="ps_project_cost" class="form-control" value="<?= $projectDetail["projectCost"] ?>">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="ps_project_deadline">Deadline</label>
                                        <input type="date" id="ps_project_deadline" class="form-control" value="<?= date('Y-m-d', strtotime($projectDetail["projectDeadline"])) ?>">
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

                            <?php if (isProjectManager($projectDetail['projectCode']) == 1 || isProjectTeamLeader($projectDetail['projectCode']) == 1) { ?>

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

                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card" style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 42%);">
                        <div class="card-header">
                            <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">

                                <div class="card-tools">
                                    <div class="align-left">
                                        <div>
                                            Members
                                        </div>
                                    </div>
                                </div>

                                <div class="card-title">
                                    <?php if (isProjectManager($projectDetail['projectCode']) == 1 || isProjectTeamLeader($projectDetail['projectCode']) == 1) { ?>
                                        <button type="button" class="btn btn-default btn-sm" onclick="inviteMemberToProj()" data-toggle="tooltip" data-placement="bottom" data-original-title="Invite member"><i class="fas fa-plus"></i></button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row" style='font-size: 14px;' id="members_data_list">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>

<?php include_once __DIR__ . '/invite_member_modal.view.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {
        displayProjectMemebers();
    });

    function displayProjectMemebers() {
        var proj_name = '<?= $projectDetail['projectName'] ?>';
        var proj_stats = '<?= $projectDetail["status"] ?>';
        var projectCode = '<?= $projectDetail['projectCode'] ?>';
        $.post(base_url + "/project/settings/members", {
            proj_name: proj_name,
            projCode: projectCode,
            proj_stats: proj_stats
        }, function(data) {
            // console.log(data);
            var projmember = JSON.parse(data);
            var member_list = '';

            var membr_len = projmember.proj_member.length;
            if (membr_len > 0) {

                for (var tdt = 0; tdt < membr_len; tdt++) {
                    var prjmem = projmember.proj_member[tdt];

                    var allow_delete = (prjmem.allowDelete == 1) ? '<a class="btn btn-link btn-sm" style="color: red;"><i class="far fa-trash-alt" onclick="removeProjectMember(' + prjmem.id + ')" data-toggle="tooltip" data-placement="bottom" data-title="Remove"></i></a>' : '';

                    member_list += '<li class="list-group-item ch-padd-hover mb-1" style="display: flex;align-items: center;justify-content: space-between;padding: 5px;border: 0px !important;width: -webkit-fill-available;"><div style="width: 80%;display: flex;justify-content: center;flex-direction: row;align-items: center;align-content: center;padding: 3px 5px 3px 5px;"><a class="avatar rounded-circle" style="width: 35px; height: 30px;"><img src=' + prjmem.avatar + ' style="width: 100%;height: 100%;object-fit: cover;" class="rounded-circle" data-toggle="tooltip" data-placement="left"></a><h4 class="text-muted" style="font-family: myFirstFont;font-size: 1rem;font-weight: 400;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;margin-bottom: 0px;width: -webkit-fill-available;margin-left: 7px;">' + prjmem.name + '</h4></div><div class="col-2"><div style="align-items: baseline;justify-content: flex-end;display: flex;"><span class="badge badge-danger"></span>' + allow_delete + '</div></div></li>';
                }

                $("#members_data_list").html(member_list);
            }
        });
    }

    function inviteMemberToProj() {
        $("#proj-search-people").val('');
        projSearchUser();

        $("#modal-add-member-to-project").modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }

    function projSearchUser() {
        var search_q = $("#proj-search-people").val();
        var project_code = '<?= $projectDetail['projectCode'] ?>';
        $.post(base_url + "/project/settings/searchPeople", {
            search_q: search_q,
            project_code: project_code
        }, function(data) {
            if (data != 1) {
                $("#proj-search-result").html(data);
            } else {
                alertMe("danger", "Please enter a valid email address.");
            }
        });
    }

    $('#proj-search-people').on('keypress', function(e) {
        if (e.keyCode == 13) {
            projSearchUser();
        }
        $('#proj-search-people').focus();
    });

    function inviteSelectedGroup() {
        var selected_group = $("#project-group-select").val();
        var projCode = '<?= $projectDetail['projectCode'] ?>';
        if (selected_group == "") {
            alertMe("warning", "please select a group to invite");
        } else {
            $.post(base_url + "/project/settings/saveGroupInvite", {
                selected_group: selected_group,
                projCode: projCode
            }, function(data) {
                resp_data = JSON.parse(data);
                if (resp_data[0].type == 1) {
                    alertMe("success", "Group members added to the project");
                } else if (resp_data[0].type == 2) {
                    alertMe("warning", resp_data[0].msg);
                } else {
                    alertMe("danger", "Error adding group to the project");
                }
                displayProjectMemebers();
            });
        }
    }

    function invitePeopleToProject(id) {
        var projCode = '<?= $projectDetail['projectCode'] ?>';
        $.post(base_url + "/project/settings/saveInvite", {
            id: id,
            projCode: projCode
        }, function(data) {
            if (data == 1) {
                displayProjectMemebers();
                $("#proj-search-people").val('');
                projSearchUser();
            } else {
                alertMe("danger", "Error saving invite");
            }
        });
    }

    function removeProject() {
        var projCode = $("#ps_project_code").val();
        var rslt = confirm("Are you sure you want to remove this project?");
        if (rslt) {
            $.post(base_url + "/project/settings/delete", {
                projCode: projCode
            }, function(data) {
                window.location.href = '<?= route("/project") ?>';
            });
        }
    }

    function removeProjectMember(id) {
        var projectCode = '<?= $projectDetail['projectCode'] ?>';
        var rslt = confirm("Are you sure you want to remove this member?");
        if (rslt) {
            $.post(base_url + "/project/settings/member/delete", {
                id: id,
                projCode: projectCode
            }, function(data) {
                displayProjectMemebers();
            });
        }
    }

    function updateProject() {
        var code = $("#ps_project_code").val();
        var name = $("#ps_project_name").val();
        var description = $("#project_desc").val();
        var project_cost = $("#ps_project_cost").val();
        var project_deadline = $("#ps_project_deadline").val();

        $.post(base_url + "/project/settings/update", {
            name: name,
            description: description,
            code: code,
            project_cost: project_cost,
            project_deadline: project_deadline
        }, function(data) {
            if (data == 1) {
                alertMe('success', 'Changes saved.');
            } else {
                alertMe('danger', 'Error in saving your changes.');
            }
        });
    }

    function closeProject() {
        var projectCode = '<?= $projectDetail['projectCode'] ?>';
        var finish_rslt = confirm("Are you sure you want to finish/close this project?");
        if (finish_rslt) {
            $.post(base_url + "/project/settings/finish", {
                projectCode: projectCode
            });
        }
    }
</script>

<?php require  __DIR__ . '/../../layouts/footer.php'; ?>