<div class="modal fade" id="create_branch_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= route('/branch/save') ?>" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Request</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="branch_name">Requested by</label>
                            <input type="text" class="form-control" name="branch_name" id="branch_name" autofocus>
                        </div>

                        <div class="form-group">
                            <label for="desc">Description</label>
                            <textarea name="desc" id="desc" rows="3" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>