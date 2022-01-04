<div class="modal fade" id="create_supplier_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= route('/supplier/save') ?>" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Supplier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="supplier_name">Supplier Name</label>
                            <input type="text" class="form-control" name="supplier_name" id="supplier_name" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" name="address" id="address" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="contact">Contact</label>
                            <input type="text" class="form-control" name="contact" id="contact" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="tin">TIN</label>
                            <input type="text" class="form-control" name="tin" id="tin" autocomplete="off">
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