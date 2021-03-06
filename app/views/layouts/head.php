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

	<link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>

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
	<link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/fullcalendar/main.css') ?>">
	<link rel="stylesheet" href="<?= public_url('/assets/adminlte/plugins/sweetalert2/bootstrap-4.min.css') ?>">

	<style>
		@font-face {
			font-family: Nunito;
			src: url("<?= public_url('/assets/adminlte/fonts/Nunito-Regular.ttf') ?>");
		}

		.nav-link {
			font-weight: 500;
		}

		.brand-link {
			font-size: 14px;
			padding: 0.7rem;
			border-bottom: 0px solid #dee2e6 !important;
		}

		.info-box {
			border: 1px solid #c9c9c9;
		}

		.main-header .nav-link {
			height: 1.7rem;
		}

		.main-header {
			border-bottom: 0px solid #dee2e6;
		}

		.main-footer {
			border-top: 0px solid #dee2e6;
		}

		body {
			/* font-weight: 300; */
			font-size: 14px;
			/* font-family: "Open Sans"; */
			color: #222222 !important;
			background: #fff;
		}

		.select2-container .select2-selection--single {
			height: 40px;
		}

		.nav-sidebar.nav-child-indent .nav-treeview {
			transition: padding .3s ease-in-out;
			padding-left: 1rem;
		}

		.navbar-expand .navbar-nav {
			align-items: baseline;
		}

		.navbar-expand .navbar-nav .nav-link {
			padding-right: 0.6rem;
			padding-left: 0.6rem;
		}

		.navbar-badge {
			right: 0px;
		}

		.table td,
		.table th {
			font-size: 14px;
			padding: 9px;
		}

		.content-wrapper {
			height: auto !important;
			/* background-color: #ffffff; */
			min-height: calc(100vh - 57px) !important;
		}

		.select2-container--default .select2-selection--multiple .select2-selection__choice {
			color: #000;
		}

		.select2-container--default .select2-selection--multiple .select2-selection__rendered {
			padding-bottom: 5px;
		}

		.card {
			border-radius: 10px !important;
		}

		.bg-green {
			background-color: #28a745 !important;
		}

		.bg-red {
			background-color: #dc3545 !important;
		}

		.bg-orange {
			background-color: #ff851b !important;
		}

		/* 
        *   CHAT SCROLL
        */
		.msg_chat_scroll {
			overflow: hidden;
		}

		.msg_chat_scroll::-webkit-scrollbar-track {
			/* inset 0 0 6px rgba(0,0,0,0.3) */
			-webkit-box-shadow: transparent;
			background-color: transparent;
		}

		.msg_chat_scroll::-webkit-scrollbar {
			width: 5px;
			background-color: transparent;
		}

		.msg_chat_scroll::-webkit-scrollbar-thumb {
			background-color: #676767;
			border: 0px solid #555555;
			border-radius: 4px;
		}

		.msg_chat_scroll:hover {
			overflow: auto;
		}

		/* 
        *   CHAT SCROLL STEADY
        */
		.msg_chat_scroll_steady {
			overflow: auto;
		}

		.msg_chat_scroll_steady::-webkit-scrollbar-track {
			/* inset 0 0 6px rgba(0,0,0,0.3) */
			-webkit-box-shadow: transparent;
			background-color: transparent;
		}

		.msg_chat_scroll_steady::-webkit-scrollbar {
			width: 5px;
			background-color: transparent;
		}

		.msg_chat_scroll_steady::-webkit-scrollbar-thumb {
			background-color: #676767;
			border: 0px solid #555555;
			border-radius: 4px;
		}

		.msg_chat_scroll_steady:hover {
			overflow: auto;
		}

		.control-sidebar {
			top: 0 !important;
			bottom: 0 !important;
		}

		.cardNote {
			border: 0.5px solid #a3a3a38f
		}

		.note-title {
			background-color: transparent;
			border: none;
			padding: 0;
			outline: none;
			overflow: hidden;
			resize: none;
			vertical-align: top;
			width: 100%;
		}

		.noteEditor {
			font-size: 20px;
			font-family: inherit;
			white-space: pre-wrap;
			outline: none;
			-webkit-user-modify: read-write-plaintext-only;
		}

		[contenteditable][placeholder]:empty:before {
			content: attr(placeholder);
			position: absolute;
			color: gray;
			background-color: transparent;
		}

		.sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active,
		.sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
			/* background-color: #2c3e50;
			color: #fff; */
			background-color: rgba(0, 0, 0, .1);
			color: #212529;
			box-shadow: none;
		}

		.nav-sidebar .nav-link>.right,
		.nav-sidebar .nav-link>p>.right {
			top: 10px;
		}
	</style>

	<!-- jQuery -->
	<script src="<?= public_url('/assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
	<script src="<?= public_url('/assets/adminlte/js/jquery-ui.min.js') ?>"></script>
	<script src="<?= public_url('/assets/adminlte/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

	<?php
	// this will auto include filepond css/js when adding filepond in public/assets
	if (file_exists('public/assets/filepond')) {
		require_once 'public/assets/filepond/filepond.php';
	}
	?>

	<script>
		const base_url = "<?= App::get('base_url') ?>";

		$(function() {
			$("body").tooltip({
				selector: '[data-toggle=tooltip]',
				trigger: 'hover',
				container: 'body'
			});
		});

		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000
		});

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
			type = (typeof type === 'undefined') ? 'info' : type;
			toastr.options.timeOut = 2500;
			toastr[type](msg);
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

				<!-- <li class="nav-item dropdown" style="font-size: 14px;">
					<a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
						<i class="far fa-bell"></i>
						<span class="badge badge-warning navbar-badge">15</span>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
						<span class="dropdown-item dropdown-header">15 Notifications</span>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fas fa-envelope mr-2"></i> 4 new messages
							<span class="float-right text-muted text-sm">3 mins</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fas fa-users mr-2"></i> 8 friend requests
							<span class="float-right text-muted text-sm">12 hours</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fas fa-file mr-2"></i> 3 new reports
							<span class="float-right text-muted text-sm">2 days</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
					</div>
				</li> -->

				<li class="nav-item" style="font-size: 14px;">

					<a class="nav-link" href="<?= route('/profile') ?>" aria-expanded="true">
						<div class="mt-1"><img src="<?= getUserAvatar(Auth::user('id')) ?>" style="width:20px;height: 20px;object-fit: cover;margin-left: 1px;margin-right: 2px;cursor: default;" class="rounded-circle"><span><?= Auth::user('fullname') ?></span></div>
					</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" data-toggle="tooltip" data-placement="bottom" data-original-title="Notebook">
						<i class="far fa-sticky-note" style="font-size: 16px;"></i>
					</a>
				</li>

			</ul>
		</nav>
		<!-- /.navbar -->

		<?php require_once(__DIR__ . '/sidebar.php'); ?>

		<!-- Content Wrapper. Contains page content -->

		<div class="content-wrapper bg-white">