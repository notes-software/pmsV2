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
                <li class="breadcrumb-item"><a href="<?= route('/purchase') ?>">Receiving</a></li>
                <li class="breadcrumb-item active">New Receiving</li>
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
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                        <div class="card-tools">
                            <div class="align-left">
                                <a href="<?= route('/receiving') ?>" type="button" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ref_code">Reference code</label>
                                        <input type="hidden" id="rr_id">
                                        <input type="text" class="form-control" name="ref_code" id="ref_code" value="<?= $refCode ?>" disabled autofocus>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="po_number">PO Number</label>
                                        <select class="form-control select2" name="po_number" id="po_number" style="width: 100%;" onchange="getSelectedPoData()">
                                            <option value="">-- select po request --</option>
                                            <?php
                                            foreach ($outStandingPo as $po) {
                                                if (!empty($po['purchase_detail_count'])) {
                                                    echo "<option value='" . $po['id'] . "'>" . $po['ref_code'] . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control" name="date" id="date">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks" cols="10" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex;flex-direction: row;justify-content: start;align-items: center;">
                                <div class="card-tools">
                                    <div>ITEMS TO RECEIVE</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 0px;">
                            <table id="employee_tbl" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="no-sort" style="width: 15px;"></th>
                                        <th>PRODUCT</th>
                                        <th>SUPPLIER</th>
                                        <th style="width: 90px;">UNIT</th>
                                        <th class='text-right' style="width: 100px;">COST</th>
                                        <th class='text-right' style="width: 90px;">QTY</th>
                                        <th class='text-right' style="width: 100px;">BALANCE</th>
                                    </tr>
                                </thead>
                                <tbody id="rr_details">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                                <div class="card-tools">
                                    <div>ITEMS RECEIVED</div>
                                </div>
                                <div class="card-tools">
                                    <div class="align-right">
                                        <a type="button" class="btn btn-success btn-sm" onclick="finishRR()" id="finishEntryBtn"> Finish Receiving</a>
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
                                        <th>SUPPLIER</th>
                                        <th style="width: 90px;">UNIT</th>
                                        <th class='text-right' style="width: 100px;">COST</th>
                                        <th class='text-right' style="width: 90px;">QTY</th>
                                        <th class='text-right' style="width: 100px;">BALANCE</th>
                                    </tr>
                                </thead>
                                <tbody id="received_items_details">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
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
            remarks: remarks
        }, function(data) {
            $("#po_header_id").val(data);
        });
    }

    function addPoItem() {
        var po_header_id = $("#po_header_id").val();
        var product = $("#product").val();
        var unit = $("#unit").val();
        var qty = $("#qty").val();

        $.post(base_url + "/purchase/additem", {
            po_header_id: po_header_id,
            product: product,
            unit: unit,
            qty: qty
        }, function(data) {
            if (data == 1) {
                displayPoItems(po_header_id);
            }
        });
    }

    function displayPoItems(po_header_id) {
        $.post(base_url + "/receiving/displayPoItems", {
            po_header_id: po_header_id
        }, function(data) {
            var items = JSON.parse(data);
            var skin_body = "";
            var balance = 0;
            for (var td = 0; td < items.data.length; td++) {
                var itemList = items.data[td];
                const qty = parseFloat(itemList.qty);
                const cost = parseFloat(itemList.cost)
                const sumBalance = cost.toFixed(2) * qty.toFixed(2);

                skin_body += '<tr>';
                skin_body += '<td class="no-sort text-center"><a type="button" class="btn btn-primary btn-sm" onclick="receiveItem(\'' + itemList.id + '\')"><i class="fas fa-plus"></i></a></td>';
                skin_body += "<td>" + itemList.product + "</td>";
                skin_body += "<td>" + itemList.supplier + "</td>";
                skin_body += "<td>" + itemList.unit + "</td>";
                skin_body += "<td class='text-right'>" + cost.toFixed(2) + "</td>";
                skin_body += "<td class='text-right'>" + qty.toFixed(2) + "</td>";
                skin_body += "<td class='text-right'>" + sumBalance.toFixed(2) + "</td>";
                skin_body += "</tr>";

                balance += sumBalance;
            }

            skin_body += '<tr style="background-color: #fff;"><td colspan="6" style="text-align: right;"><b>Total Unreceived PO based in this receiving:</b></td><td class="text-right">' + balance.toFixed(2) + '</td></tr>';

            $("#rr_details").html(skin_body);
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

    function getSelectedPoData() {
        var po_id = $("#po_number").val();
        displayPoItems(po_id);
    }

    function receiveItem(itemId) {
        var ref_code = $("#ref_code").val();
        var date = $("#date").val();
        var remarks = $("#remarks").val();
        var po_header_id = $("#po_number").val();

        if (date != "") {
            $("#ref_code").prop('disabled', true);
            $("#date").prop('disabled', true);
            $("#remarks").prop('disabled', true);
            $("#po_number").prop('disabled', true);
            $.post(base_url + "/receiving/receiveItem", {
                ref_code: ref_code,
                date: date,
                remarks: remarks,
                po_header_id: po_header_id,
                po_detail_id: itemId
            }, function(data) {
                $("#rr_id").val(data);
                displayPoItems(po_header_id);
                getReceivedItems(data);
            });
        } else {
            alertMe("warning", " Please set a date");
        }
    }

    function getReceivedItems(rr_header_id) {
        $.post(base_url + "/receiving/displayReceivedItems", {
            rr_header_id: rr_header_id
        }, function(data) {
            var rcvd_items = JSON.parse(data);
            var skin_receivedItem_body = "";
            var balance = 0;
            for (var td = 0; td < rcvd_items.data.length; td++) {
                var rcvdItemList = rcvd_items.data[td];
                const qty = parseFloat(rcvdItemList.qty);
                const cost = parseFloat(rcvdItemList.cost)
                const sumBalance = cost.toFixed(2) * qty.toFixed(2);

                skin_receivedItem_body += '<tr>';
                skin_receivedItem_body += '<td class="no-sort text-center"><a type="button" class="btn btn-danger btn-sm" onclick="removeItem(\'' + rcvdItemList.id + '\', \'' + rr_header_id + '\')"><i class="fas fa-trash"></i></a></td>';
                skin_receivedItem_body += "<td>" + rcvdItemList.product + "</td>";
                skin_receivedItem_body += "<td>" + rcvdItemList.supplier + "</td>";
                skin_receivedItem_body += "<td>" + rcvdItemList.unit + "</td>";
                skin_receivedItem_body += "<td class='text-right'>" + cost.toFixed(2) + "</td>";
                skin_receivedItem_body += "<td class='text-right'>" + qty.toFixed(2) + "</td>";
                skin_receivedItem_body += "<td class='text-right'>" + sumBalance.toFixed(2) + "</td>";
                skin_receivedItem_body += "</tr>";

                balance += sumBalance;
            }

            skin_receivedItem_body += '<tr style="background-color: #fff;"><td colspan="6" style="text-align: right;"><b>Total:</b></td><td class="text-right">' + balance.toFixed(2) + '</td></tr>';

            $("#received_items_details").html(skin_receivedItem_body);
        });
    }

    function removeItem(rr_detail_id, rr_header_id) {
        var po_header_id = $("#po_number").val();
        $.post(base_url + "/receiving/removeReceivedItem", {
            rr_detail_id: rr_detail_id
        }, function(data) {
            if (data == 1) {
                displayPoItems(po_header_id);
                getReceivedItems(rr_header_id);
            } else {
                alertMe("warning", "There was something wrong!");
            }
        });
    }

    function finishRR() {
        var rr_id = $("#rr_id").val();
        $.post(base_url + "/receiving/finish", {
            rr_id: rr_id
        }, function(data) {
            if (data == 1) {
                location.reload();
            } else {
                alertMe("warning", "There was something wrong!");
            }
        });
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>