<?php

use App\Core\App;
use App\Core\Auth;
use App\Core\Request;
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="<?= route('/') ?>" class="brand-link text-center">
		<span class="brand-image img-circle elevation-3" style="opacity: .8"></span>
		<span class="brand-text font-weight-light"><?= App::get('config')['app']['name'] ?></span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

				<?=
				showMenus([
					'DASHBOARD' => [
						'isTree' => false,
						'icon' => 'fas fa-home',
						'child' => [
							'dashboard_access' => [
								'route' => '/',
								'name' => 'dashboard',
								'level' => 0
							]
						]
					],
					'REQUEST BOOK' => [
						'isTree' => false,
						'icon' => 'fas fa-book',
						'child' => [
							'request_book_access' => [
								'route' => '/requestbook',
								'name' => 'requestbook',
								'level' => 1
							]
						]
					],
					'NOTEBOOK' => [
						'isTree' => false,
						'icon' => 'far fa-sticky-note',
						'child' => [
							'notebook_access' => [
								'route' => '/notebook',
								'name' => 'notebook',
								'level' => 2
							]
						]
					],
					'PROJECT' => [
						'isTree' => false,
						'icon' => 'fas fa-tasks',
						'child' => [
							'project_access' => [
								'route' => '/project',
								'name' => 'project',
								'level' => 3
							]
						]
					],
					// 'MASTER DATA' => [
					// 	'isTree' => true,
					// 	'icon' => 'fas fa-book',
					// 	'child' => [
					// 		'branch_access' => [
					// 			'route' => '/branch',
					// 			'name' => 'branch',
					// 			'level' => 0
					// 		],
					// 		'employee_access' => [
					// 			'route' => '/employee',
					// 			'name' => 'employee',
					// 			'level' => 1
					// 		],
					// 		'product_access' => [
					// 			'route' => '/product',
					// 			'name' => 'product',
					// 			'level' => 2
					// 		],
					// 		'supplier_access' => [
					// 			'route' => '/supplier',
					// 			'name' => 'supplier',
					// 			'level' => 3
					// 		]
					// 	]
					// ],
					// 'TRANSACTIONS' => [
					// 	'isTree' => true,
					// 	'icon' => 'far fa-file',
					// 	'child' => [
					// 		'purchase_access' => [
					// 			'route' => '/purchase',
					// 			'name' => 'purchase',
					// 			'level' => 0
					// 		],
					// 		'receiving_access' => [
					// 			'route' => '/receiving',
					// 			'name' => 'receiving',
					// 			'level' => 1
					// 		],
					// 		'repack_access' => [
					// 			'route' => '/repack',
					// 			'name' => 'repack',
					// 			'level' => 2
					// 		],
					// 		'sales_access' => [
					// 			'route' => '/salesTransact',
					// 			'name' => 'salesTransact',
					// 			'level' => 3
					// 		]
					// 	]
					// ],
					// 'REPORT' => [
					// 	'isTree' => true,
					// 	'icon' => 'fas fa-print',
					// 	'child' => [
					// 		'inventory_report_access' => [
					// 			'route' => '/report/inventory',
					// 			'name' => 'inventory',
					// 			'level' => 0
					// 		],
					// 		'sales_report_access' => [
					// 			'route' => '/report/salesReport',
					// 			'name' => 'salesReport',
					// 			'level' => 1
					// 		]
					// 	]
					// ],
					'SETTINGS' => [
						'isTree' => true,
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