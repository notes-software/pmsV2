<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../layouts/head.php'; ?>

<style>
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

    .card-title {
        font-weight: 600;
        color: #222222;
    }

    .card-text {
        color: #222222;
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
    <div class="row">

        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-body p-0">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</section>

<?php include_once __DIR__ . '/add_task_modal.php'; ?>
<?php include_once __DIR__ . '/task_info_modal.php';
?>

<script type="text/javascript">
    $(function() {
        showCalendar();
    });

    function showCalendar() {
        /* initialize the calendar
        -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date()
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear()

        var Calendar = FullCalendar.Calendar;
        var Draggable = FullCalendar.Draggable;

        var containerEl = document.getElementById('external-events');
        var checkbox = document.getElementById('drop-remove');
        var calendarEl = document.getElementById('calendar');

        var calendar = new Calendar(calendarEl, {
            dayMaxEventRows: true,
            views: {
                timeGrid: {
                    dayMaxEventRows: 3 // adjust to 6 only for timeGridWeek/timeGridDay
                }
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            themeSystem: 'standard',
            //Random default events
            events: base_url + '/mycalendar/tasks',
            // editable: true,
            // droppable: true, // this allows things to be dropped onto the calendar !!!
            // drop: function(info) {
            //     console.log(info);
            //     // is the "remove after drop" checkbox checked?
            //     if (checkbox.checked) {
            //         // if so, remove the element from the "Draggable Events" list
            //         info.draggedEl.parentNode.removeChild(info.draggedEl);
            //     }
            // },
            dateClick: function(info) {
                // console.log(info);
                // alert(info.dateStr);
                var calDate = new Date(info.dateStr).toDateString();
                $('#inputDate').val(info.dateStr);
                $('#calendarDate').html(calDate);

                $("#calendar_add_task_modal").modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false
                });
            },

            eventClick: function(info) {
                viewTask(info.event.id, info.event.extendedProps.type, info.event.extendedProps.projCode);
            },
            displayEventTime: false,
        });

        calendar.render();
    }

    function addNewCalendarTask() {
        var created_date = $("#inputDate").val();
        var task_date = $("#cal_task_date").val();
        var project = $("#cal_project").val();
        var task_status = $("#cal_task_status").val();
        var task_title = $("#task_title").val();
        var task_description = $("#cal_task_description").html();

        if (task_status == '' || task_description == '') {
            alertMe("warning", "Some of the fields is blank, please fill in the fields.");
        } else {
            $.post(base_url + "/mycalendar/tasks/add", {
                created_date: created_date,
                due_date: task_date,
                task_title: task_title,
                taskDescription: task_description,
                priority_status: task_status,
                projectCode: project
            }, function(data, status) {
                if (data == 1) {
                    alertMe('success', 'Successfully Saved!');
                    $("#cal_task_description").html('');
                } else {
                    alertMe('danger', 'Error saving!');
                }

                showCalendar();
                $("#calendar_add_task_modal").modal('hide');
            });
        }
    }

    function viewTask(id, type, project_code) {
        getTaskDetails(id, type, project_code);
        $("#task_info_modal").modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }

    function getTaskDetails(id, type, project_code) {
        $.post(base_url + "/mycalendar/tasks/detail", {
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
                    $("#v_task_title").val(tsk_dt.task_title);
                    $("#v_task_desc").html(tsk_dt.task);

                    $("#v_task_projectName").html(tsk_dt.projectName);

                    if (tsk_dt.task_type == "DONE") {
                        $("#markAsDone").html('');
                    } else {
                        $("#markAsDone").html('<a onclick="markAsDone(\'' + id + '\', \'' + project_code + '\')" class="btn btn-success btn-sm" data-dismiss="modal">Mark as Done</a>');
                    }

                    if (tsk_dt.task_type != "DONE") {
                        $('#saveChangesBtn').html('<a href="#" class="btn btn-primary btn-sm float-right" onclick="save_task_details(\'' + project_code + '\')">Save Changes</a>');
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

    function markAsDone(id, project_code) {
        $.post(base_url + "/project/task/update", {
            id: id,
            type: 2,
            project_code: project_code
        }, function(data, status) {
            alertMe('success', 'Task updated');
            showCalendar();
            getTaskDetails(id, 2, project_code);
        });
    }

    function save_task_details(project_code) {
        var task_desc = $("#v_task_desc").html();
        var task_title = $("#v_task_title").val();
        var task_due_date = $("#task_v_date").val();
        var task_code = $("#v_task_code").html();
        var task_prio = $("#v_task_prio_status").val();
        $.post(base_url + "/project/task/update/details", {
            task_title: task_title,
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

            showCalendar();
            var task_id = $("#task_v_id").val();
            var task_type = $("#v_task_status").html();

            if (task_type == "IN PROGRESS") {
                var type_number = 1;
            } else if (task_type == "DONE") {
                var type_number = 2;
            } else {
                var type_number = 0;
            }

            getTaskDetails(task_id, type_number, project_code);
        });
    }

    $('#taskSearchMember').on('keypress', function(e) {
        if (e.keyCode == 13) {
            taskSearchMember();
        }
        $('#taskSearchMember').focus();
    });

    function taskSearchMember() {
        var project_code = $("#task_proj_code").val();
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

            if (type == "IN PROGRESS") {
                var type_number = 1;
            } else if (type == "DONE") {
                var type_number = 2;
            } else {
                var type_number = 0;
            }

            getTaskDetails(task_id, type_number, project_code);
            showCalendar();
        });
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>