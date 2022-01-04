<?php

use App\Core\Auth;
use App\Core\Request;

$disabled = '';
if ($rrData['status'] == 1) {
    $disabled = 'disabled';
}

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
                <li class="breadcrumb-item"><a href="<?= route('/receiving') ?>">Receiving</a></li>
                <li class="breadcrumb-item active">Receiving Details</li>
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
                        <div class="align-right">
                            <b>STATUS: </b><?= ($rrData['status'] == 0) ? '<span style="color: orange;">PENDING</span>' : '<span style="color: green;">FINISH</span>'; ?>
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
                                        <input type="hidden" id="rr_id" value="<?= $rrData['id'] ?>">
                                        <input type="hidden" id="rr_status" value="<?= $rrData['status'] ?>">
                                        <input type="hidden" id="rr_supplier" value="<?= $rrData['supplier']['name'] ?>">
                                        <input type="text" class="form-control" name="ref_code" id="ref_code" value="<?= $rrData['ref_code'] ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="po_number">PO Number</label>
                                        <select class="form-control select2" name="po_number" id="po_number" style="width: 100%;" onchange="getSelectedPoData()" disabled>
                                            <?php
                                            echo "<option value='" . $outStandingPo['id'] . "'>" . $outStandingPo['ref_code'] . "</option>";
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control" name="date" id="date" value="<?= $rrData['date'] ?>" <?= $disabled ?>>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" name="remarks" id="remarks" cols="10" rows="3" <?= $disabled ?>><?= $rrData['remarks'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <?php
                if ($rrData['status'] == 0) {
                ?>
                    <div class="card-footer" id="updateChangesFooter" style="border-top: 1px solid rgba(0,0,0,.125);">
                        <div style="display: flex;flex-direction: row;justify-content: end;">
                            <div class="card-tools">
                                <div class="align-left">
                                    <button class="btn btn-primary btn-sm" onclick="updatePO()" id="updateRRChangesBtn">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <!-- /.card-footer-->
            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if ($rrData['status'] == 0) {
                    ?>
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
                    <?php } ?>

                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                                <div class="card-tools">
                                    <div>ITEMS RECEIVED</div>
                                </div>
                                <div class="card-tools">
                                    <div class="align-right">
                                        <a href="<?= route('/receiving/print', $rrData['id']) ?>" target="_blank" class="btn btn-default btn-sm"><i class="fas fa-print"></i> Print Receiving</a>

                                        <?php
                                        if ($rrData['status'] == 0) {
                                        ?>
                                            <a type="button" class="btn btn-success btn-sm" onclick="finishRR()" id="finishEntryBtn"><i class="fas fa-check"></i> Finish Receiving</a>
                                        <?php } ?>
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
        var po_header_id = $("#po_number").val();
        var rr_id = $("#rr_id").val();
        var rr_status = $("#rr_status").val();
        if (rr_status == 0) {
            displayPoItems(po_header_id);
        }
        getReceivedItems(rr_id);
    });

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
            $.post(base_url + "/receiving/receiveItem", {
                ref_code: ref_code,
                date: date,
                remarks: remarks,
                po_header_id: po_header_id,
                po_detail_id: itemId
            }, function(data) {
                displayPoItems(po_header_id);
                getReceivedItems(data);
            });
        } else {
            alertMe("warning", " Please set a date");
        }
    }

    function getReceivedItems(rr_header_id) {
        var rr_status = $("#rr_status").val();
        var rr_supplier = $("#rr_supplier").val();
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

                var removeItem = "";
                if (rr_status == 0) {
                    removeItem = '<a type="button" class="btn btn-danger btn-sm" onclick="removeItem(\'' + rcvdItemList.id + '\', \'' + rr_header_id + '\')"><i class="fas fa-trash"></i></a>';
                }

                skin_receivedItem_body += '<tr>';
                skin_receivedItem_body += '<td class="no-sort text-center">' + removeItem + '</td>';
                skin_receivedItem_body += "<td>" + rcvdItemList.product + "</td>";
                skin_receivedItem_body += "<td>" + rr_supplier + "</td>";
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