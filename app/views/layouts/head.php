<?php

use App\Core\App;
use App\Core\Auth;

if (empty($_SESSION['system']['branch_id'])) {
	$_SESSION['system']['branch_id'] = "";
}
?>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel='icon' href='<?= public_url('/favicon.ico') ?>' type='image/ico' />
	<title>
		<?= ucfirst($pageTitle) . " | " . App::get('config')['app']['name'] ?>
	</title>

	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
	<link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') ?>">

	<!-- Theme style -->
	<link rel="stylesheet" href="<?= public_url('/assets/adminlte/css/adminlte.min.css') ?>">
	<link rel="stylesheet" href="<?= public_url('/assets/adminlte/css/highlighter.css') ?>">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
	<link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/select2/css/select2.min.css') ?>">
	<link rel="stylesheet" href="<?= public_url('/assets/sprnva/css/multiple-select.css') ?>">
	<link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/toastr/toastr.min.css') ?>">
	<link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/sweetalert2/bootstrap-4.min.css') ?>">

	<style>
		@font-face {
			font-family: Nunito;
			src: url("<?= public_url('/assets/adminlte/fonts/Nunito-Regular.ttf') ?>");
		}

		body {
			font-weight: 300;
			font-size: 14px;
			font-family: Nunito;
			color: #26425f;
			background: #eef1f4;
		}

		.select2-container .select2-selection--single {
			height: 40px;
		}

		.nav-sidebar.nav-child-indent .nav-treeview {
			transition: padding .3s ease-in-out;
			padding-left: 1rem;
		}

		.table td,
		.table th {
			font-size: 14px;
			padding: 9px;
		}

		.content-wrapper {
			height: auto !important;
			min-height: calc(100vh - 57px) !important;
		}

		.select2-container--default .select2-selection--multiple .select2-selection__choice {
			color: #000;
		}

		.select2-container--default .select2-selection--multiple .select2-selection__rendered {
			padding-bottom: 5px;
		}
	</style>

	<!-- jQuery -->
	<script src="<?= public_url('/assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
	<script src="<?= public_url('/assets/adminlte/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

	<?php
	// this will auto include filepond css/js when adding filepond in public/assets
	if (file_exists('public/assets/filepond')) {
		require_once 'public/assets/filepond/filepond.php';
	}
	?>

	<script>
		const base_url = "<?= App::get('base_url') ?>";
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000
		});

		function page_loader(param) {
			$("#page_loader_content").html(param);
			$('#modalLoader').modal({
				backdrop: 'static',
				keyboard: false
			});
			$("#modalLoader").modal('show');
		}

		function page_loader_hide() {
			$("#modalLoader").modal('hide');
		}

		function success_update() {
			Toast.fire({
				type: 'success',
				title: 'Data successfully updated!'
			});
		}

		function success_add() {
			Toast.fire({
				type: 'success',
				title: 'Data successfully added!'
			});
		}

		function success_delete() {
			Toast.fire({
				type: 'success',
				title: 'Data successfully deleted!'
			});
		}

		function alertMe(type, msg) {
			Toast.fire({
				type: type,
				title: msg
			});
		}

		function failed_query() {
			Toast.fire({
				type: 'error',
				title: 'Error executing query.'
			});
		}
	</script>
</head>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
	<div class="wrapper">

		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a class="nav-link"><?= 'Access: ' . Auth::user('roles')['role'] ?></a>
				</li>
			</ul>

			<!-- Right navbar links -->
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">

				</li>
			</ul>
		</nav>
		<!-- /.navbar -->

		<?php require_once(__DIR__ . '/sidebar.php'); ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">