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
                <li class="breadcrumb-item active">Master Data</li>
                <li class="breadcrumb-item active">Products</li>
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
                        <a href="<?= route('/product/create') ?>" type="button" class="btn btn-default btn-sm">Add Product</a>
                        <button type="button" id="delete_product_btn" class="btn btn-default btn-sm" onclick="deleteProduct()">Delete selected product</button>
                    </div>
                </div>
                <div class="card-tools">
                    <div class="align-left">
                        <a href="<?= route('/product/category') ?>" type="button" class="btn btn-default btn-sm">Product Category</a>
                        <a href="<?= route('/product/unit') ?>" type="button" class="btn btn-default btn-sm">Product Unit</a>
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
                        <th>BARCODE</th>
                        <th>NAME</th>
                        <th>CATEGORY</th>
                        <th>SELLING PRICE</th>
                        <th style="width: 90px;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($products as $product) {
                        $status = ($product['status'] == 0) ? '<span style="color: green;">Active</span>' : '<span style="color: red;">Inactive</span>';

                        $category = (!empty($product['product_category'][0]))
                            ? $product['product_category'][0]['name']
                            : $product['product_category']['name'];
                    ?>
                        <tr>
                            <td class="no-sort text-center"><input type='checkbox' name='checkbox' value='<?= $product['id'] ?>'></td>
                            <td class="no-sort text-center">
                                <a href="<?= route('/product/view', $product['id']) ?>" style="color: #605e5e;"><i class="far fa-edit"></i></a>
                            </td>
                            <td><?= $product['code'] ?></td>
                            <td><?= $product['name'] ?></td>
                            <td><?= $category ?></td>
                            <td><?= $product['selling_price'] ?></td>
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