<?php

use App\Core\App;
use App\Core\Auth;
use App\Core\Request;
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary">
	<!-- Brand Logo -->
	<a href="<?= route('/') ?>" class="brand-link text-center">
		<span class="brand-image img-circle elevation-3" style="opacity: .8"></span>
		<span class="brand-text font-weight-bold"><?= App::get('config')['app']['name'] ?></span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">

		<!-- Sidebar Menu -->
		<nav>
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

				<li class="nav-header text-muted"><small><?= strtoupper(Auth::user('email')) ?></small></li>

				<?=
				showMenus([
					'DASHBOARD' => [
						'isTree' => 0,
						'icon' => 'fas fa-home',
						'child' => [
							'dashboard_access' => [
								'route' => '/',
								'name' => 'dashboard',
								'level' => 0
							]
						]
					],
					'MY CALENDAR' => [
						'isTree' => 0,
						'icon' => 'far fa-calendar-alt',
						'child' => [
							'my_calendar_access' => [
								'route' => '/mycalendar',
								'name' => 'mycalendar',
								'level' => 1
							]
						]
					],
					// 'REQUEST BOOK' => [
					// 	'isTree' => 0,
					// 	'icon' => 'fas fa-book',
					// 	'child' => [
					// 		'request_book_access' => [
					// 			'route' => '/requestbook',
					// 			'name' => 'requestbook',
					// 			'level' => 2
					// 		]
					// 	]
					// ],
					'NOTEBOOK' => [
						'isTree' => 0,
						'icon' => 'far fa-sticky-note',
						'child' => [
							'notebook_access' => [
								'route' => '/notebook',
								'name' => 'notebook',
								'level' => 3
							]
						]
					],
					'PROJECT' => [
						'isTree' => 0,
						'icon' => 'fas fa-tasks',
						'child' => [
							'project_access' => [
								'route' => '/project',
								'name' => 'project',
								'level' => 4
							]
						]
					],
					'ACTIVITY LOGS' => [
						'isTree' => 0,
						'icon' => 'fas fa-book',
						'child' => [
							'activity_log_access' => [
								'route' => '/activitylogs',
								'name' => 'activitylogs',
								'level' => 0
							]
						]
					],
					'SETTINGS' => [
						'isTree' => 1,
						'icon' => 'fas fa-cog',
						'child' => [
							'permission_access' => [
								'route' => '/settings/permission',
								'name' => 'permission',
								'level' => 0
							],
							'role_access' => [
								'route' => '/settings/roles',
								'name' => 'roles',
								'level' => 1
							],
							'user_access' => [
								'route' => '/settings/users',
								'name' => 'users',
								'level' => 2
							]
						]
					]
				])
				?>

				<li class="nav-item">
					<a href="<?= route('/logout') ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
						<i class='nav-icon fas fa-sign-out-alt'></i>
						<p>LOGOUT</p>
					</a>

					<form id="logout-form" action="<?= route('/logout') ?>" method="POST" style="display:none;">
						<?= csrf() ?>
					</form>
				</li>


			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>