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
                <li class="breadcrumb-item"><a href="<?= route('/repack') ?>">Product Repack</a></li>
                <li class="breadcrumb-item active">Repack Detail</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    <?= alert_msg(); ?>
</section>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- Default box -->
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                        <div class="card-tools">
                            <div class="align-left">
                                <a href="<?= route('/repack') ?>" type="button" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="align-right">
                            <b>STATUS: </b><?= ($repacks['status'] == 0) ? '<span style="color: orange;">PENDING</span>' : '<span style="color: green;">FINISH</span>'; ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="hidden" id="rpck_status" value="<?= $repacks['status'] ?>">
                                <input type="hidden" id="rpck_id" value="<?= $repacks['id'] ?>">
                                <label for="sku_number">REFERENCE CODE</label>
                                <input type="text" class="form-control" name="ref_code" id="ref_code" value="<?= $repacks['ref_code'] ?>" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="selling_price">Date</label>
                                <input type="date" class="form-control" name="date" id="date" value="<?= $repacks['date'] ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks" cols="10" rows="3"><?= $repacks['remarks'] ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div style="display: flex;flex-direction: row;justify-content: start;align-items: center;">
                                                <div class="card-tools">
                                                    <div>ORIGINAL ITEM</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="name">Product Name</label>
                                                        <select class="form-control select2" name="original_product" id="original_product" style="width: 100%;" onchange="selectOrigUnit()">
                                                            <option value="">-- select product --</option>
                                                            <?php
                                                            foreach ($products as $product) {

                                                                $selected = ($product['id'] == $repacks['from_item']) ? 'selected' : '';

                                                                echo "<option " . $selected . " value='" . $product['id'] . "'>" . $product['name'] . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="original_unit">Unit</label>
                                                        <select class="form-control unit" name="original_unit" id="original_unit" style="width: 100%;">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="original_qty">Qty</label>
                                                        <input type="number" class="form-control" name="original_qty" id="original_qty" onkeyup="computeQty()" value="<?= number_format($repacks['from_qty']) ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                                                <div class="card-tools">
                                                    <div>REPACK TO ITEM</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="to_unit">Unit</label>
                                                        <select class="form-control unit" name="to_unit" id="to_unit" style="width: 100%;" onchange="computeQty()">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="to_qty">Qty</label>
                                                        <input type="number" class="form-control" name="to_qty" id="to_qty" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <?php if ($repacks['status'] == 0) { ?>
                    <div class="card-footer" style="border-top: 1px solid rgba(0,0,0,.125);">
                        <div style="display: flex;flex-direction: row;justify-content: end;">
                            <div class="card-tools">
                                <div class="align-left">
                                    <button class="btn btn-success btn-sm" onclick="finishRepack()">Finish Repack</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-footer-->
                <?php } ?>

            </div>
        </div>
        <!-- /.card -->
    </div>
</section>
<script>
    $(document).ready(function() {
        $('#original_product').select2();
        isFinished();
        selectOrigUnit();
    });

    function isFinished() {
        var rpck = $("#rpck_status").val();
        if (rpck == 1) {
            $("#ref_code").prop('disabled', true);
            $("#date").prop('disabled', true);
            $("#original_product").prop('disabled', true);
            $("#original_unit").prop('disabled', true);
            $("#original_qty").prop('disabled', true);
            $("#to_unit").prop('disabled', true);
            $("#to_qty").prop('disabled', true);
            $("#remarks").prop('disabled', true);
        }
    }

    function selectOrigUnit() {
        var product = $("#original_product").val();
        $.post(base_url + "/purchase/selectUnit", {
            product: product
        }, function(data) {
            $("#original_unit").html(data);
            $("#to_unit").html(data);

            var orig_unit = '<?= $repacks['from_unit'] ?>';
            var to_unit = '<?= $repacks['to_unit'] ?>';
            $("#original_unit").val(orig_unit);
            $('#original_unit').select2().trigger('change');
            $("#to_unit").val(to_unit);
            $('#to_unit').select2().trigger('change');
        });
    }

    function finishRepack() {
        var ref_code = $("#ref_code").val();
        var date = $("#date").val();
        var original_product = $("#original_product").val();
        var original_unit = $("#original_unit").val();
        var original_qty = $("#original_qty").val();
        var rpck_id = $("#rpck_id").val();
        var to_unit = $("#to_unit").val();
        var to_qty = $("#to_qty").val();
        var remarks = $("#remarks").val();

        $.post(base_url + "/repack/edit/finish", {
            ref_code: ref_code,
            date: date,
            original_product: original_product,
            original_unit: original_unit,
            original_qty: original_qty,
            to_unit: to_unit,
            to_qty: to_qty,
            remarks: remarks,
            rpck_id: rpck_id
        }, function(data) {
            var respnse = JSON.parse(data);
            if (respnse.id == 1) {
                alertMe("success", "Repack saved successfully.");
                location.reload();
            } else {
                location.reload();
            }
        });
    }

    function computeQty() {
        var original_product = $("#original_product").val();
        var original_unit = $("#original_unit").val();
        var original_qty = $("#original_qty").val();
        var to_unit = $("#to_unit").val();
        var to_qty = $("#to_qty").val();

        $.post(base_url + "/repack/compute", {
            original_product: original_product,
            original_unit: original_unit,
            original_qty: original_qty,
            to_unit: to_unit,
            to_qty: to_qty
        }, function(data) {
            $("#to_qty").val(data);
        });
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>