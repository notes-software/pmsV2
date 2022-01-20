<?php

use App\Core\Auth;
use App\Core\Request;

require 'layouts/head.php'; ?>

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
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bullhorn text-danger"></i>
                            Due and Past Due Tasks ( <span class="text-success">IN PROGESS</span> )
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <?php
                        // dd(dueDateForthisWeek());
                        foreach (dueDateForthisWeek() as $task) :
                            if (!empty($task['projects'])) {
                                $detail = (!empty($task['projects'][0]))
                                    ? $task['projects'][0]
                                    : $task['projects'];

                                $priority_stats = ($task['priority_stats'] == 0)
                                    ? "green"
                                    : (($task['priority_stats'] == 1) ? "orange" : "red");
                        ?>
                                <div class="col-md-12 col-sm-12 col-12" id="<?= $task['task_id'] ?>">
                                    <div class="row">
                                        <div class="info-box bg-<?= $priority_stats ?>">
                                            <div class="info-box-content">
                                                <div class="d-flex" style="flex-direction: column;justify-content: space-between;">
                                                    <div>
                                                        <small class="info-box-text mt-1"><i class="far fa-calendar-check"></i> Due: <?= date('Y-m-d', strtotime($task['taskDueDate'])) ?></small>
                                                    </div>
                                                    <div>
                                                        <small class="info-box-text mt-1">Task Code: <?= $task['task_code'] ?></small>

                                                        <small class="info-box-text mt-1">Project : <?= $detail['projectName'] ?></small>
                                                    </div>
                                                </div>
                                                <span class="badge navbar-badge" data-toggle="tooltip" data-placement="bottom" data-original-title="View task" style="right: 5px;" onclick="window.location='project/<?= $task['projectCode'] ?>'">
                                                    <i class="fas fa-eye" style="font-size: 13px;"></i>
                                                </span>
                                                <span class="info-box-text">
                                                    <pre class="mt-1 px-2" style="white-space: pre-wrap;font-family: myFirstFont;font-size: inherit;padding: 0px;color: inherit;background: #00000029;border-radius: 3px;"><?= html_entity_decode($task['taskDescription']) ?></pre>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        <?php }
                        endforeach;
                        ?>

                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-lg-5">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span style="font-size: 16px;">Welcome back, <?= Auth::user('fullname') ?></span>
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
                                <button type="button" class="btn btn-default btn-sm">What's new?</button>
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
</script>

<?php require 'layouts/footer.php'; ?>