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
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bullhorn text-danger"></i>
                            Due and Past Due Tasks ( <span class="text-success">IN PROGESS</span> )
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="card bg-gradient-warning">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                CODE: KPLM2659
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <i class="far fa-calendar-check"></i> 12/14/2020
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <pre class="mt-2" style="white-space: pre-wrap;font-family: inherit;font-size: inherit;padding: 0px;color: inherit;background: inherit;background : transparent;">purchase module add if close kag e open liwat wla ga reset ang mga textboxes,it should be reset before openning modal</pre>
                                    </div>

                                    <div class="col-md-6">
                                        PRIORITY: MEDIUM
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="#" style="color: inherit;">View Task</a>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-lg-5">

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span style="font-size: 16px;">Welcome back, <?= Auth::user('fullname') ?></span>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span style="font-size: 16px;">Current Version (v1.1)</span>
                                <button type="button" class="btn btn-default btn-sm">What's new?</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>

            </div>
        </div>
    </div>
</section>

<?php require 'layouts/footer.php'; ?>