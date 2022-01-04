<div class="modal fade" id="create_position_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= route('/employee/position/save') ?>" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Position</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="branch_name">Position Name</label>
                            <input type="text" class="form-control" name="position_name" id="position_name" autofocus>
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