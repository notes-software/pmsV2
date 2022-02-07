<?php

use App\Core\Auth;
use App\Core\Request;

require 'layouts/head.php'; ?>

<style>
    .welcome-msg {
        margin-top: 10%;
    }

    .table td,
    .table th {
        border-top: 0px solid #dee2e6;
    }

    .tasksSelect:hover {
        padding: 3px;
        background: #ebebeb;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="row mb-0">
        <div class="col-sm-6">
            <h1 class="m-0"><?= ucfirst($pageTitle) ?></h1>
            <p class="text-muted" style="font-size: 14px;">
                <span class="text-green"><?= date("l,") ?></span>
                <?= date("M d, Y") ?>
            </p>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Starter Page</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</section>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card pt-3" style="background: #e7f2ff;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row" style="display: flex;flex-direction: row;justify-content: center;justify-items: center;color: #fff;">
                                    <div class="col-md-3">
                                        <div class="card" style="background-image: radial-gradient(circle at 0 2%,#283e63,#172337 99%);">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <h1><?= $totalProjects ?></h1>
                                                        <div class="d-flex" style="flex-direction: column;">
                                                            <span>PROJECTS</span>
                                                            <small style="color: #c1bfbf;">[for you]</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card" style="background-image: radial-gradient(circle at 0 2%,#283e63,#172337 99%);">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <h1><?= $totalInProgressTask ?></h1>
                                                        <div class="d-flex" style="flex-direction: column;">
                                                            <span>TASK</span>
                                                            <small style="color: #c1bfbf;">[in progress]</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card" style="background-image: radial-gradient(circle at 0 2%,#283e63,#172337 99%);">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <h1><?= $totalTask ?></h1>
                                                        <div class="d-flex" style="flex-direction: column;">
                                                            <span>TASK</span>
                                                            <small style="color: #c1bfbf;">[to all projects]</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="display: flex;flex-direction: column;justify-content: center;">
                                <h1 style="font-weight:400;">Welcome back, <span class="text-muted"><?= Auth::user('fullname') ?></span></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bars text-dark"></i>
                            <b class="ml-2">Today's Task</b>
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <table class="table table-sm">
                            <tbody>
                                <?php
                                // dd(dueDateForthisWeek());
                                foreach (dueDateForthisWeek() as $task) :
                                    if (!empty($task['projects'])) {
                                        $detail = (!empty($task['projects'][0]))
                                            ? $task['projects'][0]
                                            : $task['projects'];

                                        $description = html_entity_decode($task['taskDescription']);
                                        $shortTaskDesc = substr($description, 0, 50);
                                        $taskTitleLen = (strlen($description) > 50) ? ' . . .' : '';
                                        $tasktitle = ($task['taskTitle'] != "") ? $task['taskTitle'] : $shortTaskDesc . $taskTitleLen;

                                        $projNameAcc = substr($detail['projectName'], 0, 13);
                                        $shortProjName = (strlen($detail['projectName']) > 13) ? $projNameAcc . '...' : $projNameAcc;

                                        if ($task['priority_stats'] == 0) {
                                            $priority_stats = "green";
                                            $prioName = "LOW";
                                        } else if ($task['priority_stats'] == 1) {
                                            $priority_stats = "orange";
                                            $prioName = "MEDIUM";
                                        } else {
                                            $priority_stats = "red";
                                            $prioName = "HIGH";
                                        }
                                ?>
                                        <tr class="tasksSelect">
                                            <td class="d-flex" style="flex-direction: row;justify-content: space-between;cursor: pointer;" onclick="window.location='project/<?= $task['projectCode'] ?>'">
                                                <div>
                                                    <span><?= $tasktitle ?></span>
                                                </div>
                                                <div>

                                                    <span class="badge bg-<?= $priority_stats ?>"><?= $prioName ?></span>
                                                    <span class="ml-2"><b><?= $shortProjName ?></b></span>
                                                    <span class="ml-2 text-muted"><?= date('M d, Y', strtotime($task['taskDueDate'])) ?></span>
                                                </div>
                                            </td>
                                        </tr>
                                <?php }
                                endforeach;
                                ?>
                            </tbody>
                        </table>






                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-lg-4">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="m-0" style="color: #000; font-family: 'myFirstFont';">Project Management System</h4>
                                <p class="text-muted" style="font-size: 14px;">Project management is the application of processes, methods, skills, knowledge and experience to achieve specific project objectives according to the project acceptance criteria within agreed parameters. Project management has final deliverables that are constrained to a finite timescale and budget.</p>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span style="font-size: 16px;">Current Version (v1.1)</span>
                                <button type="button" class="btn btn-default btn-sm" onclick="openChangeLog()">What's new?</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    function dueTaskClothe(list) {

        $deleteOption = (list.allow_delete == 1) ? '<span class="badge navbar-badge" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete task" onclick="deletetask(\'' + list.task_id + '\')"><i class="far fa-trash-alt" style="font-size: 13px;"></i></span>' : '';

        $viewOption = ($deleteOption == '') ? '5px' : '30';

        var task_member = "";
        var memTDCounter = 1;
        if (list.task_member != null) {
            for (var tmem = 0; tmem < list.task_member.length; tmem++) {
                var mmbr = list.task_member[tmem];
                if (tmem <= 3) {
                    task_member += "<img src='" + mmbr.member_avatar + "' style='width:20px; height: 20px;object-fit: cover;margin-left: 1px;' class='rounded-circle' data-toggle='tooltip' data-placement='bottom' title='' data-original-title='" + mmbr.member_name + "'>";
                } else {
                    if (memTDCounter == 1) {
                        task_member += '<span class="rounded-circle" style="margin-left: 1px;padding: 2px;font-size: 12px;background-color: #868686 !important;" data-toggle="tooltip" data-placement="bottom" data-original-title="+' + (list.task_member.length - 4) + ' more">+' + (list.task_member.length - 4) + '</span>';
                    }
                    memTDCounter++;
                }
            }
        }

        return '<div class="col-md-12 col-sm-12 col-12" id="' + list.task_id + '"><div class="row"><div class="info-box bg-' + list.priority_color + '"><div class="info-box-content"><div class="mt-1" style="cursor: default;">' + task_member + '</div><div class="d-flex" style="flex-direction: row;justify-content: space-between;"><div><small class="info-box-text mt-1"><i class="far fa-calendar-check"></i> Due: ' + list.date + '</small></div><div><small class="info-box-text mt-1">Code: ' + list.task_code + '</small></div></div>' + $deleteOption + '<span class="badge navbar-badge" data-toggle="tooltip" data-placement="bottom" data-original-title="View task" style="right: ' + $viewOption + ';" onclick="viewTask(\'' + list.task_id + '\', \'' + list.module + '\')"><i class="fas fa-eye" style="font-size: 13px;"></i></span><span class="info-box-text"><pre class="mt-1" style="white-space: pre-wrap;font-family: myFirstFont;font-size: inherit;padding: 0px;color: inherit;background: inherit;background : transparent;">' + list.task + '</pre></span></div></div></div></div>';
    }

    function openChangeLog() {
        $.get(base_url + "/whatsnew", {}, function(data) {
            $("#cl-content").html(data);
            $("#modal-change-log").modal({
                show: true,
                backdrop: 'static',
                keyboard: false
            });
        });
    }
</script>

<?php require 'layouts/footer.php'; ?>