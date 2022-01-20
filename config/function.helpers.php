<?php

use App\Core\Request;
use App\Core\Auth;
use App\Core\App;
use App\Core\Database\Connection\Connection;

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
	$getTasks = DB()->selectLoop("*", "task_member", "projectCode = '$projectCode' {$withFilter}")
		->andFilter([
			"tasks" => "ORDER BY priority_stats DESC , taskDueDate ASC"
		])
		->with([
			"tasks" => ['task_id', 'task_id'],
			"users" => ['user_id', 'id']
		])
		->get();

	return $getTasks;
}

function getProjectPercentage($finishTasks, $totalTask)
{
	$percent = ($totalTask != 0) ? ($finishTasks / $totalTask) * 100 : 0;
	return number_format($percent, 2);
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
				$menus .= '<li class="nav-item"><a href="' . route($child_name['route']) . '" class="nav-link ' . isActive($child_name['name']) . '"><i class="nav-icon ' . $parentData['icon'] . '"></i><p>' . $parent . '</p></a></li>';
			}
		}
	}
	return $menus;
}

function log_activity($msg, $module, $user_id, $task_code)
{
	$data = array(
		'log' => $msg,
		'module' => $module,
		'date' => date("Y-m-d H:i:s"),
		'user_id' => $user_id,
		'task_code' => $task_code
	);
	DB()->insert("activity_logs", $data);
}

function getTaskMember($project_code, $task_id)
{
	$loop_task = DB()->selectLoop("*", "task_member", "projectCode = '$project_code' AND task_id = '$task_id'")
		->with([
			'users' => ['user_id', 'id']
		])
		->get();

	foreach ($loop_task as $task_list) {
		if (!empty($task_list['users'])) {
			$_task_list = (!empty($task_list['users'][0]))
				? $task_list['users'][0]
				: $task_list['users'];

			$showPic = ($_task_list['slug'] != "")
				? $_task_list['slug']
				: 'user_default_avatar.png';

			$task_member_data[] = array(
				'task_member_id'    => $task_list['user_id'],
				'member_name'       => $_task_list['fullname'],
				'member_avatar'     => public_url("/assets/pms/user_avatar/{$showPic}"),
				'invite_status'     => $task_list['invite_status']
			);
		}
	}

	return $task_member_data;
}

function allowDeleteTask($project_code, $task_id)
{
	$user_id = Auth::user('id');
	$_task = DB()->select("COUNT(*) as totals", "task_member", "projectCode = '$project_code' AND task_id = '$task_id' AND invite_status = 0 AND user_id = '$user_id'")->get();
	$allow = ($_task['totals'] > 0) ? 1 : 0;
	return $allow;
}

function isProjectManager($projectCode)
{
	$isAllowedToViewAll = [
		1,
		2
	];

	$treatRolesAsPm = implode(',', $isAllowedToViewAll);
	$optin_id = Auth::user('id');
	$getPM = DB()->select("user_id", "project_member", "projectCode = '$projectCode' AND user_id = '$optin_id' AND role_id IN ($treatRolesAsPm)")->get();

	if (!empty($getPM)) {
		if ($getPM['user_id'] > 0) {
			$result = 1;
		} else {
			$result = 0;
		}
	} else {
		$result = 0;
	}

	return $result;
}

function isProjectTeamLeader($projectCode)
{
	$optin_id = Auth::user('id');
	$getPM = DB()->select("user_id", "project_member", "projectCode = '$projectCode' AND user_id = '$optin_id' AND role_id = '4'")->get();

	if (!empty($getPM)) {
		if ($getPM['user_id'] > 0) {
			$result = 1;
		} else {
			$result = 0;
		}
	} else {
		$result = 0;
	}

	return $result;
}

function getProjectMember($code)
{
	$data = [];
	$query = DB()->selectLoop("*", "project_member", "projectCode = '$code' AND user_id != 0 GROUP BY user_id")
		->with([
			"users" => ['user_id', 'id']
		])
		->get();
	foreach ($query as $getRow) {
		if (!empty($getRow['users'])) {

			$_task = (!empty($getRow['users'][0]))
				? $getRow['users'][0]
				: $getRow['users'];

			$data[] = array(
				'user_id'   => $getRow['user_id'],
				'memberName' => $_task['fullname'],
				'role'      => $getRow['role_id']
			);
		}
	}

	return $data;
}

function getUserAvatar($user_id)
{
	$res = DB()->select("slug", "users", "id = '$user_id'")->get();
	$showPic = ($res['slug'] != "") ? $res['slug'] : 'user_default_avatar.png';
	return public_url("/assets/pms/user_avatar/{$showPic}");
}

function getMyGroups($user_id)
{
	$data = [];
	$loop_group = DB()->selectLoop("*", "teams", "created_by = '$user_id'")->get();
	if (count($loop_group) > 0) {
		foreach ($loop_group as $group_list) {
			$data[] = array(
				'team_id' => $group_list['team_id'],
				'team_code' => $group_list['teamCode'],
				'team_name' => $group_list['teamName'],
			);
		}
	}

	return $data;
}

function getUserName($userID)
{
	$getUserName = DB()->select("fullname", "users", "id = '$userID'")->get();
	return (!empty($getUserName)) ? $getUserName['fullname'] : '';
}

function dueDateForthisWeek()
{
	$userid = Auth::user('id');
	$today = date('Y-m-d');
	$tasks = DB()->selectLoop("task.*, taskmem.*", "tasks AS task, task_member as taskmem ", "taskmem.task_id = task.task_id AND taskmem.user_id = '$userid' AND task.taskDueDate <= '$today' AND task.status = '1' ORDER BY task.priority_stats DESC , task.taskDueDate ASC")
		->with([
			'projects' => ['projectCode', 'projectCode'],
			'users' => ['user_id', 'id']
		])
		->get();

	return $tasks;
}

function conns()
{
	return Connection::make(App::get('config')['database']);
}

function clean($str)
{
	$str = @trim($str);
	$str = stripslashes($str);

	return htmlentities(conns()->quote($str));
}

function totalRequestBook()
{
	$user_id = Auth::user('id');
	$res = DB()->select("count(request_id) as idCounter", "request_logs", "status = 0 GROUP BY status")->get();
	$total = ($user_id == 19) ? (($res['idCounter'] > 0) ? $res['idCounter'] : "") : "";
	return $total;
}
