<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../../layouts/head.php'; ?>

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
                <li class="breadcrumb-item active">Product Category</li>
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
                        <a href="<?= route('/product') ?>" type="button" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                        <a type="button" class="btn btn-default btn-sm" onclick="createCategoryModal()">Add Category</a>
                        <button type="button" id="delete_category_btn" class="btn btn-default btn-sm" onclick="deleteCategory()">Delete selected category</button>
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
                        <th>NAME</th>
                        <th style="width: 90px;">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($prodCategories as $category) {
                        $status = ($category['status'] == 0) ? '<span style="color: green;">Active</span>' : '<span style="color: red;">Inactive</span>';
                    ?>
                        <tr>
                            <td class="no-sort text-center"><input type='checkbox' name='checkbox' value='<?= $category['id'] ?>'></td>
                            <td class="no-sort text-center">
                                <a style="color: #605e5e;" onclick="editProductCategory('<?= $category['id'] ?>')"><i class="far fa-edit"></i></a>
                            </td>
                            <td><?= $category['name'] ?></td>
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

<?php include_once __DIR__ . '/create_category_modal.php'; ?>
<?php include_once __DIR__ . '/edit_category_modal.php'; ?>

<script type="text/javascript">
    $(function() {
        $("#employee_tbl").DataTable();
    });

    function createCategoryModal() {
        $("#create_category_modal").modal('show');
    }

    function editProductCategory(id) {
        $.post(base_url + "/product/category/view/" + id, {}, function(data) {
            var category = JSON.parse(data);
            $("#edit_category_name").val(category.name);
            $("#edit_category_id").val(category.id);
            $("#edit_category_modal").modal('show');
        });
    }

    function deleteCategory() {
        var checkedValues = $('input:checkbox:checked').map(function() {
            return this.value;
        }).get();

        id = [];
        if (checkedValues == "") {
            alertMe("Aw Snap!", "No Selected Category", "warning");
        } else {
            var retVal = confirm("Are you sure to delete?");
            if (retVal) {
                $("#delete_category_btn").prop('disabled', true);
                $("#delete_category_btn").html("<span class='fa fa-spin fa-spinner'></span> Loading ...");

                $.post(base_url + "/product/category/delete", {
                    id: checkedValues
                }, function(data) {
                    $("#delete_category_btn").prop('disabled', true);
                    $("#delete_category_btn").html("Delete selected category");

                    location.reload();
                });
            }
        }
    }
</script>

<?php require  __DIR__ . '/../../layouts/footer.php'; ?>