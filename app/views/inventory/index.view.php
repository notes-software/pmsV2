<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../layouts/head.php'; ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0"><?= ucfirst($pageTitle) ?></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Report</li>
                <li class="breadcrumb-item active">Inventory</li>
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
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" id="po_header_id">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="product">Product Category</label>
                                <select class="form-control select2" name="product_cat" id="product_cat" style="width: 100%;">
                                    <option value="">-- select product --</option>
                                    <?php
                                    foreach ($prodCategories as $cat) {
                                        echo "<option value='" . $cat['id'] . "'>" . $cat['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unit">Date</label>
                                <input type="date" class="form-control unit" ame="date" id="date" style="width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer" id="addItemFooter" style="border-top: 1px solid rgba(0,0,0,.125);">
                    <div style="display: flex;flex-direction: row;justify-content: end;align-items: center;">
                        <div class="card-tools">
                            <div class="align-right">
                                <a type="button" class="btn btn-default btn-sm" onclick="generateInv()" id="addItemBtn"> Generate Report</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="padding: 0px;">
                    <table id="inv_table" class="table table-bordered">

                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function generateInv() {
        var product_cat = $("#product_cat").val();
        var date = $("#date").val();

        $.post(base_url + "/report/inventory", {
            product_cat: product_cat,
            date: date
        }, function(data) {
            $("#inv_table").html(data);
            // $("#delete_unit_btn").prop('disabled', true);
            // $("#delete_unit_btn").html("Delete selected unit");

            // location.reload();
        });
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>