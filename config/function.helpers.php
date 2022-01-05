<?php

use App\Core\Request;
use App\Core\Auth;

function getProjectName($projectCode, $col)
{
	$projName = DB()->select($col, "projects", "projectCode = '$projectCode'")->get();
	return $projName[$col];
}

function getTotalTask($projectCode)
{
	return count(getAllProjectTask($projectCode));
}

function getFinishTask($projectCode)
{
	return count(getAllProjectTask($projectCode, '2'));
}

function getAllProjectTask($projectCode, $status = '')
{
	$withFilter = ($status != '') ? "AND status = '$status'" : "";
	$tasks = DB()->selectLoop("*", "tasks", "projectCode = '$projectCode' {$withFilter}")->get();

	return $tasks;
}

function getUserTask($projectCode, $member = '')
{
	$userid = Auth::user('id');
	$withFilter = ($member == "") ? "AND user_id = '$userid'" : "AND user_id = '$member'";
	$getProject = DB()->selectLoop("*", "task_member", "projectCode = '$projectCode' {$withFilter}")
		->andFilter([
			"tasks" => "ORDER BY priority_stats DESC , taskDueDate ASC"
		])
		->with([
			"tasks" => ['task_id', 'task_id'],
			"users" => ['user_id', 'id']
		])
		->get();

	return $getProject;
}

function getProjectPercentage($finishTasks, $totalTask)
{
	$percent = ($totalTask != 0) ? ($finishTasks / $totalTask) * 100 : 0;
	return $percent;
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
