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
                <li class="breadcrumb-item active">Supplier</li>
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
                        <button type="button" class="btn btn-default btn-sm" onclick="create_supplier_modal()">Add Supplier</button>
                        <button type="button" id="delete_supplier_btn" class="btn btn-default btn-sm" onclick="deleteSupplier()">Delete selected supplier</button>
                    </div>
                </div>

                <h3 class="card-title"></h3>
            </div>
        </div>
        <div class="card-body">
            <table id="supplier_tbl" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="no-sort" style="width: 15px;"></th>
                        <th class="no-sort" style="width: 15px;"></th>
                        <th>SUPPLIER NAME</th>
                        <th>ADDRESS</th>
                        <th>CONTACT</th>
                        <th style="width: 90px;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($suppliers as $supplier) {
                        $status = ($supplier['status'] == 0) ? '<span style="color: green;">Active</span>' : '<span style="color: red;">Inactive</span>';
                    ?>
                        <tr>
                            <td class="no-sort text-center"><input type='checkbox' name='checkbox' value='<?= $supplier['id'] ?>'></td>
                            <td class="no-sort text-center">
                                <a style="color: #605e5e;" onclick="editSupplier('<?= $supplier['id'] ?>')"><i class="far fa-edit"></i></a>
                            </td>
                            <td><?= $supplier['name'] ?></td>
                            <td><?= $supplier['address'] ?></td>
                            <td><?= $supplier['contact'] ?></td>
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

<?php include_once __DIR__ . '/create_supplier_modal.php'; ?>
<?php include_once __DIR__ . '/edit_supplier_modal.php'; ?>

<script type="text/javascript">
    $(function() {
        $("#supplier_tbl").DataTable();
    });

    function create_supplier_modal() {
        $("#supplier_name").val('');
        $("#address").val('');
        $("#contact").val('');
        $("#tin").val('');

        $("#create_supplier_modal").modal('show');
    }

    function editSupplier(id) {
        $.post(base_url + "/supplier/view/" + id, {}, function(data) {
            var supplier = JSON.parse(data);
            $("#u_supplier_name").val(supplier.name);
            $("#u_address").val(supplier.address);
            $("#u_contact").val(supplier.contact);
            $("#u_tin").val(supplier.tin);
            $("#u_supplier_id").val(supplier.id);
            $("#edit_supplier_modal").modal('show');
        });
    }

    function deleteSupplier() {
        var checkedValues = $('input:checkbox:checked').map(function() {
            return this.value;
        }).get();

        id = [];
        if (checkedValues == "") {
            alertMe("Aw Snap!", "No selected supplier", "warning");
        } else {
            var retVal = confirm("Are you sure to delete?");
            if (retVal) {
                $("#delete_supplier_btn").prop('disabled', true);
                $("#delete_supplier_btn").html("<span class='fa fa-spin fa-spinner'></span> Loading ...");

                $.post(base_url + "/supplier/delete", {
                    id: checkedValues
                }, function(data) {
                    $("#delete_supplier_btn").prop('disabled', true);
                    $("#delete_supplier_btn").html("Delete selected supplier");

                    location.reload();
                });
            }
        }
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>