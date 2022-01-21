<?php

use App\Core\Auth;
use App\Core\Request;

require __DIR__ . '/../layouts/head.php'; ?>

<style>
    .welcome-msg {
        margin-top: 10%;
    }

    .note-title {
        background-color: transparent;
        border: none;
        padding: 0;
        outline: none;
        overflow: hidden;
        resize: none;
        vertical-align: top;
        width: 100%;
    }

    .cardNote {
        border: 0.5px solid #a3a3a38f
    }

    .card-title {
        font-weight: 600;
        color: #222222;
    }

    .card-text {
        color: #222222;
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

<?php include_once __DIR__ . '/create_request_modal.php'; ?>
<?php include_once __DIR__ . '/edit_request_modal.php'; ?>

<script type="text/javascript">
    $(function() {
        getUserNote();
    });

    function addNote() {
        $.post(base_url + "/notebook/new", {}, function(data, status) {
            $("#note_container").prepend(data);
            $("#note_title").focus();
        });
    }

    function saveNote() {
        var title = $("#note_title").val();
        var content = $("#note_content").html();
        $.post(base_url + "/notebook/save", {
            title: title,
            content: content
        }, function(data, status) {
            if (data == 1) {
                alertMe('success', 'Note saved.');
            } else {
                alertMe('danger', 'Error in saving your note.');
            }
            getUserNote();
        });
    }

    function searchInNotes() {
        var search_q = $("#notes_search_input").val();
        if (search_q != "") {
            $.post(base_url + "/notebook/search", {
                search_q: search_q
            }, function(data) {
                $("#note_container").html(data);
            });
        } else {
            getUserNote();
        }
    }

    function deleteNote(id) {
        var res = confirm("Are you sure you want to delete this note?");
        if (res) {
            $.post(base_url + "/notebook/delete", {
                id: id
            }, function(data) {
                if (data == 1) {
                    getUserNote();
                    alertMe("success", "Note deleted");
                } else {
                    alertMe("danger", "Error in deleting notes!");
                }
            });
        }
    }

    function getUserNote() {
        $.post(base_url + "/notebook/datas", {}, function(data) {
            $("#note_container").html(data);
        });
    }

    function updateNote(note_id) {
        var title = $("#update_note_title_" + note_id).val();
        var content = $("#update_note_content_" + note_id).html();
        $.post(base_url +
            "/notebook/update", {
                title: title,
                content: content,
                id: note_id
            },
            function(data, status) {
                if (data == 1) {
                    alertMe('success', 'Note updated.');
                } else {
                    alertMe('danger', 'Error in updating your note.');
                }

                $('[data-toggle="tooltip"]').tooltip('hide');
                getUserNote();
            });
    }

    function cancelNote() {
        getUserNote();
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>