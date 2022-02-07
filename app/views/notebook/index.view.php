<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../layouts/head.php'; ?>

<style>
    .welcome-msg {
        margin-top: 10%;
    }

    .card-title {
        font-weight: 600;
        color: #222222;
    }

    .card-text {
        color: #222222;
    }

    @media (min-width: 576px) {
        .card-columns {
            column-count: 2;
        }
    }

    @media (min-width: 768px) {
        .card-columns {
            column-count: 3;
        }
    }

    @media (min-width: 992px) {
        .card-columns {
            column-count: 4;
        }
    }

    @media (min-width: 1200px) {
        .card-columns {
            column-count: 4;
        }
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
                <li class="breadcrumb-item active"><?= ucfirst($pageTitle) ?></li>
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
                        <button type="button" class="btn btn-default btn-sm" onclick="addNote()">Add note</button>
                    </div>
                </div>

                <span class="card-title">
                    <div class="input-group">
                        <input type="search" class="form-control" id="notes_search_input" placeholder="search your notes" onkeyup="searchInNotes()">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-lg btn-default" onclick="searchInNotes()">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="card-columns" id="note_container">

            </div>
        </div>
        <!-- /.card-body -->
        <!-- <div class="card-footer">
            Footer
        </div> -->
        <!-- /.card-footer-->
    </div>
    <!-- /.card -->
</section>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>