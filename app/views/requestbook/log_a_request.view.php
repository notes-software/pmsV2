<div class="modal fade" id="log_a_request_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <input type="hidden" id="u_branch_id" name="u_branch_id">
            <div class="modal-header">
                <h4 class="modal-title">New Request</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col" style="">
                        <div class="form-group mb-3">
                            <label for="rb_requested_by">Requested by:</label>
                            <input type="text" id="rb_requested_by" class="form-control form-control-alternative" placeholder="type a name" autocomplete="off">
                        </div>
                        <div class="form-group mb-0">
                            <label for="rb_description">Description:</label>
                            <textarea class="form-control form-control-alternative" id="rb_description" rows="5" placeholder="Description format" style="resize: none;"></textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" onclick="saveNewRequestBook()">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>