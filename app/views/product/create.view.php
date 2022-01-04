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
                <li class="breadcrumb-item"><a href="<?= route('/employee') ?>">Products</a></li>
                <li class="breadcrumb-item active">New Product</li>
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
        <form action="<?= route('/product/save') ?>" method="POST">
            <div class="card-header">
                <div style="display: flex;flex-direction: row;justify-content: space-between;align-items: center;">
                    <div class="card-tools">
                        <div class="align-left">
                            <a href="<?= route('/product') ?>" type="button" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="barcode">BARCODE</label>
                            <input type="text" class="form-control" name="barcode" id="barcode" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" class="form-control" name="name" id="name" autofocus>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select class="form-control select2" name="category" id="category" style="width: 100%;">
                                <option value="">-- select category --</option>
                                <?php
                                foreach ($categories as $category) {
                                    echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="selling_price">Selling Price</label>
                            <input type="text" class="form-control" name="selling_price" id="selling_price" autofocus>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer" style="border-top: 1px solid rgba(0,0,0,.125);">
                <div style="display: flex;flex-direction: row;justify-content: end;">
                    <div class="card-tools">
                        <div class="align-left">
                            <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-footer-->
        </form>
    </div>
    <!-- /.card -->
</section>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>