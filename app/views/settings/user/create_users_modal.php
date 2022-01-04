<div class="modal fade" id="create_users_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= route('/settings/users/save') ?>" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Add New User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" name="email" autocomplete="off" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <select class="form-control select2" name="name" id="name" style="width: 100%;">
                                <option value="">-- select employee --</option>
                                <?php
                                foreach ($employees as $employee) {
                                    echo "<option value='" . $employee['fullname'] . "'>" . $employee['fullname'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="roles">User Access</label>
                            <select class="form-control select2" name="roles" id="roles" style="width: 100%;">
                                <option value="">-- select access --</option>
                                <?php
                                foreach ($roles as $role) {
                                    echo "<option value='" . $role['id'] . "'>" . $role['role'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" autocomplete="off">
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