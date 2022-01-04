<div class="modal fade" id="edit_users_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= route('/settings/users/update') ?>" method="POST">
                <input type="hidden" id="u_users_id" name="u_users_id">
                <div class="modal-header">
                    <h4 class="modal-title">Branch Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="u_email">E-mail</label>
                            <input type="u_email" class="form-control" id="u_email" name="u_email" autocomplete="off" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="u_name">Name</label>
                            <select class="form-control select2" name="u_name" id="u_name" style="width: 100%;">
                                <option value="">-- select employee --</option>
                                <?php
                                foreach ($employees as $employee) {
                                    echo "<option value='" . $employee['fullname'] . "'>" . $employee['fullname'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="u_roles">User Access</label>
                            <select class="form-control select2" name="u_roles" id="u_roles" style="width: 100%;">
                                <option value="">-- select access --</option>
                                <?php
                                foreach ($roles as $role) {
                                    echo "<option value='" . $role['id'] . "'>" . $role['role'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="u_username">Username</label>
                            <input type="text" class="form-control" id="u_username" name="u_username" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="u_password">Password</label>
                            <input type="password" class="form-control" id="u_password" name="u_password" autocomplete="off">
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