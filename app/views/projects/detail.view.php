<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../layouts/head.php';

$isProjPm = isProjectManager($project['projectCode']);
$isProjTl = isProjectTeamLeader($project['projectCode']);
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

    .cardDropOffs {
        border: 1px solid #c9c9c9;
    }

    .droptarget {
        /* height: 600px; */
    }

    .ch-padd-hover:hover {
        background: #747f8d3d !important;
        border-radius: 3px;
    }
</style>

<!-- Content Header (Page header) -->
<input type="hidden" id="is_pm" value="<?= $isProjPm ?>">
<input type="hidden" id="is_tl" value="<?= $isProjTl ?>">
<input type="hidden" id="has_user_id" value="<?= Auth::user('id') ?>">
<section class="content-header pb-0">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0" style="color: #000; font-family: 'myFirstFont';"><?= ucfirst($pageTitle) ?></h1>
            <p class="text-muted" style="font-size: 12px;">[ <span style="color: #000; font-weight: 400;"><?= $project['projectCode'] ?></span> ] <?= $project['projectDescription'] ?></p>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= route('/') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= route('/project') ?>">Project</a></li>
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
                        <a href="<?= route('/project') ?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" data-original-title="Go back"><i class="fas fa-arrow-left p-1"></i></a>

                        <?php if (isProjectManager($project['projectCode']) == 1 || isProjectTeamLeader($project['projectCode']) == 1) { ?>
                            <a href="<?= route('/project/settings', $project['projectCode']) ?>" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" data-original-title="Project Settings"><i class="fas fa-cog p-1"></i></a>
                        <?php } ?>

                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#add_task_modal">New Task</button>
                    </div>
                </div>

                <div class="card-title">
                    <?php if (isProjectManager($project['projectCode']) == 1 || isProjectTeamLeader($project['projectCode']) == 1) { ?>
                        <select class="form-control-sm select2 " name="myproj-member-selected" id="myproj-member-selected" tabindex="-1" aria-hidden="true" onchange="displayUserTask()">
                            <option value="">-- select member --</option>
                            <?php
                            if (count($projectMembers) > 0) {
                                foreach ($projectMembers as $member) {
                            ?>
                                    <option value="<?= $member['user_id'] ?>"><?= $member['memberName'] ?></option>
                            <?php }
                            } ?>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="row">

                <!-- TODO'S -->
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="card cardDropOffs">
                        <div class="card-header">
                            <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                                <div>
                                    <span>TO DO'S</span>
                                </div>
                                <div>
                                    <span class=" badge rounded-pill bg-dark" id="counter-todo"></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-2 drops droptarget msg_chat_scroll" id="sortable1">

                        </div>
                    </div>
                </div>
                <!-- END TODO'S -->

                <!-- IN PROGRESS -->
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="card cardDropOffs">
                        <div class="card-header">
                            <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                                <div>
                                    <span>IN PROGRESS</span>
                                </div>
                                <div>
                                    <span class=" badge rounded-pill bg-dark" id="counter-inprogress"></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-2 drops droptarget msg_chat_scroll" id="sortable2">
                        </div>
                    </div>
                </div>
                <!-- END IN PROGRESS -->

                <!-- DONE -->
                <div class="col-md-4 col-sm-6 col-12">
                    <!-- <div class="card cardDropOffs">
                        <div class="card-header">
                            <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">

                                <div class="card-tools">
                                    <div>
                                        DONE
                                        <span class="badge badge-secondary navbar-badge">15</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-2 drops droptarget msg_chat_scroll" id="sortable3">
                        </div>
                    </div> -->

                    <div class="card cardDropOffs">
                        <div class="card-header pb-0 pt-1">
                            <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                                <div class="card-tools">DONE</div>
                                <div class="card-tools">
                                    <div class="d-flex" style="flex-direction: row;align-items: center;justify-content: space-between;">

                                        <!-- <span class="badge badge-secondary navbar-badge">15</span> -->
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item"><a class="nav-link active" href="#todaytab" data-toggle="tab">Today <span class="badge rounded-pill bg-dark" id="counter-today"></span></a></li>
                                            <li class="nav-item"><a class="nav-link" href="#pasttab" data-toggle="tab">Past <span class="badge rounded-pill bg-dark" id="counter-done"></span></a></li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div><!-- /.card-header -->
                        <div class="card-body p-2 drops droptarget msg_chat_scroll" id="sortable3">
                            <div class="tab-content">
                                <div class="tab-pane active" id="todaytab">
                                    <div id="sortable3_today" style="min-height: 150px;">
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="pasttab">
                                    <div id="sortable3_all">
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                </div>
                <!-- END DONE -->

            </div>
        </div>
    </div>
    <!-- /.card -->
</section>

<?php include_once __DIR__ . '/add_task_modal.view.php'; ?>
<?php include_once __DIR__ . '/edit_task_modal.php'; ?>
<?php include_once __DIR__ . '/create_request_modal.php'; ?>

<script type="text/javascript">
    var isPM = $("#is_pm").val();
    var isTL = $("#is_tl").val();

    $(function() {
        displayUserTask();
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

    function displayUserTask() {
        var project_code = "<?= $project['projectCode'] ?>";
        var memberSelected;
        if (isPM == 1 || isTL == 1) {
            memberSelected = $("#myproj-member-selected").val();
        } else {
            memberSelected = "";
        }

        $.post(base_url + "/project/task", {
            project_code: project_code,
            memberSelected: memberSelected
        }, function(data) {
            // $('#user_task_container').html(data);
            var task_data = JSON.parse(data);
            var todo_cloth = "",
                inprog_cloth = "",
                done_cloth_today = "",
                done_cloth_all = "";
            var todo_len = 0,
                inprog_len = 0,
                done_len = 0;

            // TO DO'S
            var todo_len = (task_data.todo == null) ? 0 : task_data.todo.length;
            if (todo_len > 0) {
                for (var td = 0; td < todo_len; ++td) {
                    var todo_list = task_data.todo[td];

                    todo_cloth += taskClothe(todo_list);
                }
            }
            $("#counter-todo").html(todo_len);
            $('#sortable1').html(todo_cloth);

            // IN PROGRESS
            var inprog_len = (task_data.inprogress == null) ? 0 : task_data.inprogress.length;
            if (inprog_len > 0) {
                for (var td = 0; td < inprog_len; ++td) {
                    var inprog_list = task_data.inprogress[td];

                    inprog_cloth += taskClothe(inprog_list);
                }
            }
            $('#sortable2').html(inprog_cloth);
            $("#counter-inprogress").html(inprog_len);

            // DONE
            var today = new Date();
            const year = today.getFullYear();
            const date = today.getDate();
            const month = today.getMonth() + 1;
            var now = year + "-" + month + "-" + date;

            var done_len = (task_data.done == null) ? 0 : task_data.done.length;
            var count_all = 0,
                count_today = 0;

            if (done_len > 0) {
                for (var td = 0; td < done_len; ++td) {
                    var done_list = task_data.done[td];

                    // console.log(done_list.finishDate + " == " + now);
                    if (done_list.finishDate == now) {
                        count_today++;
                        done_cloth_today += taskClothe(done_list);
                    } else {
                        count_all++;
                        done_cloth_all += taskClothe(done_list);
                    }
                }
            }
            $('#sortable3_today').html(done_cloth_today);
            $("#counter-today").html(count_today);

            $('#sortable3_all').html(done_cloth_all);
            $("#counter-done").html(count_all);

            draggable_task();
        });
    }

    function draggable_task() {
        var hasUserID = $("#has_user_id").val();
        if (hasUserID != "") {
            $(function() {
                $("#sortable1, #sortable2").sortable({
                    connectWith: ".drops"
                }).disableSelection();
            });

            $("#sortable1, #sortable2, #sortable3").droppable({
                drop: function(event, ui) {
                    var draggableId = ui.draggable.attr("id");
                    var parent_id = event.target.id;

                    if (parent_id == "sortable1") {
                        updateType(draggableId, 0);
                    } else if (parent_id == "sortable2") {
                        updateType(draggableId, 1);
                    } else {
                        updateType(draggableId, 2);
                    }
                }
            });
        } else {
            alertMe('danger', 'User session is empty! Please log in again.');
        }
    }

    function taskClothe(list) {

        var deleteOption = (list.allow_delete == 1) ? '<span class="badge navbar-badge" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete task" onclick="deletetask(\'' + list.task_id + '\')" style="right: 15px !important;cursor: default;"><i class="far fa-trash-alt" style="font-size: 13px;"></i></span>' : '';

        $viewOption = (deleteOption == '') ? '15px' : '35';

        var task_member = "";
        var memTDCounter = 1;
        if (list.task_member != null) {
            for (var tmem = 0; tmem < list.task_member.length; tmem++) {
                var mmbr = list.task_member[tmem];
                if (tmem <= 3) {
                    task_member += "<img src='" + mmbr.member_avatar + "' style='width:20px; height: 20px;object-fit: cover;margin-left: 1px;cursor: default;' class='rounded-circle' data-toggle='tooltip' data-placement='bottom' title='' data-original-title='" + mmbr.member_name + "'>";
                } else {
                    if (memTDCounter == 1) {
                        task_member += '<span class="rounded-circle" style="margin-left: 1px;padding: 2px;font-size: 12px;background-color: #868686 !important;cursor: default;" data-toggle="tooltip" data-placement="bottom" data-original-title="+' + (list.task_member.length - 4) + ' more">+' + (list.task_member.length - 4) + '</span>';
                    }
                    memTDCounter++;
                }
            }
        }

        return '<div class="col-md-12 col-sm-12 col-12" id="' + list.task_id + '"><div class="row"><div class="info-box bg-' + list.priority_color + '" style="cursor: move;"><div class="info-box-content"><div class="mt-1">' + task_member + '</div><div class="d-flex" style="flex-direction: row;justify-content: space-between;"><div><small class="info-box-text mt-1"><i class="far fa-calendar-check"></i> Due: ' + list.date + '</small></div><div><small class="info-box-text mt-1">Code: ' + list.task_code + '</small></div></div>' + deleteOption + '<span class="badge navbar-badge" data-toggle="tooltip" data-placement="bottom" data-original-title="View task" style="cursor: default;right: ' + $viewOption + ';" onclick="viewTask(\'' + list.task_id + '\', \'' + list.module + '\')"><i class="fas fa-eye" style="font-size: 13px;"></i></span><span class="info-box-text"><pre class="mt-1 px-2" style="white-space: pre-wrap;font-family: myFirstFont;font-size: inherit;padding: 0px;color: inherit;background: #0000001a;border-radius: 3px;">' + list.task + '</pre></span></div></div></div></div>';
    }

    function updateType(id, type) {
        var project_code = "<?= $project['projectCode'] ?>";
        $.post(base_url + "/project/task/update", {
            id: id,
            type: type,
            project_code: project_code
        }, function(data, status) {
            displayUserTask();
            alertMe('success', 'Task updated');
        });
    }

    function viewTask(id, type) {
        getTaskDetails(id, type);
        $("#edit_task_modal").modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }

    function getTaskDetails(id, type) {
        var project_code = "<?= $project['projectCode'] ?>";

        $.post(base_url + "/project/task/detail", {
            task_id: id,
            type: type,
            project_code: project_code
        }, function(data) {
            var task_dt = JSON.parse(data);
            $("#task_v_id").val(id);
            var task_dt_len = task_dt.task_detail.length;
            if (task_dt_len > 0) {
                for (var tdt = 0; tdt < task_dt_len; tdt++) {
                    var tsk_dt = task_dt.task_detail[tdt];

                    if (tsk_dt.priority == 0) {
                        $("#task-color").css("color", "green");
                    } else if (tsk_dt.priority == 1) {
                        $("#task-color").css("color", "orange");
                    } else {
                        $("#task-color").css("color", "red");
                    }

                    $("#task-search-result").html("");
                    $("#taskSearchMember").val("");

                    $("#v_task_code").html(tsk_dt.task_code);
                    $("#v_task_status").html(tsk_dt.task_type);
                    $("#task_v_date").val(tsk_dt.date);
                    $("#v_task_prio_status").val(tsk_dt.priority);
                    $("#v_task_desc").html(tsk_dt.task);

                    if (tsk_dt.task_type != "DONE") {
                        $('#saveChangesBtn').html('<a href="#" class="btn btn-primary btn-sm float-right" onclick="save_task_details()">Save Changes</a>');
                        $('#shareTaskBin').show();
                    } else {
                        $('#saveChangesBtn').html('');
                        $('#shareTaskBin').hide();
                    }

                    var member_list = "";
                    var member_remove_btn;
                    var task_mmber_len = tsk_dt.task_member.length;

                    $("#member-count").html(task_mmber_len);
                    if (task_mmber_len > 0) {
                        for (var mmbrL = 0; mmbrL < task_mmber_len; mmbrL++) {
                            var tsk_mem_list = tsk_dt.task_member[mmbrL];
                            if (tsk_mem_list.invite_status == 1) {
                                if (tsk_dt.member_remove == 1) {
                                    member_remove_btn = '<a class="btn btn-link btn-sm" style="color: red;"><i class="far fa-trash-alt" data-toggle="tooltip" data-placement="bottom" data-title="Remove"></i></a>';
                                } else {
                                    member_remove_btn = '';
                                }
                            } else {
                                member_remove_btn = '<a class="btn btn-link btn-sm" style="color: green;"><i class="fas fa-seedling" data-toggle="tooltip" data-placement="bottom" data-title="Owner"></i></a>';
                            }

                            member_list += '<li class="list-group-item ch-padd-hover mb-1" style="display: flex;align-items: center;justify-content: space-between;padding: 5px;border: 0px !important;width: -webkit-fill-available;"><div style="width: 80%;display: flex;justify-content: center;flex-direction: row;align-items: center;align-content: center;padding: 3px 5px 3px 5px;"><a class="avatar rounded-circle" style="width: 35px; height: 30px;"><img src=' + tsk_mem_list.member_avatar + ' style="width: 100%;height: 100%;object-fit: cover;" class="rounded-circle" data-toggle="tooltip" data-placement="left"></a><h4 class="text-muted" style="font-family: myFirstFont;font-size: 1rem;font-weight: 400;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;margin-bottom: 0px;width: -webkit-fill-available;margin-left: 7px;">' + tsk_mem_list.member_name + '</h4></div><div class="col-2"><div style="align-items: baseline;justify-content: flex-end;display: flex;"><span class="badge badge-danger"></span>' + member_remove_btn + '</div></div></li>';
                        }
                    }
                    $("#task_member_bin").html(member_list);
                }
            }
        });
    }

    function addNewTask() {
        var task_date = $("#task_date").val();
        var task_status = $("#task_status").val();
        var task_description = $("#task_description").html();
        var project_code = "<?= $project['projectCode'] ?>";
        if (project_code == '') {
            alertMe('danger', 'No project selected!');
        } else {
            if (task_status == '' || task_description == '') {
                alertMe("warning", "Some of the fields is blank, please fill in the fields.");
            } else {
                $.post(base_url + "/project/task/add", {
                    due_date: task_date,
                    taskDescription: task_description,
                    priority_status: task_status,
                    projectCode: project_code
                }, function(data, status) {
                    if (data == 1) {
                        alertMe('success', 'Successfully Saved!');
                        $("#task_description").val('');
                    } else {
                        alertMe('danger', 'Error saving!');
                    }
                    displayUserTask();
                    //$("#modal-add-task").modal('hide');
                });
            }
        }
    }

    function save_task_details() {
        var project_code = "<?= $project['projectCode'] ?>";
        var task_desc = $("#v_task_desc").html();
        var task_due_date = $("#task_v_date").val();
        var task_code = $("#v_task_code").html();
        var task_prio = $("#v_task_prio_status").val();
        $.post(base_url + "/project/task/update/details", {
            task_desc: task_desc,
            task_due_date: task_due_date,
            task_code: task_code,
            task_prio: task_prio,
            project_code: project_code
        }, function(data) {
            if (data == 1) {
                alertMe("success", "Task updated");
            } else {
                alertMe("danger", "Something went wrong!");
            }

            displayUserTask();
            var task_id = $("#task_v_id").val();
            var task_type = $("#v_task_status").html();
            getTaskDetails(task_id, task_type);
        });
    }

    function deletetask(id) {
        var result = confirm("Are you sure you want to delete task?");
        if (result) {
            var project_code = "<?= $project['projectCode'] ?>";
            $.post(base_url + "/project/task/delete", {
                id: id,
                project_code: project_code
            }, function(data, status) {
                if (data == 1) {
                    alertMe("success", "Task deleted!");
                } else {
                    alertMe("danger", "Something went wrong!");
                }
                displayUserTask();
            });
        }
    }

    $('#taskSearchMember').on('keypress', function(e) {
        if (e.keyCode == 13) {
            taskSearchMember();
        }
        $('#taskSearchMember').focus();
    });

    function taskSearchMember() {
        var project_code = "<?= $project['projectCode'] ?>";
        var search_tq = $("#taskSearchMember").val();
        var task_code = $("#v_task_code").html();
        $.post(base_url + "/project/task/searchmember", {
            search_tq: search_tq,
            task_code: task_code,
            project_code: project_code
        }, function(data) {
            if (data != 1) {
                $("#task-search-result").html(data);
            } else {
                alertMe("danger", "Please enter a valid email address.");
            }
        });
    }

    function inviteMemberToTask(user_id, task_id, project_code) {
        $.post(base_url + "/project/task/inviteMember", {
            user_id: user_id,
            task_id: task_id,
            project_code: project_code
        }, function(data) {
            if (data == 1) {
                alertMe("success", "Task shared");
            } else if (data == 2) {
                alertMe("warning", "Already exist in task.");
            } else {
                alertMe("danger", "Something went wrong");
            }
            var type = $("#v_task_status").html();
            getTaskDetails(task_id, type);
            displayUserTask();
        });
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>