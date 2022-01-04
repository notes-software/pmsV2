<div class="modal fade" id="create_roles_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= route('/settings/roles/save') ?>" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Roles</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" autofocus autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="permissions">Permissions</label>
                            <select class='form-control multiSelect' id='permissions' name='permissions[]' multiple required style='color: black; width: 100%;'>
                                <?php
                                foreach ($permissions as $permission) {
                                    echo '<option value=' . $permission['id'] . '>' . $permission['title'] . '</option>';
                                }
                                ?>
                            </select>
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