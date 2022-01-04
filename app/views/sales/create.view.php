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
                <li class="breadcrumb-item"><a href="<?= route('/purchase') ?>">Purchase</a></li>
                <li class="breadcrumb-item active">New Purchase</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    <?= alert_msg(); ?>
</section>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                        <div class="card-tools">
                            <div class="align-left">
                                <a href="<?= route('/purchase') ?>" type="button" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ref_code">Reference code</label>
                                <input type="text" class="form-control" name="ref_code" id="ref_code" value="<?= $refCode ?>" disabled autofocus>
                            </div>
                            <div class="form-group">
                                <label for="supplier">Supplier</label>
                                <select class="form-control select2" name="supplier" id="supplier" style="width: 100%;">
                                    <option value="">-- select supplier --</option>
                                    <?php
                                    foreach ($suppliers as $supplier) {
                                        echo "<option value='" . $supplier['id'] . "'>" . $supplier['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" name="date" id="date">
                            </div>
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea class="form-control" name="remarks" id="remarks" cols="10" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer" id="saveChangesFooter" style="border-top: 1px solid rgba(0,0,0,.125);">
                    <div style="display: flex;flex-direction: row;justify-content: end;">
                        <div class="card-tools">
                            <div class="align-left">
                                <button class="btn btn-primary btn-sm" onclick="savePO()" id="saveChangesBtn">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-footer-->

            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <input type="hidden" id="po_header_id">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="product">Product</label>
                                        <select class="form-control select2" name="product" id="product" style="width: 100%;" onchange="selectUnit()">
                                            <option value="">-- select product --</option>
                                            <?php
                                            foreach ($products as $product) {
                                                echo "<option value='" . $product['id'] . "'>" . $product['name'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="qty">Supplier Price</label>
                                        <input type="number" class="form-control" name="price" id="price">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="unit">Unit</label>
                                        <select class="form-control unit" name="unit" id="unit" style="width: 100%;">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="qty">Qty</label>
                                        <input type="number" class="form-control" name="qty" id="qty" autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" id="addItemFooter" style="border-top: 1px solid rgba(0,0,0,.125);">
                            <div style="display: flex;flex-direction: row;justify-content: end;align-items: center;">
                                <div class="card-tools">
                                    <div class="align-right">
                                        <a type="button" class="btn btn-primary btn-sm" onclick="addPoItem()" id="addItemBtn"> Add item</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex;flex-direction: row;justify-content: end;align-items: center;">
                                <div class="card-tools">
                                    <div class="align-right">
                                        <a href="<?= route('/purchase') ?>" type="button" class="btn btn-default btn-sm"><i class="fas fa-trash" style="color: red;"></i> Remove selected item</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 0px;">
                            <table id="employee_tbl" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="no-sort" style="width: 15px;"></th>
                                        <th>PRODUCT</th>
                                        <th style="width: 90px;">UNIT</th>
                                        <th class='text-right' style="width: 90px;">QTY</th>
                                        <th class='text-right' style="width: 100px;">COST</th>
                                        <th class='text-right' style="width: 100px;">BALANCE</th>
                                    </tr>
                                </thead>
                                <tbody id="po_details">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-footer" id="finishEntryFooter" style="border-top: 1px solid rgba(0,0,0,.125);">
                            <div style="display: flex;flex-direction: row;justify-content: end;align-items: center;">
                                <div class="card-tools">
                                    <div class="align-right">
                                        <a type="button" class="btn btn-success btn-sm" onclick="finishPo()" id="finishEntryBtn"> Finish Entry</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    $("#addItemFooter").hide();
    $("#finishEntryFooter").hide();

    $(document).ready(function() {
        $('#unit').select2();
        $('#supplier').select2();
        $('#product').select2();
    });

    function savePO() {
        var ref_code = $("#ref_code").val();
        var supplier = $("#supplier").val();
        var po_date = $("#date").val();
        var remarks = $("#remarks").val();

        $.post(base_url + "/purchase/save", {
            ref_code: ref_code,
            supplier: supplier,
            po_date: po_date,
            remarks: remarks,
        }, function(data) {
            var poResponse = JSON.parse(data);
            if (poResponse.validationError != null) {
                location.reload();
            } else {
                $("#po_header_id").val(poResponse.id);
                $("#saveChangesFooter").hide();
                $("#addItemFooter").show();
                $("#finishEntryFooter").show();
            }
        });
    }

    function addPoItem() {
        var po_header_id = $("#po_header_id").val();
        var product = $("#product").val();
        var unit = $("#unit").val();
        var qty = $("#qty").val();
        var price = $("#price").val();

        $.post(base_url + "/purchase/additem", {
            po_header_id: po_header_id,
            product: product,
            unit: unit,
            qty: qty,
            price: price
        }, function(data) {
            var dataResponse = JSON.parse(data);
            if (dataResponse.id == 1) {
                displayItems(po_header_id);
            }

            $("#saveChangesFooter").hide();
            $("#addItemFooter").show();
            $("#finishEntryFooter").show();
        });
    }

    function displayItems(po_header_id) {
        $.post(base_url + "/purchase/displayItems", {
            po_header_id: po_header_id
        }, function(data) {
            var items = JSON.parse(data);
            console.log(items.data);
            var skin_body = "";
            var balance = 0;
            for (var td = 0; td < items.data.length; td++) {
                var itemList = items.data[td];
                const qty = parseFloat(itemList.qty);
                const cost = parseFloat(itemList.cost)
                const sumBalance = cost.toFixed(2) * qty.toFixed(2);

                skin_body += '<tr>';
                skin_body += '<td class="no-sort text-center"><input type="checkbox" name="checkbox" value=""></td>';
                skin_body += "<td>" + itemList.product + "</td>";
                skin_body += "<td>" + itemList.unit + "</td>";
                skin_body += "<td class='text-right'>" + qty.toFixed(2) + "</td>";
                skin_body += "<td class='text-right'>" + cost.toFixed(2) + "</td>";
                skin_body += "<td class='text-right'>" + sumBalance.toFixed(2) + "</td>";
                skin_body += "</tr>";

                balance += sumBalance;
            }

            skin_body += '<tr style="background-color: #fff;"><td colspan="5" style="text-align: right;"><b>Total:</b></td><td>' + balance.toFixed(2) + '</td></tr>';

            $("#po_details").html(skin_body);

            $("#saveChangesFooter").hide();
            $("#addItemFooter").show();
            $("#finishEntryFooter").show();
        });
    }

    function selectUnit() {
        var product = $("#product").val();
        $.post(base_url + "/purchase/selectUnit", {
            product: product
        }, function(data) {
            $("#unit").html(data);
        });
    }

    function finishPo() {
        var poHeaderId = $("#po_header_id").val();
        $.post(base_url + "/purchase/finish", {
            poHeaderId: poHeaderId
        }, function(data) {
            if (data == 1) {
                window.location.href = base_url + "/purchase";
            } else {
                alertMe("warning", "There was something wrong!");
            }
        });
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>