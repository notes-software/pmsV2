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
                <li class="breadcrumb-item active">Product Repack</li>
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
                        <a href="<?= route('/repack/create') ?>" type="button" class="btn btn-default btn-sm">Add Repack</a>
                        <button type="button" id="delete_product_btn" class="btn btn-default btn-sm" onclick="deleteProduct()">Delete selected repack</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="employee_tbl" class="table table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2" class="no-sort" style="width: 15px;"></th>
                        <th rowspan="2" class="no-sort" style="width: 15px;"></th>
                        <th rowspan="2" style="vertical-align: middle;">REFERENCE CODE</th>
                        <th rowspan="2" style="vertical-align: middle;">ITEM</th>
                        <th colspan="2" style="text-align: center;">FROM</th>
                        <th colspan="2" style="text-align: center;">TO</th>
                        <th rowspan="2" style="vertical-align: middle;width: 90px;">STATUS</th>
                    </tr>
                    <tr>
                        <th style="text-align: right;width: 40px;">UNIT</th>
                        <th style="text-align: right;width: 40px;">QTY</th>
                        <th style="text-align: right;width: 40px;">UNIT</th>
                        <th style="text-align: right;width: 40px;">QTY</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($repacks as $repack) {
                        $status = ($repack['status'] == 0) ? '<span style="color: orange;">Pending</span>' : '<span style="color: green;">Finish</span>';
                    ?>
                        <tr>
                            <td class="no-sort text-center">
                                <?php
                                if ($repack['status'] == 0) {
                                ?>
                                    <input type='checkbox' name='checkbox' value='<?= $repack['id'] ?>'>
                                <?php } else { ?>
                                    <input type='hidden' value='<?= $repack['id'] ?>'>
                                <?php } ?>
                            </td>
                            <td class="no-sort text-center">
                                <a href="<?= route('/repack/view', $repack['id']) ?>" style="color: #605e5e;"><i class="far fa-edit"></i></a>
                            </td>
                            <td><?= $repack['ref_code'] ?></td>
                            <td><?= (!empty($repack['products'][0])) ? $repack['products'][0]['name'] : $repack['products']['name']; ?></td>
                            <td class="text-right"><?= getProductUnit($repack['from_unit']) ?></td>
                            <td class="text-right"><?= number_format($repack['from_qty'], 2) ?></td>
                            <td class="text-right"><?= getProductUnit($repack['to_unit']) ?></td>
                            <td class="text-right"><?= number_format($repack['to_qty'], 2) ?></td>
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

    function editBranch(id) {
        $.post(base_url + "/branch/view/" + id, {}, function(data) {
            var branch = JSON.parse(data);
            $("#u_branch_name").val(branch.name);
            $("#u_branch_id").val(branch.id);
            $("#edit_branch_modal").modal('show');
        });
    }

    function deleteProduct() {
        var checkedValues = $('input:checkbox:checked').map(function() {
            return this.value;
        }).get();

        id = [];
        if (checkedValues == "") {
            alertMe("Aw Snap!", "No Selected Product", "warning");
        } else {
            var retVal = confirm("Are you sure to delete?");
            if (retVal) {
                $("#delete_product_btn").prop('disabled', true);
                $("#delete_product_btn").html("<span class='fa fa-spin fa-spinner'></span> Loading ...");

                $.post(base_url + "/product/delete", {
                    id: checkedValues
                }, function(data) {
                    $("#delete_product_btn").prop('disabled', true);
                    $("#delete_product_btn").html("Delete selected product");

                    location.reload();
                });
            }
        }
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>