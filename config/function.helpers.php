<?php

use App\Core\Request;

function getBranches()
{
	$branches = DB()->selectLoop("*", "branch", "status = 0 ORDER BY name ASC")->get();
	return $branches;
}

function getBranchName($branch_id)
{
	$branch = DB()->select("name", "branch", "id = '$branch_id'")->get();
	return $branch['name'];
}

function selectTedBranch()
{
	return (!empty($_SESSION['system']['branch_name'])) ? $_SESSION['system']['branch_name'] : "";
}

function getProductName($id)
{
	$prod = DB()->select("name", "products", "id = '$id'")->get();
	return $prod['name'];
}

function getProductUnit($id)
{
	$prod = DB()->select("name", "product_unit", "id = '$id'")->get();
	return $prod['name'];
}

function isActive($uri)
{
	return (strpos(Request::uri(), $uri) !== false) ? 'active' : '';
}

function collapseTree($uri)
{
	$test = '';
	$bal = 0;
	foreach ($uri as $word) {
		if (strpos(Request::uri(), $word) !== false) {
			$bal++;
		}
	}

	if ($bal > 0) {
		$test = "has-treeview menu-open";
	}

	return $test;
}

function getPermissionName($id)
{
	$res = DB()->select("title", "permissions", "id = '$id'")->get();
	return $res['title'];
}

function sideAccess($parent)
{
	if ($_SESSION['AUTH']['role_id'] != null) {
		if (!empty($_SESSION['AUTH']['roles'])) {
			$authPermission = $_SESSION['AUTH']['roles']['permission'];

			$menus = [];
			foreach ($parent as $main => $menu) {
				foreach (explode(',', $authPermission) as $permission) {
					$prmssn = DB()->select("*", "permissions", "id = '$permission'")->get();
					if (array_key_exists($prmssn['title'], $menu['child'])) {
						$menus[$main]['isTree'] = $menu['isTree'];
						$menus[$main]['icon'] = $menu['icon'];

						foreach ($menu['child'] as $childKey => $child) {
							if ($prmssn['title'] == $childKey) {
								$menus[$main]['child'][$childKey] = $child;
							}
						}
					}
				}
			}

			return $menus;
		}
	}
}

function showMenus($sideMenuData)
{
	$menus = "";
	foreach (sideAccess($sideMenuData) as $parent => $parentData) {

		if ($parentData['isTree']) {

			$menuContents = [];
			foreach ($parentData['child'] as $access => $menuName) {
				$menuContents[] = $menuName['name'];
			}

			$menus .= '<li class="nav-item ' . collapseTree($menuContents) . '"><a href="#" class="nav-link"><i class="nav-icon ' . $parentData['icon'] . '"></i><p>' . $parent . '<i class="right fa fa-angle-left"></i></p></a><ul class="nav nav-treeview">';

			foreach ($parentData['child'] as $menu_name) {
				$menus .= '<li class="nav-item"><a href="' . route($menu_name['route']) . '" class="nav-link ' . isActive($menu_name['name']) . '" style="padding-left: 30px;"><i class="nav-icon far fa-circle"></i><p>' . ucwords($menu_name['name']) . '</p></a></li>';
			}

			$menus .= '</ul></li>';
		} else {
			foreach ($parentData['child'] as $child_name) {
				$menus .= '<li class="nav-item"><a href="' . route($child_name['route']) . '" class="nav-link ' . isActive($child_name['name']) . '"><i class="nav-icon ' . $parentData['icon'] . '"></i><p>' . $parent . '</p></li>';
			}
		}
	}

	return $menus;
}


// if (in_array($prmssn['title'], $menu['DASHBOARD'])) {
// 	if ($prmssn['title'] == $menu['DASHBOARD'][$prmssn['title']]) {
// 		$menus .= '<li class="nav-item">
// 		<a href="' . route('/dashboard') . '" class="nav-link ' . isActive('dashboard') . '">
// 			<i class="nav-icon fas fa-home"></i>
// 			<p>' . $main . '</p>
// 		</a>
// 	</li>';
// 	}
// }

// if (in_array($prmssn['title'], $menu['MASTER DATA'])) {

// 	$menus .= '<li class="nav-item ' . collapseTree(['branch', 'employee', 'product', 'supplier']) . '">
// 	<a href="#" class="nav-link">
// 		<i class="nav-icon fas fa-book"></i>
// 		<p>
// 			MASTER DATA
// 			<i class="right fa fa-angle-left"></i>
// 		</p>
// 	</a><ul class="nav nav-treeview">';

// 	if ($prmssn['title'] == $menu['MASTER DATA'][$prmssn['title']]) {
// 		$menus .= '
// 			<li class="nav-item">
// 				<a href="' . route('/branch') . '" class="nav-link ' . isActive('branch') . '">
// 					<i class="nav-icon far fa-circle"></i>
// 					<p>Branch</p>
// 				</a>
// 			</li>
// 			<li class="nav-item">
// 				<a href="' . route('/employee') . '" class="nav-link ' . isActive('employee') . '">
// 					<i class="nav-icon far fa-circle"></i>
// 					<p>Employee</p>
// 				</a>
// 			</li>
// 			<li class="nav-item">
// 				<a href="' . route('/product') . '" class="nav-link ' . isActive('product') . '">
// 					<i class="nav-icon far fa-circle"></i>
// 					<p>Products</p>
// 				</a>
// 			</li>
// 			<li class="nav-item">
// 				<a href="' . route('/supplier') . '" class="nav-link ' . isActive('supplier') . '">
// 					<i class="nav-icon far fa-circle"></i>
// 					<p>Supplier</p>
// 				</a>
// 			</li>';
// 	}
// 	$menus .= '</ul></li>';