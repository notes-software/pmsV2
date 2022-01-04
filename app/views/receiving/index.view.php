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
                <li class="breadcrumb-item active">Receiving</li>
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
                        <a href="<?= route('/receiving/create') ?>" type="button" class="btn btn-default btn-sm">Add New Receiving</a>
                        <button type="button" id="delete_unit_btn" class="btn btn-default btn-sm" onclick="deleteUnit()">Delete selected receiving</button>
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
                        <th style="width: 200px;">RR CODE</th>
                        <th style="width: 200px;">PO CODE</th>
                        <th>SUPPLIER</th>
                        <th>DATE</th>
                        <th class="text-right">AMOUNT</th>
                        <th style="width: 50px;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rrData as $rr) {

                        $status = ($rr['status'] == 0) ? '<span style="color: orange;">Pending</span>' : '<span style="color: green;">Finish</span>';

                        $checkDel = "";
                        if ($rr['status'] == 0) {
                            $checkDel = "<input type='checkbox' name='checkbox' value='" . $rr['id'] . "'>";
                        } else {
                            $checkDel = "<input type='hidden' value='" . $rr['id'] . "'>";
                        }

                        $amount = 0;
                        if (!empty($rr['receiving_detail'][0])) {
                            foreach ($rr['receiving_detail'] as $rrDt) {
                                $amount += $rrDt['supplier_price'] * $rrDt['qty'];
                            }
                        } else {
                            $amount += $rr['receiving_detail']['supplier_price'] * $rr['receiving_detail']['qty'];
                        }
                    ?>
                        <tr>
                            <td class="no-sort text-center">
                                <?= $checkDel ?>
                            </td>
                            <td class="no-sort text-center">
                                <a href='<?= route('/receiving/view', $rr['id']) ?>' style='color: #605e5e;'><i class='far fa-edit'></i></a>
                            </td>
                            <td class="no-sort text-center">
                                <a href="<?= route('/receiving/print', $rr['id']) ?>" style="color: #605e5e;" target="_blank"><i class="fas fa-print"></i></a>
                            </td>
                            <td><?= $rr['ref_code'] ?></td>
                            <td><?= (!empty($rr['purchase_header'][0])) ? $rr['purchase_header'][0]['ref_code'] : $rr['purchase_header']['ref_code']; ?></td>
                            <td><?= $rr['supplier']['name'] ?></td>
                            <td><?= date('Y-m-d', strtotime($rr['date'])) ?></td>
                            <td class="text-right"><?= $amount ?></td>
                            <td style="width: 50px;"><?= $status ?></td>
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