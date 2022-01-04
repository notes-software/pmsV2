<?php

use App\Core\Auth;
use App\Core\Request;

require 'layouts/head.php'; ?>

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
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Starter Page</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</section>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="col-12">
        <div class="d-flex flex-column align-items-center justify-content-center">
            <h2 class="mb-0 text-muted welcome-msg">Welcome back, <?= Auth::user('fullname') ?></h2>
            <p class="text-muted">
            <h3><?= selectTedBranch() ?></h3>
            </p>
        </div>
    </div>
</section>

<?php require 'layouts/footer.php'; ?>