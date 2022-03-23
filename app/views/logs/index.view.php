<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../layouts/head.php'; ?>

<style>
    .welcome-msg {
        margin-top: 10%;
    }

    .table td,
    .table th {
        padding: 0.45rem !important;
    }

    .table th {
        background-color: #eaecf0 !important;
    }

    .table tr,
    .table th,
    .table td {
        border: 1px solid #a2a9b1 !important;
    }

    .table tr:hover {
        background-color: #ffffc8;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0"><?= ucfirst($pageTitle) ?></h1>
            <p class="text-muted" style="font-size: 12px;">
                <span style="color: #000; font-weight: 400;">
                    This is the list of all the system activity
                </span>
            </p>
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
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 15px;"></th>
                    <th>Log</th>
                    <th>Module</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($logs as $log) :
                ?>
                    <tr>
                        <td class="text-center"><?= $log['log_id'] ?></td>
                        <td><?= $log['log'] ?></td>
                        <td><?= $log['module'] ?></td>
                        <td><?= $log['date'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-2">
            <?= $links ?>
        </div>
    </div>
</section>

<?php include_once __DIR__ . '/add_remarks.view.php'; ?>
<?php include_once __DIR__ . '/log_a_request.view.php'; ?>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>