<a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
    <i class="fas fa-chevron-up"></i>
</a>

</div>

<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
    </div>
    <!-- Default to the left -->
    <strong>&copy; 2021-<?= date('Y'); ?> <a href="https://sprnva.space/">Sprnva</a>.</strong> All rights reserved
</footer>

<?php include_once __DIR__ . '/../create_note_modal.php'; ?>
<?php include_once __DIR__ . '/../edit_note_modal.php'; ?>
<?php include_once __DIR__ . '/../changelog.view.php'; ?>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-light">
    <!-- Control sidebar content goes here -->
    <div style="margin-top: calc(2.9rem + 5px); color: #222222;">
        <div class="col-md-12">
            <div style="display: flex;flex-direction: row;justify-content: end;align-items: center;">

                <div class="btn-group" style="width: 100%;">
                    <input type="search" class="form-control form-control-sm" id="notes_search_input" placeholder="search your notes" onkeyup="searchInNotes()" autocomplete="off">
                </div>
                <div class="btn-group ml-1">
                    <a onclick="addNote()" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="bottom" data-original-title="Add New Note"><i class="far fa-plus-square p-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-2 msg_chat_scroll_steady" id="note_container" style="height: 590px;">

        </div>
    </div>
</aside>
<!-- /.control-sidebar -->

<script type="text/javascript">
    $(function() {
        getUserNote();
    });

    function addNote() {

        var title = $("#create_note_title").html('');
        var content = $("#create_note_content").html('');

        $("#add_modal_btns").html('<a onclick="saveNote()" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="bottom" data-original-title="Save Changes"><i class="fas fa-save p-1"></i> Save Changes</a>');

        $("#create_note_modal").modal({
            show: true
        });
    }

    function saveNote() {
        var title = $("#create_note_title").html();
        var content = $("#create_note_content").html();
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
            $("#create_note_modal").modal('hide');
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

                $('[data-toggle="tooltip"]').tooltip('hide');
                $("#edit_note_modal").modal('hide');
            });
        }
    }

    function getUserNote() {
        $.post(base_url + "/notebook/datas", {}, function(data) {
            $("#note_container").html(data);
        });
    }

    function updateNote(note_id) {
        var title = $("#update_note_title").html();
        var content = $("#update_note_content").html();
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
                $("#edit_note_modal").modal('hide');
            });
    }

    function cancelNote() {
        getUserNote();
    }

    function openNote(id) {
        var title = $("#update_note_title_" + id).html();
        var content = $("#update_note_content_" + id).html();

        // console.log(content);
        $("#note_id").val(id);
        $("#update_note_title").html(title);
        $("#update_note_content").html(content);

        $("#modal_btns").html(' <a onclick="updateNote(\'' + id + '\')" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="bottom" data-original-title="Save Changes"><i class="fas fa-save p-1"></i></a><a onclick="deleteNote(\'' + id + '\')" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="bottom" data-original-title="Delete Note"><i class="far fa-trash-alt p-1"></i></a>');

        $("#edit_note_modal").modal({
            show: true
        });
    }
</script>

</div>

<div id="modalLoader" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" style='border-radius: 15px;'>
            <div class="modal-body">
                <h4 style="margin: 0px;">
                    <center><span class='fa fa-spin fa-spinner'></span> <span id='page_loader_content'></span></center>
                </h4>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap 4 -->
<script src="<?= public_url('/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= public_url('/assets/adminlte/plugins/select2/js/select2.full.min.js') ?>"></script>
<script src="<?= public_url('/assets/sprnva/js/multiple-select.js') ?>"></script>
<script src="<?= public_url('/assets/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
<script src="<?= public_url('/assets/adminlte/plugins/datatables/jquery.dataTables.js') ?>"></script>
<script src="<?= public_url('/assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js') ?>"></script>
<script src="<?= public_url('/assets/adminlte/plugins/toastr/toastr.min.js') ?>"></script>
<script src="<?= public_url('/assets/adminlte/plugins/moment/moment.min.js') ?>"></script>
<script src="<?= public_url('/assets/adminlte/plugins/fullcalendar/main.js') ?>"></script>

<!-- AdminLTE App -->
<script src="<?= public_url('/assets/adminlte/js/adminlte.min.js') ?>"></script>

<script type="text/javascript">
    $('.select2').select2();
</script>
</body>

</html>