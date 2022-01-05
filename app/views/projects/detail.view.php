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

    .cardDropOffs {
        border: 1px solid #c9c9c9;
    }

    .droptarget {
        height: 600px;
    }
</style>

<!-- Content Header (Page header) -->
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
                        <a href="<?= route('/project') ?>" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#create_branch_modal">New Task</button>
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#create_branch_modal">Project settings</button>
                    </div>
                </div>

                <div class="card-title">
                    <select class="form-control select2 " name="current_branch" id="current_branch" tabindex="-1" aria-hidden="true">
                        <option value="">-- select member --</option>
                        <option value="1">Bacolod Store</option>
                        <option value="2">Murcia Store</option>
                    </select>
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

                                <div class="card-tools">
                                    <div>
                                        TO DO'S
                                        <span class="badge badge-secondary navbar-badge">15</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-2 drops droptarget msg_chat_scroll" id="sortable1">

                            <div class="col-md-12 col-sm-12 col-12" id="315">
                                <div class="row">
                                    <div class="info-box bg-green">
                                        <div class="info-box-content">
                                            <small class="info-box-text"><i class="far fa-calendar-check"></i> Due: 12/14/2020</small>
                                            <span class="badge navbar-badge" data-toggle='tooltip' data-placement='bottom' data-original-title='Delete task'><i class="far fa-trash-alt" style="font-size: 13px;"></i></span>
                                            <span class="badge navbar-badge" data-toggle='tooltip' data-placement='bottom' data-original-title='View task' style="right: 30;"><i class="fas fa-eye" style="font-size: 13px;"></i></span>
                                            <span class="info-box-text">
                                                <pre class="mt-1" style="white-space: pre-wrap;font-family: 'myFirstFont';font-size: inherit;padding: 0px;color: inherit;background: inherit;background : transparent;">purchase module add if close kag e open liwat wla ga reset ang mga textboxes,it should be reset before openning modal</pre>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- END TODO'S -->

                <!-- IN PROGRESS -->
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="card cardDropOffs">
                        <div class="card-header">
                            <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">

                                <div class="card-tools">
                                    <div>
                                        IN PROGRESS
                                        <span class="badge badge-secondary navbar-badge">15</span>
                                    </div>
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
                    <div class="card cardDropOffs">
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
                    </div>
                </div>
                <!-- END DONE -->


            </div>
        </div>
    </div>
    <!-- /.card -->
</section>

<?php include_once __DIR__ . '/create_request_modal.php'; ?>
<?php include_once __DIR__ . '/edit_request_modal.php'; ?>

