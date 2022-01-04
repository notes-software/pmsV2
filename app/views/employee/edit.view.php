<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../layouts/head.php'; ?>

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
                <li class="breadcrumb-item"><a href="<?= route('/employee') ?>">Employee</a></li>
                <li class="breadcrumb-item active">Edit Employee</li>
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
        <form action="<?= route('/employee/update', $employee['id']) ?>" method="POST">
            <div class="card-header">
                <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                    <div class="card-tools">
                        <div class="align-left">
                            <a href="<?= route('/employee') ?>" type="button" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <h6>Status: Active</h6>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="employee_number">Employee #</label>
                            <input type="text" class="form-control" name="employee_number" id="employee_number" value="<?= $employee['ref_code'] ?>" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="employee_contact">Contact #</label>
                            <input type="text" class="form-control" name="employee_contact" id="employee_contact" value="<?= $employee['contact'] ?>" autofocus>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="employee_fullname">Fullname</label>
                            <input type="text" class="form-control" name="employee_fullname" id="employee_fullname" value="<?= $employee['fullname'] ?>" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="employee_date_hired">Date Hired</label>
                            <input type="date" class="form-control" name="employee_date_hired" id="employee_date_hired" value="<?= $employee['datehired'] ?>" autofocus>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="employee_address">Address</label>
                            <input type="text" class="form-control" name="employee_address" id="employee_address" value="<?= $employee['address'] ?>" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="employee_position">Position</label>
                            <select class="form-control select2" name="employee_position" id="employee_position" style="width: 100%;">
                                <option value="">-- select position --</option>
                                <?php
                                foreach ($positions as $position) {
                                    $selected = ($employee['position_id'] == $position['id']) ? 'selected' : '';
                                    echo "<option " . $selected . " value='" . $position['id'] . "'>" . $position['position'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer" style="border-top: 1px solid rgba(0,0,0,.125);">
                <div style="display: flex;flex-direction: row;justify-content: end;">
                    <div class="card-tools">
                        <div class="align-left">
                            <button type="submit" class="btn btn-primary btn-sm">Update changes</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-footer-->
        </form>
    </div>
    <!-- /.card -->
</section>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>