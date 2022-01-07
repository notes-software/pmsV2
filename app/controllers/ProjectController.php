<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Auth;

class ProjectController
{
	public function index()
	{
		// abort_if(gate_denies('branch_access'), '403 Forbidden');

		$pageTitle = "Projects";
		$user_id = Auth::user('id');

		$projects = DB()->selectLoop("t1.CODE as project_code", "(SELECT pm.projectCode AS CODE FROM project_member AS pm, team_member AS tm WHERE pm.teamCode = tm.teamCode AND tm.user_id = '$user_id' AND pm.type = 1 GROUP BY pm.projectCode UNION ALL SELECT pjm.projectCode AS CODE FROM project_member AS pjm, projects AS p WHERE p.projectCode = pjm.projectCode AND pjm.user_id = '$user_id' AND pjm.type = 0 AND p.status = 0 UNION ALL SELECT prj.projectCode as CODE FROM projects as prj WHERE prj.proj_pm = '$user_id' AND prj.status = 0) AS t1", "t1.CODE != '' GROUP BY t1.CODE")->get();

		return view('/projects/index', compact('pageTitle', 'projects'));
	}

	public function view($projectCode)
	{
		$project = DB()->select('*', 'projects', "projectCode = '$projectCode' ORDER BY projectName ASC")->get();
		$projectMembers = getProjectMember($projectCode);

		$pageTitle = $project['projectName'];
		return view('/projects/detail', compact('pageTitle', 'project', 'projectMembers'));
	}

	public function task()
	{
		$request = Request::validate('/project/' . $_REQUEST['project_code']);

		$response['todo'] = [];
		$response['inprogress'] = [];
		$response['done'] = [];
		$tasks = getUserTask($request['project_code'], $request['memberSelected']);

		// var_dump($tasks);
		// die();

		foreach ($tasks as $task) {
			if (!empty($task['tasks'])) {
				$_task = (!empty($task['tasks'][0]))
					? $task['tasks'][0]
					: $task['tasks'];

				if ($_task['task_id'] != null) {

					if ($_task['priority_stats'] == 0) {
						$prio = "green";
					} else if ($_task['priority_stats'] == 1) {
						$prio = "orange";
					} else {
						$prio = "red";
					}

					if ($_task['status'] == 0) {
						$module = "TO DO";
					} else if ($_task['status'] == 1) {
						$module = "IN PROGRESS";
					} else if ($_task['status'] == 2) {
						$module = "DONE";
					}

					$items = array(
						"task_id"   => $_task['task_id'],
						"priority"  => $_task['priority_stats'],
						"priority_color" => $prio,
						"date"      => date('m/d/Y', strtotime($_task['taskDueDate'])),
						"task_code" => $_task['task_code'],
						"task"      => html_entity_decode($_task['taskDescription']),
						"task_member"   => getTaskMember($_task['projectCode'], $task['task_id']),
						"allow_delete"  => allowDeleteTask($_task['projectCode'], $task['task_id']),
						"finishDate" => date("Y-n-d", strtotime($_task['finishDate'])),
						"module" => $module
					);

					if ($_task['status'] == 0) {
						array_push($response['todo'], $items);
					} else if ($_task['status'] == 1) {
						array_push($response['inprogress'], $items);
					} else if ($_task['status'] == 2) {
						array_push($response['done'], $items);
					}
				}
			}
		}

		echo json_encode($response);
	}

	public function updateTaskType()
	{
		$request = Request::validate('/project/' . $_REQUEST['project_code']);
		$task_id = $request["id"];
		$task_type = $request["type"];

		$task_code = DB()->select("task_code", "tasks", "task_id = '$task_id'")->get();
		$data = array(
			'status' => $task_type,
			"finishDate" => date("Y-m-d H:i:s")
		);
		$result = DB()->update("tasks", $data, "task_id = '$task_id'");
		if ($result) {
			if ($task_type != 0) {
				if ($task_type == 1) {
					$types = "ongoing";
				} else if ($task_type == 2) {
					$types = "done";
				}
				$msg = Auth::user('fullname') . " updated a task [" . $task_code["task_code"] . "] to " . $types;
				$module = "Task";
				$user_id = Auth::user('id');
				log_activity($msg, $module, $user_id, $task_code['task_code']);
			}
		}
	}
}
