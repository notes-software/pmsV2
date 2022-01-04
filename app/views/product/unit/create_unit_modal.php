<div class="modal fade" id="create_unit_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= route('/product/unit/save') ?>" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Add New unit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="unit_category">Category</label>
                            <select class="form-control select2" name="unit_category" id="unit_category" style="width: 100%;">
                                <option value="">-- select position --</option>
                                <?php
                                foreach ($prodCategories as $category) {
                                    echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="unit_name">Unit Name</label>
                            <input type="text" class="form-control" name="unit_name" id="unit_name" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="unit_qty">Unit Qty</label>
                            <input type="number" class="form-control" name="unit_qty" id="unit_qty" autofocus>
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