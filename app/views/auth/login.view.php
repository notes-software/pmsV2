<?php

use App\Core\App;
use App\Core\Request;
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='icon' href='<?= public_url('/favicon.ico') ?>' type='image/ico' />
    <title>
        <?= ucfirst($pageTitle) . " | " . App::get('config')['app']['name']; ?>
    </title>

    <link rel="stylesheet" href="<?= public_url('/assets/sprnva/css/bootstrap.min.css') ?>">

    <style>
        @font-face {
            font-family: Nunito;
            src: url("<?= public_url('/assets/sprnva/fonts/Nunito-Bold.ttf') ?>");
        }

        body {
            font-weight: 300;
            font-family: Nunito;
            color: #26425f;
            background: #fff;
        }

        .card {
            box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%);
            margin-bottom: 1rem;
            border-radius: .5rem !important;
        }
    </style>

    <script src="<?= public_url('/assets/sprnva/js/jquery-3.6.0.min.js') ?>"></script>
    <script src="<?= public_url('/assets/sprnva/js/popper.min.js') ?>"></script>
    <script src="<?= public_url('/assets/sprnva/js/bootstrap.min.js') ?>"></script>
</head>

<body>
    <div class="container px-5" style="margin-top: 10%;">
        <div class="row justify-content-md-center">
            <div class="col-md-7">
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <h2 class="mb-3 font-weight-normal">
                            <?= App::get('config')['app']['name'] ?>
                        </h2>
                    </div>
                </div>
                <p>Offers free unlimited projects and tasks with unlimited collaborators.</p>
                <ul>
                    <li>Tracks your productivity</li>
                    <li>Reminds your task for today</li>
                    <li>Reminds your past due tasks</li>
                    <li>Assign permission to users</li>
                    <li>Take note of your thoughts</li>
                    <li>Built from <a href="https://sprnva.space/" rel="nofollow noreferrer noopener" target="_blank">Sprnva Framework</a></li>
                </ul>
            </div>

            <div class="col-md-5">
                <div class="card mt-4" style="background-color: #fff; border: 0px; border-radius: 8px; box-shadow: none; border: 1px solid #bfbfbf;">
                    <div class="card-body">

                        <?= alert_msg(); ?>

                        <form method="POST" action="<?= route('/login') ?>">
                            <?= csrf() ?>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" autocomplete="off" autofocus value="<?= old('username') ?>">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" autocomplete="off">
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="<?= route('/forgot/password'); ?>" style="font-size: 18px;">
                                    <small id="emailHelp" class="form-text text-muted mb-1">Forgot password?</small>
                                </a>
                            </div>
                            <div class="form-group mt-2">
                                <button type="submit" class="btn btn-success btn-md btn-block">LOGIN</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <small>Don't have an account yet? <a href="<? //= route('/register') 
                                                                ?>">Register now</a></small> -->
            </div>
        </div>
    </div>

</body>

</html>