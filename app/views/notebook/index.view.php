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
                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#create_branch_modal">Add note</button>
                    </div>
                </div>

                <h3 class="card-title"></h3>
            </div>
        </div>
        <div class="card-body">
            <div class="card-columns" id="note_container">

                <div class="card cardNote">
                    <div class="card-body">
                        <textarea class="card-title note-title" rows="1" placeholder="Title" maxlength="999" dir="ltr" style="height: 20px;font-size: 14px; font-style: bolder; font-family: inherit;margin-bottom: 0px;" id="update_note_title_47">git pull using .sh</textarea>

                        <div class="card-text note-content mb-2" rows="1" contenteditable="true" placeholder="Take a note…" maxlength="19999" dir="ltr" style="font-size: 12px; font-family: inherit;white-space: pre-wrap;-webkit-user-modify: read-write-plaintext-only;" id="update_note_content_47">https://gist.github.com/fahim0173/6545d76cc0b5b80d9d8e76fb3a60cd1d</div>

                        <div style="display: flex;flex-direction: row;justify-content: space-between;"><a href="#" onclick="updateNote(47)" style="font-size: 12px;">Done</a><a href="#" onclick="deleteNote('47')" style="color: red;font-size: 12px;" title="delete note"><i class="far fa-trash-alt"></i></a>
                        </div>
                    </div>
                </div>

                <div class="card cardNote">
                    <div class="card-body">
                        <textarea class="card-title note-title" rows="1" placeholder="Title" maxlength="999" dir="ltr" style="height: 20px;font-size: 14px; font-style: bolder; font-family: inherit;margin-bottom: 0px;" id="update_note_title_43">GITLAB MARKDOWN</textarea>

                        <div class="card-text note-content mb-2" rows="1" contenteditable="true" placeholder="Take a note…" maxlength="19999" dir="ltr" style="font-size: 12px; font-family: inherit;white-space: pre-wrap;-webkit-user-modify: read-write-plaintext-only;" id="update_note_content_43">https://docs.gitlab.com/ee/user/markdown.html</div>

                        <div style="display: flex;flex-direction: row;justify-content: space-between;"><a href="#" onclick="updateNote(43)" style="font-size: 12px;">Done</a><a href="#" onclick="deleteNote('43')" style="color: red;font-size: 12px;" title="delete note"><i class="far fa-trash-alt"></i></a>
                        </div>
                    </div>
                </div>

                <div class="card cardNote">
                    <div class="card-body">
                        <textarea class="card-title note-title" rows="1" placeholder="Title" maxlength="999" dir="ltr" style="height: 20px;font-size: 14px; font-style: bolder; font-family: inherit;margin-bottom: 0px;" id="update_note_title_35">REACT-NATIVE COMMAND</textarea>

                        <div class="card-text note-content mb-2" rows="1" contenteditable="true" placeholder="Take a note…" maxlength="19999" dir="ltr" style="font-size: 12px; font-family: inherit;white-space: pre-wrap;-webkit-user-modify: read-write-plaintext-only;" id="update_note_content_35">install node_modules
                            npm install

                            create new project
                            npx react-native init &lt;project name&gt;

                            start metro
                            npx react-native start

                            run android
                            npx react-native run-android

                            run IOS
                            npx react-native run-ios</div>

                        <div style="display: flex;flex-direction: row;justify-content: space-between;"><a href="#" onclick="updateNote(35)" style="font-size: 12px;">Done</a><a href="#" onclick="deleteNote('35')" style="color: red;font-size: 12px;" title="delete note"><i class="far fa-trash-alt"></i></a>
                        </div>
                    </div>
                </div>

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
        $("#branch_tbl").DataTable();
    });

    function editBranch(id) {
        $.post(base_url + "/branch/view/" + id, {}, function(data) {
            var branch = JSON.parse(data);
            $("#u_branch_name").val(branch.name);
            $("#u_branch_id").val(branch.id);
            $("#edit_branch_modal").modal('show');
        });
    }

    function deleteBranch() {
        var checkedValues = $('input:checkbox:checked').map(function() {
            return this.value;
        }).get();

        id = [];
        if (checkedValues == "") {
            alertMe("Aw Snap!", "No Selected Branch", "warning");
        } else {
            var retVal = confirm("Are you sure to delete?");
            if (retVal) {
                $("#delete_branch_btn").prop('disabled', true);
                $("#delete_branch_btn").html("<span class='fa fa-spin fa-spinner'></span> Loading ...");

                $.post(base_url + "/branch/delete", {
                    id: checkedValues
                }, function(data) {
                    $("#delete_branch_btn").prop('disabled', true);
                    $("#delete_branch_btn").html("Delete selected branch");

                    location.reload();
                });
            }
        }
    }
</script>

<?php require  __DIR__ . '/../layouts/footer.php'; ?>