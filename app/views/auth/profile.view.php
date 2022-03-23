<?php require __DIR__ . '/../layouts/head.php';

use App\Core\Auth;
?>

<!-- Main content -->
<section class="content pt-2">
    <div class="col-12">
        <?= alert_msg(); ?>
    </div>

    <div class="col-12">
        <div class="row">
            <div class="col-md-4">
                <div>
                    <h5>Avatar</h5>
                    <small class="text-muted">Update your avatar.</small>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="background-color: #fff; border: 0px; border-radius: 8px; box-shadow: 0 4px 5px 0 rgba(0,0,0,0.2);">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 d-flex flex-row justify-content-center">
                                        <div class="form-group">
                                            <img src="<?= getUserAvatar($user_data['id']) ?>" style="height:150px;width: 150px;object-fit: cover;border-radius: 50%;">
                                        </div>
                                    </div>
                                    <div class="col-md-8 d-flex flex-column justify-content-center">
                                        <form method="POST" action="<?= route('/profile/uploadAvatar') ?>" enctype="multipart/form-data">
                                            <?= csrf() ?>

                                            <div class="form-group">
                                                <input type="file" class="form-control form-control-sm" name="upload_file" accept="image/png, image/jpeg, image/gif" />
                                            </div>

                                            <div class="d-flex justify-content-end"><button type="submit" class="btn btn-primary btn-sm text-rigth">Upload file</button></div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="row">
            <div class="col-md-4">
                <div class="mt-5">
                    <h5>Profile Information</h5>
                    <small class="text-muted">Update your account's profile information.</small>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-12  mt-5">
                        <div class="card" style="background-color: #fff; border: 0px; border-radius: 8px; box-shadow: 0 4px 5px 0 rgba(0,0,0,0.2);">
                            <div class="card-body">
                                <form method="POST" action="<?= route('/profile', Auth::user('id')) ?>">
                                    <?= csrf() ?>
                                    <div class="form-group">
                                        <label for="username">E-mail</label>
                                        <input type="email" class="form-control" name="email" autocomplete="off" value="<?= $user_data['email'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Name</label>
                                        <input type="text" class="form-control" name="name" autocomplete="off" value="<?= $user_data['fullname'] ?>">
                                    </div>
                                    <div class="d-flex justify-content-end"><button type="submit" class="btn btn-secondary btn-sm text-rigth">SAVE</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="row">
            <div class="col-md-4">
                <div class="mt-5">
                    <h5>Change Profile password</h5>
                    <small class="text-muted">Update your account's password.</small>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-12 mt-5">
                        <div class="card" style="background-color: #fff; border: 0px; border-radius: 8px; box-shadow: 0 4px 5px 0 rgba(0,0,0,0.2);">
                            <div class="card-body">

                                <form method="POST" action="<?= route('/profile/changepass') ?>">
                                    <?= csrf() ?>
                                    <div class="form-group">
                                        <label for="username">Old Password</label>
                                        <input type="password" class="form-control" name="old-password" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="username">New Password</label>
                                        <input type="password" class="form-control" name="new-password" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Confirm Password</label>
                                        <input type="password" class="form-control" name="confirm-password" autocomplete="off"">
                            </div>
                            <div class=" d-flex justify-content-end"><button type="submit" class="btn btn-success btn-sm text-rigth">UPDATE PASSWORD</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php require __DIR__ . '/../layouts/footer.php'; ?>