<script type="text/javascript">
    $(function() {
        draggable_task();
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

        $.post(base_url + "/projects/task", {
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

            console.log(task_data);

            // TO DO'S
            var todo_len = (task_data.todo == null) ? 0 : task_data.todo.length;
            if (todo_len > 0) {
                for (var td = 0; td < todo_len; ++td) {
                    var todo_list = task_data.todo[td];

                    var todo_task_member = "";
                    var memTDCounter = 1;
                    if (todo_list.task_member != null) {
                        for (var tmem = 0; tmem < todo_list.task_member.length; tmem++) {
                            var todo_mmbr = todo_list.task_member[tmem];
                            if (tmem <= 3) {
                                todo_task_member += "<img src='" + todo_mmbr.member_avatar + "' style='width:20px; height: 20px;object-fit: cover;' class='rounded-circle' data-toggle='tooltip' data-placement='bottom' title='' data-original-title='" + todo_mmbr.member_name + "'>";
                            } else {
                                if (memTDCounter == 1) {
                                    todo_task_member += '<div style="width: 20px;height: 20px;object-fit: cover;font-size: 12px;background-color: #868686 !important;" class="avatar rounded-circle" data-toggle="tooltip" data-placement="bottom" data-original-title="+' + (todo_list.task_member.length - 4) + '"><i class="fas fa-ellipsis-h"></i></div>';
                                }
                                memTDCounter++;
                            }
                        }
                    }

                    var todo_prio_stats;
                    if (todo_list.priority == 0) {
                        todo_prio_stats = "LOW";
                    } else if (todo_list.priority == 1) {
                        todo_prio_stats = "MEDIUM";
                    } else {
                        todo_prio_stats = "HIGH";
                    }

                    var delete_task = (todo_list.allow_delete == 1) ? "<small class='text-muted float-right' onclick='deletetask(" + todo_list.task_id + ")' style='color: red !important; cursor: default;'><a style='color: red;font-size: 12px;' data-toggle='tooltip' data-placement='bottom' data-original-title='Delete task'><i class='far fa-trash-alt'></i></a></small>" : "";

                    todo_cloth += "<div class='ui-state-default card mb-3 c_items' id='" + todo_list.task_id + "' style='font-size: 14px;margin-bottom: 5px !important;cursor: grab;box-shadow: 0 0;'><div class='card-header' style='border-left: 3px solid " + todo_list.priority_color + ";padding: 8px;'><div class='col-12' style='display: flex;flex-direction: row;padding: 0px;justify-content:space-between;'><small class='d-flex' style='flex-direction: row;align-items: center;'><a style='color: green;font-size: 12px;cursor: default;' data-toggle='tooltip' data-placement='bottom' data-original-title='View task' onclick='viewTask(\"" + todo_list.task_id + "\", \"TO DO\")'><i class='fas fa-eye'></i></a><label class='ml-1' style='border: 1px solid #ddd;border-radius: 5px;color: #1d81e8;padding-left: 3px; padding-right: 3px;margin-bottom: 0px;'><b><i class='far fa-calendar-check'></i> " + todo_list.date + "</b></label><div class='ml-1' style='cursor: default;'>" + todo_task_member + "</div></small>" + delete_task + "</div><div class='col-12' style='padding: 0px;'><div class='col-12' style='padding: 0px;'><small>CODE: " + todo_list.task_code + "</small><small class='ml-3'>PRIORITY: <span style='color:" + todo_list.priority_color + ";'>" + todo_prio_stats + "</small></div><pre style='white-space: pre-wrap;font-family: inherit;font-size: 14px;'>" + todo_list.task + "</pre></div></div></div>";
                }
            }
            $("#counter-todo").html(todo_len);
            $('#sortable1').html(todo_cloth);

            // IN PROGRESS
            var inprog_len = (task_data.inprogress == null) ? 0 : task_data.inprogress.length;
            if (inprog_len > 0) {
                for (var td = 0; td < inprog_len; ++td) {
                    var inprog_list = task_data.inprogress[td];

                    var inprog_task_member = "";
                    var memCounter = 1;
                    if (inprog_list.task_member != null) {
                        for (var ipmem = 0; ipmem < inprog_list.task_member.length; ipmem++) {
                            var inprog_mmbr = inprog_list.task_member[ipmem];
                            if (ipmem <= 3) {
                                inprog_task_member += "<img src='" + inprog_mmbr.member_avatar + "' style='width:20px; height: 20px;object-fit: cover;' class='rounded-circle' data-toggle='tooltip' data-placement='bottom' title='' data-original-title='" + inprog_mmbr.member_name + "'>";
                            } else {
                                if (memCounter == 1) {
                                    inprog_task_member += '<div style="width: 20px;height: 20px;object-fit: cover;font-size: 12px;background-color: #868686 !important;" class="avatar rounded-circle" data-toggle="tooltip" data-placement="bottom" data-original-title="+' + (inprog_list.task_member.length - 4) + '"><i class="fas fa-ellipsis-h"></i></div>';
                                }
                                memCounter++;
                            }
                        }
                    }

                    var inprog_prio_stats;
                    if (inprog_list.priority == 0) {
                        inprog_prio_stats = "LOW";
                    } else if (inprog_list.priority == 1) {
                        inprog_prio_stats = "MEDIUM";
                    } else {
                        inprog_prio_stats = "HIGH";
                    }

                    var delete_inprog_task = (inprog_list.allow_delete == 1) ? "<small class='text-muted float-right' onclick='deletetask(" + inprog_list.task_id + ")' style='color: red !important; cursor: default;'><a style='color: red;font-size: 12px;' data-toggle='tooltip' data-placement='bottom' data-original-title='Delete task'><i class='far fa-trash-alt'></i></a></small>" : "";

                    inprog_cloth += "<div class='ui-state-default card mb-3 c_items' id='" + inprog_list.task_id + "' style='font-size: 14px;margin-bottom: 5px !important;cursor: grab;box-shadow: 0 0;'><div class='card-header' style='border-left: 3px solid " + inprog_list.priority_color + ";padding: 8px;'><div class='col-12' style='display: flex;flex-direction: row;padding: 0px;justify-content:space-between;'><small class='d-flex' style='flex-direction: row;align-items: center;'><a style='color: green;font-size: 12px;cursor: default;' data-toggle='tooltip' data-placement='bottom' data-original-title='View task' onclick='viewTask(\"" + inprog_list.task_id + "\", \"IN PROGRESS\")'><i class='fas fa-eye'></i></a><label class='ml-1' style='border: 1px solid #ddd;border-radius: 5px;color: #1d81e8;padding-left: 3px; padding-right: 3px;margin-bottom: 0px;'><b><i class='far fa-calendar-check'></i> " + inprog_list.date + "</b></label><div class='ml-1' style='cursor: default;display: flex;flex-direction: row;'>" + inprog_task_member + "</div></small>" + delete_inprog_task + "</div><div class='col-12' style='padding: 0px;'><div class='col-12' style='padding: 0px;'><small>CODE: " + inprog_list.task_code + "</small><small class='ml-3'>PRIORITY: <span style='color:" + inprog_list.priority_color + ";'>" + inprog_prio_stats + "</small></div><pre style='white-space: pre-wrap;font-family: inherit;font-size: 14px;'>" + inprog_list.task + "</pre></div></div></div>";
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

                    var done_task_member = "";
                    var memIPCounter = 1;
                    if (done_list.task_member != null) {
                        for (var dmem = 0; dmem < done_list.task_member.length; dmem++) {
                            var done_mmbr = done_list.task_member[dmem];
                            if (dmem <= 3) {
                                done_task_member += "<img src='" + done_mmbr.member_avatar + "' style='width:20px; height: 20px;object-fit: cover;' class='rounded-circle' data-toggle='tooltip' data-placement='bottom' title='' data-original-title='" + done_mmbr.member_name + "'>";
                            } else {
                                if (memIPCounter == 1) {
                                    done_task_member += '<div style="width: 20px;height: 20px;object-fit: cover;font-size: 12px;background-color: #868686 !important;" class="avatar rounded-circle" data-toggle="tooltip" data-placement="bottom" data-original-title="+' + (done_list.task_member.length - 4) + '"><i class="fas fa-ellipsis-h"></i></div>';
                                }
                                memIPCounter++;
                            }
                        }
                    }

                    var done_prio_stats;
                    if (done_list.priority == 0) {
                        done_prio_stats = "LOW";
                    } else if (done_list.priority == 1) {
                        done_prio_stats = "MEDIUM";
                    } else {
                        done_prio_stats = "HIGH";
                    }



                    // console.log(done_list.finishDate + " == " + now);
                    if (done_list.finishDate == now) {
                        console.log(now);
                        count_today++;
                        done_cloth_today += "<div class='ui-state-default card mb-3 c_items' id='" + done_list.task_id + "' style='font-size: 14px;margin-bottom: 5px !important;box-shadow: 0 0;'><div class='card-header' style='border-left: 3px solid " + done_list.priority_color + ";padding: 8px;'><div class='col-12' style='display: flex;flex-direction: row;padding: 0px;justify-content:space-between;'><small class='d-flex' style='flex-direction: row;align-items: center;'><a style='color: green;font-size: 12px;cursor: default;' data-toggle='tooltip' data-placement='bottom' data-original-title='View task' onclick='viewTask(\"" + done_list.task_id + "\", \"DONE\")'><i class='fas fa-eye'></i></a><label class='ml-1' style='border: 1px solid #ddd;border-radius: 5px;color: #1d81e8;padding-left: 3px; padding-right: 3px;margin-bottom: 0px;'><b><i class='far fa-calendar-check'></i> " + done_list.date + "</b></label><div class='ml-1' style='cursor: default;'>" + done_task_member + "</div></small></div><div class='col-12' style='padding: 0px;'><div class='col-12' style='padding: 0px;'><small>CODE: " + done_list.task_code + "</small><small class='ml-3'>PRIORITY: <span style='color:" + done_list.priority_color + ";'>" + done_prio_stats + "</small></div><pre style='white-space: pre-wrap;font-family: inherit;font-size: 14px;'>" + done_list.task + "</pre></div></div></div>";
                    } else {
                        count_all++;
                        done_cloth_all += "<div class='ui-state-default card mb-3 c_items' id='" + done_list.task_id + "' style='font-size: 14px;margin-bottom: 5px !important;box-shadow: 0 0;'><div class='card-header' style='border-left: 3px solid " + done_list.priority_color + ";padding: 8px;'><div class='col-12' style='display: flex;flex-direction: row;padding: 0px;justify-content:space-between;'><small class='d-flex' style='flex-direction: row;align-items: center;'><a style='color: green;font-size: 12px;cursor: default;' data-toggle='tooltip' data-placement='bottom' data-original-title='View task' onclick='viewTask(\"" + done_list.task_id + "\", \"DONE\")'><i class='fas fa-eye'></i></a><label class='ml-1' style='border: 1px solid #ddd;border-radius: 5px;color: #1d81e8;padding-left: 3px; padding-right: 3px;margin-bottom: 0px;'><b><i class='far fa-calendar-check'></i> " + done_list.date + "</b></label><div class='ml-1' style='cursor: default;'>" + done_task_member + "</div></small></div><div class='col-12' style='padding: 0px;'><div class='col-12' style='padding: 0px;'><small>CODE: " + done_list.task_code + "</small><small class='ml-3'>PRIORITY: <span style='color:" + done_list.priority_color + ";'>" + done_prio_stats + "</small></div><pre style='white-space: pre-wrap;font-family: inherit;font-size: 14px;'>" + done_list.task + "</pre></div></div></div>";
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
            alertMe('User session is empty! Please log in again.', 'danger');
        }
    }

    function updateType(id, type) {
        var project_code = "<?= $project['projectCode'] ?>";
        $.post(base_url + "/projects/task/update", {
            id: id,
            type: type,
            project_code: project_code
        }, function(data, status) {
            displayUserTask();
            alertMe('Task updated', 'success');
        });
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>