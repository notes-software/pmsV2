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
                <li class="breadcrumb-item"><a href="<?= route('/') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Transactions</li>
                <li class="breadcrumb-item active">Purchase</li>
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
                        <a href="<?= route('/purchase/create') ?>" type="button" class="btn btn-default btn-sm">Add New Purchase</a>
                        <button type="button" id="delete_unit_btn" class="btn btn-default btn-sm" onclick="deleteUnit()">Delete selected purchase</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="employee_tbl" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="no-sort" style="width: 15px;"></th>
                        <th class="no-sort" style="width: 15px;"></th>
                        <th class="no-sort" style="width: 15px;"></th>
                        <th style="width: 300px;">REFERENCE CODE</th>
                        <th>SUPPLIER</th>
                        <th>REMARKS</th>
                        <th style="width: 90px;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($purchases as $po) {
                        $status = ($po['status'] == 0) ? '<span style="color: orange;">Pending</span>' : '<span style="color: green;">Finish</span>';
                    ?>
                        <tr>
                            <td class="no-sort text-center">
                                <?php if ($po['status'] == 0) { ?>
                                    <input type='checkbox' name='checkbox' value='<?= $po['id'] ?>'>
                                <?php } else { ?>
                                    <input type='hidden' value='<?= $po['id'] ?>'>
                                <?php } ?>
                            </td>
                            <td class="no-sort text-center">
                                <a href="<?= route('/purchase/view', $po['id']) ?>" style="color: #605e5e;"><i class="far fa-edit"></i></a>
                            </td>
                            <td class="no-sort text-center">
                                <a href="<?= route('/purchase/print', $po['id']) ?>" style="color: #605e5e;" target="_blank"><i class="fas fa-print"></i></a>
                            </td>
                            <td><?= $po['ref_code'] ?></td>
                            <td><?= (!empty($po['supplier'][0])) ? $po['supplier'][0]['name'] : $po['supplier']['name']; ?></td>
                            <td><?= $po['remarks'] ?></td>
                            <td style="width: 90px;"><?= $status ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
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
    $(function() {
        $("#employee_tbl").DataTable();
    });

    function createUnitModal() {
        $("#create_unit_modal").modal('show');
    }

    function editProductUnit(id) {
        $.post(base_url + "/product/unit/view/" + id, {}, function(data) {
            var unit = JSON.parse(data);
            $("#edit_unit_name").val(unit.name);
            $("#edit_unit_id").val(unit.id);
            $("#edit_unit_category").val(unit.category);
            $("#edit_unit_category").select2().trigger('change');
            $("#edit_unit_qty").val(unit.qty);

            $("#edit_unit_modal").modal('show');
        });
    }

    function deleteUnit() {
        var checkedValues = $('input:checkbox:checked').map(function() {
            return this.value;
        }).get();

        id = [];
        if (checkedValues == "") {
            alertMe("Aw Snap!", "No Selected Unit", "warning");
        } else {
            var retVal = confirm("Are you sure to delete?");
            if (retVal) {
                $("#delete_unit_btn").prop('disabled', true);
                $("#delete_unit_btn").html("<span class='fa fa-spin fa-spinner'></span> Loading ...");

                $.post(base_url + "/product/unit/delete", {
                    id: checkedValues
                }, function(data) {
                    $("#delete_unit_btn").prop('disabled', true);
                    $("#delete_unit_btn").html("Delete selected unit");

                    location.reload();
                });
            }
        }
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>