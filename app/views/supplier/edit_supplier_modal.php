<div class="modal fade" id="edit_supplier_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= route('/supplier/update') ?>" method="POST">
                <input type="hidden" id="u_supplier_id" name="u_supplier_id">
                <div class="modal-header">
                    <h4 class="modal-title">Supplier Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="u_supplier_name">Supplier Name</label>
                            <input type="text" class="form-control" name="u_supplier_name" id="u_supplier_name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="u_address">Address</label>
                            <input type="text" class="form-control" name="u_address" id="u_address" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="u_contact">Contact</label>
                            <input type="text" class="form-control" name="u_contact" id="u_contact" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="u_tin">TIN</label>
                            <input type="text" class="form-control" name="u_tin" id="u_tin" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="update_branch_btn">Update changes</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>