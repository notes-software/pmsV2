<div class="modal fade" id="edit_permission_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= route('/settings/permission/update') ?>" method="POST">
                <input type="hidden" id="u_permission_id" name="u_permission_id">
                <div class="modal-header">
                    <h4 class="modal-title">Branch Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="u_title">Title</label>
                            <input type="text" class="form-control" name="u_title" id="u_title" autofocus>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="update_permission_btn">Update changes</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>